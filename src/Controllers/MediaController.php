<?php

namespace Src\Controllers;

use Src\Gateways\MediaGateway;
use Src\System\Uuid;

class MediaController
{
    private $db;
    private $requestMethod;
    private $mediaId;
    private $mediaGateway;

    public function __construct($db, $requestMethod, $mediaId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->mediaId = $mediaId;
        $this->mediaGateway = new MediaGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->mediaId) {
                    $response = $this->getMedia($this->mediaId);
                } else {
                    $response = $this->getAllMedias();
                };
                break;
            case 'POST':
                $response = $this->createMediaFromRequest();
                break;
            case 'DELETE':
                $response = $this->deleteMedia($this->mediaId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if ($response['body']) {
            echo json_encode($response['body']);
        }
    }

    private function getAllMedias()
    {
        $result = $this->mediaGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body']['data'] = $result;
        return $response;
    }

    private function getMedia($id)
    {
        $result = $this->mediaGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body']['data'] = $result;
        return $response;
    }

    private function createMediaFromRequest()
    {
        $input = (array)json_decode(file_get_contents('php://input'), true);
        if (!isset($input['account_id'])) {
            return $this->unprocessableResponse();
        }
        $input['media_storage_name'] = Uuid::getUuid($input['media_name']);
        $apiFile = getcwd() . getenv('STORAGE_PATH') . $input['media_storage_name'];
        file_put_contents($apiFile, base64_decode($input['media_content']));
        $this->mediaGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body']['data'] = null;
        return $response;
    }

    private function deleteMedia($id)
    {
        $result = $this->mediaGateway->find($id);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $apiFile = getcwd() . getenv('STORAGE_PATH') . $result['media_storage_name'];
        unlink($apiFile);
        $this->mediaGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body']['data'] = null;
        return $response;
    }

    private function validateMedia($input)
    {
        if (!isset($input['media_name'])) {
            return false;
        }
        return true;
    }

    private function unprocessableResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body']['error'] = 'Invalid input';
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body']['data'] = null;
        return $response;
    }
}