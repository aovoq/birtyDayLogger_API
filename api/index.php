<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require_once 'jwt.php';

function res($success, $status, $message, $extra = []) {
   return array_merge([
      'success' => $success,
      'status' => $status,
      'message' => $message
   ], $extra);
}

$res = res(
   1,
   200,
   'Hello, this API is for the birthDayLogger.',
   ['version' => '0.0.5']
);

echo json_encode($res);
