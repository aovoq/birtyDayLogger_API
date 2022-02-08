<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

require_once '../jwt.php';
$res = '';

function res($success, $status, $message, $extra = []) {
   return array_merge([
      'success' => $success,
      'status' => $status,
      'message' => $message
   ], $extra);
}

function checkUser($json, $email, $password) {
   $userIndex = array_search($email, array_column($json, 'email'));
   if ($userIndex !== false) {
      if (password_verify($password, $json[$userIndex]['password'])) {
         $returnMsg = $json[$userIndex]['id'];
      } else {
         $returnMsg = 'wrong_password';
      }
   } else {
      $returnMsg = 'invalid';
   }
   return $returnMsg;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $data = json_decode(file_get_contents("php://input"), TRUE);
   if (
      !isset($data['email']) || !isset($data['password']) ||
      empty(trim($data['email'])) || empty(trim($data['password']))
   ) {
      $res = res(0, 422, 'すべての項目を入力してください。');
   } else {
      $email = trim($data['email']);
      $password = trim($data['password']);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $res = res(0, 422, 'メールアドレスが不正な形式です。');
      } elseif (strlen($password) < 8) {
         $res = res(0, 422, 'パスワードが短すぎます、8文字以上入力してください。');
      } else {
         $usersJson = file_get_contents('../data/users.json');
         $usersJson = json_decode($usersJson, TRUE);
         $isMatch = checkUser($usersJson, $email, $password);

         if ($isMatch === 'invalid') {
            $res = res(0, 422, 'アカウントが存在しません。');
         } elseif ($isMatch === 'wrong_password') {
            $res = res(0, 422, 'パスワードが正しくありません。');
         } else {
            $headers = array('alg' => 'HS256', 'typ' => 'JWT');
            $payload = array('email' => $email, 'id' => $isMatch, 'exp' => (time() + 60 * 60));

            $jwt = generate_jwt($headers, $payload);

            $res = res(1, 200, 'ログインに成功しました。', array('token' => $jwt));
         }
      }
   }
} else {
   $res = res(0, 404, 'Page Not Found.');
}

echo json_encode($res);
