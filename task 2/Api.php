<?php

// Include the necessary files
require_once 'Database.php';
require_once 'ObjectOperations.php';

header("Content-Type: application/json");

$db = new Database();
$objectOps = new ObjectOperations($db->conn);

$method = $_SERVER['REQUEST_METHOD'];
$pathInfo = $_SERVER['PATH_INFO'] ?? '';
$path = explode('/', trim($pathInfo, '/'));

$resource = $path[0];
$id = $path[1] ?? null;

switch ($resource) {
    case 'object':
        switch ($method) {
            case 'GET':
                if ($id) {
                    $objectOps->read($id);
                    $response = ['object' => $objectOps];
                } else {
                    $response = ['message' => 'Provide object id'];
                }
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                $objectOps->number = $data['number'] ?? '';
                $objectOps->status = $data['status'] ?? '';
                $objectOps->create();
                $response = ['object_id' => $objectOps->id];
                break;
            case 'PUT':
                if ($id) {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $objectOps->read($id);
                    $objectOps->number = $data['number'] ?? $objectOps->number;
                    $objectOps->status = $data['status'] ?? $objectOps->status;
                    $objectOps->update();
                    $response = ['message' => 'Object updated'];
                } else {
                    $response = ['message' => 'Object ID required'];
                }
                break;
            case 'DELETE':
                if ($id) {
                    $objectOps->id = $id;
                    $objectOps->delete();
                    $response = ['message' => 'Object deleted'];
                } else {
                    $response = ['message' => 'Object ID required'];
                }
                break;
            default:
                $response = ['message' => 'Method Not Allowed'];
                break;
        }
        break;

    case 'search':
        $data = json_decode(file_get_contents('php://input'), true);

        $number = $data['number'] ?? '';
        $date = $data['date'] ?? '';
        $current_status = $data['current_status'] ?? '';
        $past_status = $data['past_status'] ?? '';

        if($number || $date || $current_status || $past_status)
            $response = ['object' => $objectOps->search($number, $date, $current_status, $past_status)];
         else {
            $response = ['message' => 'Nothing found'];
        }
        break;
    default:
        $response = ['message' => 'Bad Request'];
        break;
}

http_response_code(200);
echo json_encode($response);