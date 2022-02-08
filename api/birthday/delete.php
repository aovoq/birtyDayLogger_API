<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

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
      if (!isset($data['id']) || empty(trim($data['id']))) {
         $res = res(0, 422, '内容に不備があります。');
      } else {
         $jsonfile = "../data/user/" . $is_jwt_valid['id'] . ".json";
         if (!file_exists($jsonfile)) {
            $res = res(0, 422, '一つもデータがありません。');
         } else {
            $myJson = file_get_contents($jsonfile);
            $myJson = json_decode($myJson, TRUE);

            $removeIndex = array_search($data['id'], array_column($myJson, 'id'));
            if ($removeIndex === false) {
               $res = res(0, 422, 'IDが間違えています。');
            } else {
               array_splice($myJson, $removeIndex, 1);

               $myJson = json_encode($myJson);
               file_put_contents($jsonfile, $myJson);
               $res = res(1, 201, 'Deleted data');
            }
         }
      }
   } else {
      $res = array('error' => 'Access denied');
   }
} else {
   $res = res(0, 404, 'Page Not Found!');
}

echo json_encode($res);
