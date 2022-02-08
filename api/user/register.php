<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$res = '';

function res($success, $status, $message) {
   return array_merge([
      'success' => $success,
      'status' => $status,
      'message' => $message
   ]);
}

function uuid() {
   $pattern = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx';
   $chars = str_split($pattern);
   foreach ($chars as $i => $char) {
      if ($char === 'x') {
         $chars[$i] = dechex(random_int(0, 15));
      } elseif ($char === 'y') {
         $chars[$i] = dechex(random_int(8, 11));
      }
   }

   return implode('', $chars);
}

function registerUser($json, $email, $password) {
   $json[] = array(
      "id" => uuid(),
      "email" => $email,
      "password" => password_hash($password, PASSWORD_DEFAULT),
   );
   $json = json_encode($json);
   $result = file_put_contents('../data/users.json', $json);
   return $result;
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
         $usersJson = json_decode($usersJson);
         $userIndex = array_search($email, array_column($usersJson, 'email'));

         if ($userIndex !== false) {
            $res = res(0, 422, 'そのメールアドレスは既に登録されています。');
         } else {
            $result = registerUser($usersJson, $email, $password);
            if ($result) {
               $res = res(1, 201, 'アカウントの登録が完了しました。');
            } else {
               $res = res(0, 500, 'Server Error');
            }
         }
      }
   }
} else {
   $res = res(0, 404, 'Page Not Found.');
}

echo json_encode($res);
