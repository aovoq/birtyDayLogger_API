<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '../jwt.php';

$bearer_token = get_bearer_token();
$is_jwt_valid = is_jwt_valid($bearer_token);

if ($is_jwt_valid['status']) {
   $usersJson = file_get_contents('../data/users.json');
   $usersJson = json_decode($usersJson, TRUE);
   $matchIndex = array_search($is_jwt_valid['id'], array_column($usersJson, 'id'));
   $userData = $usersJson[$matchIndex];

   $res = array('success' => 1, 'user' => ['id' => $userData['id'], 'email' => $userData['email']]);
} else {
   $res = array('success' => 0, 'message' => 'Token is not found');
}

echo json_encode($res);
