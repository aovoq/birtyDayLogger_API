<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../jwt.php';

function res($success, $status, $message, $extra = []) {
   return array_merge([
      'success' => $success,
      'status' => $status,
      'message' => $message
   ], $extra);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $bearer_token = get_bearer_token();
   $is_jwt_valid = is_jwt_valid($bearer_token);

   if ($is_jwt_valid['status']) {
      $data = json_decode(file_get_contents("php://input"), TRUE);
      if (
         !isset($data['name']) || !isset($data['date']) ||
         empty(trim($data['name'])) || empty(trim($data['date']))
      ) {
         $res = res(0, 422, '内容に不備があります。');
      } else {
         $jsonfile = "../data/user/" . $is_jwt_valid['id'] . ".json";
         if (file_exists($jsonfile)) {
            $myJson = file_get_contents($jsonfile);
            $myJson = json_decode($myJson, TRUE);
         }
         $myJson[] = array(
            "id" => uniqid(),
            "name" => $data['name'],
            "date" => $data['date'],
            "note" => $data['note']
         );
         $myJson = json_encode($myJson);
         file_put_contents($jsonfile, $myJson);
         $res = res(1, 201, 'Added data');
      }
   } else {
      $res = array('error' => 'Access denied');
   }
} else {
   $res = res(0, 404, 'Page Not Found!');
}

echo json_encode($res);
