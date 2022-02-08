<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
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

$bearer_token = get_bearer_token();
$is_jwt_valid = is_jwt_valid($bearer_token);

if ($is_jwt_valid['status']) {
   $jsonfile = "../data/user/" . $is_jwt_valid['id'] . ".json";
   if (!file_exists($jsonfile)) {
      $res = res(0, 422, '一つもデータがありません');
   } else {
      $myJson = file_get_contents($jsonfile);
      $myJson = json_decode($myJson);
      $res = res(1, 200, '', ["birthdays" => $myJson]);
   }
} else {
   $res = res(0, 422, 'アクセスが禁止されました。');
}

echo json_encode($res);
