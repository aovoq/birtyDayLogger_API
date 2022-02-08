<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: DELETE");

require_once '../jwt.php';

function res($success, $status, $message, $extra = []) {
   return array_merge([
      'success' => $success,
      'status' => $status,
      'message' => $message
   ], $extra);
}

function deleteUser($json, $id, $password) {
   $userIndex = array_search($id, array_column($json, 'id'));
   if ($userIndex === false) {
      $return = 'invalid_user';
   } elseif (!password_verify($password, $json[$userIndex]['password'])) {
      $return = 'wrong_password';
   } else {
      array_splice($json, $userIndex, 1);
      $return = $json;
   }
   return $return;
}

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
   $bearer_token = get_bearer_token();
   $is_jwt_valid = is_jwt_valid($bearer_token);

   if ($is_jwt_valid['status']) {
      $data = json_decode(file_get_contents("php://input"), TRUE);
      if (!isset($data['password']) || empty(trim($data['password']))) {
         $res = res(0, 422, 'パスワードを入力してください。');
      } else {
         $password = trim($data['password']);
         if (strlen($password) < 8) {
            $res = res(0, 422, 'パスワードが短すぎます、8文字以上入力してください。');
         } else {
            $usersJson = file_get_contents('../data/users.json');
            $usersJson = json_decode($usersJson, TRUE);
            $deletedJson = deleteUser($usersJson, $is_jwt_valid['id'], $password);

            if ($deletedJson === 'invalid_user') {
               $res = res(0, 422, 'アカウントが存在しません');
            } elseif ($deletedJson === 'wrong_password') {
               $res = res(0, 422, 'パスワードが正しくありません。');
            } else {
               $usersJson = json_encode($deletedJson);
               file_put_contents('../data/users.json', $usersJson);
               unlink('../data/user/' . $is_jwt_valid['id'] . '.json');
               $res = res(1, 410, 'Deleted User');
            }
         }
      }
   } else {
      $res = res(0, 404, 'Page Not Found.');
   }
}

echo json_encode($res);
