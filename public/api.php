<?php
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

define('PRIVATE_DIR', __DIR__ . "/../private");
include PRIVATE_DIR . "/Request.php";
$request = new Request(PRIVATE_DIR . "/token.txt");

header('Content-type: application/json');

$output['status'] = false;
if (isset($_REQUEST['api'])) {
    if ($_REQUEST['api'] === 'post') {
        if (
            !isset($_POST['title']) ||
            !isset($_POST['content']) ||
            !is_string($_POST['title']) ||
            !is_string($_POST['content']) 
        ) {
            echo json_encode($output, JSON_PRETTY_PRINT);
            return;
        }
        
        $data = [
            'title' => $_POST['title'],
            'content' => $_POST['content']
        ];
        //$response = $request->post("jwt-auth/v1/token/validate", $data, true);
        $response = $request->post("wp/v2/book", $data, true);
        if (isset($response['id'])) {
            $output['status'] = true;
            $output['data'] = $response;
        }
        else {
            getToken($request);
            $response = $request->post("wp/v2/book", $data, true);
            if (isset($response['id'])) {
                $output['status'] = true;
                $output['data'] = $response;
            }
        }
    }
}

echo json_encode($output, JSON_PRETTY_PRINT);


function getToken($request) {
    $data = [
        'username' => 'root',
        'password' => 'root'
    ];
    $response = $request->post("jwt-auth/v1/token", $data);
    if (array_key_exists('token', $response)) {
        file_put_contents(PRIVATE_DIR . "/token.txt", $response['token']);
        $request->setToken($response['token']);
    }
}