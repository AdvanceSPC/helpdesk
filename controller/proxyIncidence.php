<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("../config/connection.php");
require __DIR__ . '/../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Dotenv\Dotenv;

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (Exception $e) {
    error_log('Error al cargar Dotenv: ' . $e->getMessage());
}

if (!isset($_ENV['AWS_ACCESS_KEY_ID']) || !isset($_ENV['AWS_SECRET_ACCESS_KEY']) || !isset($_ENV['HUBSPOT_API_KEY'])) {
    error_log('Faltan variables de entorno críticas');
    exit('Error de configuración');
}

$awsAccesKeyId = $_ENV['AWS_ACCESS_KEY_ID'];
$awsSecretAccessKey = $_ENV['AWS_SECRET_ACCESS_KEY'];
$hubspotApiKey = $_ENV['HUBSPOT_API_KEY'];

error_log('AWS_ACCESS_KEY_ID: ' . $awsAccesKeyId);
error_log('AWS_SECRET_ACCESS_KEY: ' . $awsSecretAccessKey);
error_log('HUBSPOT_API_KEY: ' . $hubspotApiKey);
error_log('Ruta del .env: ' . __DIR__ . '/../.env');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['us_Id'];
    $campaignName = $_POST['campaignName'];
    $channels = $_POST['channels'];
    $incidents = $_POST['incidents'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $afecta = $_POST['afecta'];
    $attachmentUrl = null;
    $category = "Incidencia";

    error_log("Verificando archivos: " . print_r($_FILES, true));

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['attachment']['tmp_name'];
        $fileName = $_FILES['attachment']['name'];

        error_log("Ruta temporal del archivo: $fileTmpPath");
        error_log("Nombre del archivo: $fileName");

        if (!empty($fileName)) {
            $bucketName = 'ticketaws';
            $region = 'us-east-1';
            $s3Client = new S3Client([
                'region'  => $region,
                'version' => 'latest',
                'credentials' => [
                    'key'    => $awsAccesKeyId,
                    'secret' => $awsSecretAccessKey,
                ],
            ]);

            try {
                $result = $s3Client->putObject([
                    'Bucket' => $bucketName,
                    'Key'    => $fileName,
                    'SourceFile' => $fileTmpPath,
                    'ACL'    => 'public-read',
                ]);

                error_log("Resultado de S3: " . print_r($result, true));

                $attachmentUrl = $result['ObjectURL'];
            } catch (AwsException $e) {
                error_log("Error al subir archivo: " . $e->getMessage());
                exit;
            }
        } else {
            error_log("El nombre del archivo está vacío.");
            exit;
        }
    } else {
        if (!isset($_FILES['attachment'])) {
            error_log("No se encontró el archivo.");
        } elseif ($_FILES['attachment']['error'] != UPLOAD_ERR_OK) {
            error_log("Error en el archivo: " . $_FILES['attachment']['error']);
        }
    }

    $curl = curl_init();

    $data = [
        "properties" => [
            "hs_pipeline" => "0",
            "hs_pipeline_stage" => "1",
            "hs_ticket_priority" => "HIGH",
            "subject" => $subject,
            "descripcion" => $description,
            "campania" => $campaignName,
            "canal" => $channels,
            "incidente" => $incidents,
            "afectacion" => $afecta,
            "archivo" => $attachmentUrl,
            "hs_ticket_category" => $category,
        ]
    ];

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.hubapi.com/crm/v3/objects/tickets',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $hubspotApiKey
        ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);

    $responseData = json_decode($response, true);

    if ($httpCode == 201) {
        $ticketId = $responseData['id'];

        $sql = "INSERT INTO tickets (ticket_id, campaign_name, channels, incidents, subject, description, afecta, user_id, attachment, categoria) 
                VALUES (:ticket_id, :campaign_name, :channels, :incidents, :subject, :description, :afecta, :user_id, :attachment, :categoria)";

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':ticket_id', $ticketId);
        $stmt->bindParam(':campaign_name', $campaignName);
        $stmt->bindParam(':channels', $channels);
        $stmt->bindParam(':incidents', $incidents);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':afecta', $afecta);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':attachment', $attachmentUrl);
        $stmt->bindParam(':categoria', $category);

        if ($stmt->execute()) {
            $responseData['ticketId'] = $ticketId;
            header('Content-Type: application/json');
            echo json_encode($responseData);
        } else {
            error_log("Error al guardar el ticket en la base de datos.");
            echo "Error al guardar el ticket en la base de datos.";
        }
    } else {
        error_log("Error de API: " . $response);
        echo $response;
    }
}
?>