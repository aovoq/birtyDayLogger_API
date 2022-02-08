# birtyDayLogger_API

## 概要

BirtyDayLogger のための API です。
以下ドキュメントとなります。

### 提供機能

-  ユーザー登録
-  ユーザーサインイン
-  ユーザー削除
-  ユーザーによるログの追加
-  ユーザーによるログの更新
-  ユーザーによるログの削除
-  ユーザーによるログの一覧

### 認証・認可方式???(この辺曖昧 勉強不足)

JWT による Authorization

## 要求

要求のパラメーターはそれぞれ

-  パス
-  ヘッダー
-  ボディ

のうちの決められた場所に保存してください。本文のパラメーターは JSON 形式で受け取ります。

## 応答

成功時には `2xx` を、エラー時には `4xx` または `5xx` (`x`は数字) をステータスコードとして返します。

## User

| FUNCTION     | METHOD | URI                    |
| ------------ | ------ | ---------------------- |
| UserRegister | POST   | /api/user/register.php |
| UserSignin   | POST   | /api/user/signin.php   |
| UserDelete   | DELETE | /api/user/delete.php   |

### UserRegister

#### パラメーター

| 場所   | 随意性 | 名称       | 内容           |
| ------ | ------ | ---------- | -------------- |
| ボディ | 必須   | `email`    | `` `EMAIL`  `` |
| ボディ | 必須   | `password` | `String < 8 `  |

### UserSignin

#### パラメーター

| 場所   | 随意性 | 名称       | 内容           |
| ------ | ------ | ---------- | -------------- |
| ボディ | 必須   | `email`    | `` `EMAIL`  `` |
| ボディ | 必須   | `password` | `String < 8 `  |

### UserDelete

### パラメーター

| 場所     | 随意性 | 名称            | 内容                |
| -------- | ------ | --------------- | ------------------- |
| ヘッダー | 必須   | `Authorization` | `` Bearer `JWT`  `` |
| ボディ   | 必須   | `Password`      | `String < 8`        |

## Data

| FUNCTION   | METHOD | URI                      |
| ---------- | ------ | ------------------------ |
| DataAdd    | POST   | /api/birthday/add.php    |
| DataUpdate | POST   | /api/birthday/update.php |
| DataRead   | GET    | /api/birthday/read.php   |
| DataDelete | DELETE | /api/birthday/delete.php |

### UserAdd

#### パラメーター

| 場所     | 随意性 | 名称            | 内容                |
| -------- | ------ | --------------- | ------------------- |
| ヘッダー | 必須   | `Authorization` | `` Bearer `JWT`  `` |
| ボディ   | 必須   | `name`          | `String`            |
| ボディ   | 必須   | `date`          | `` `int` - `int` `` |
| ボディ   | 任意   | `note`          | `String`            |

### UserUpdate

#### パラメーター

| 場所     | 随意性 | 名称            | 内容                |
| -------- | ------ | --------------- | ------------------- |
| ヘッダー | 必須   | `Authorization` | `` Bearer `JWT`  `` |
| ボディ   | 必須   | `id`            | `` `DataID` ``      |
| ボディ   | 必須   | `name`          | `String`            |
| ボディ   | 必須   | `date`          | `` `int` - `int` `` |
| ボディ   | 任意   | `note`          | `String`            |

### UserRead

#### パラメーター

| 場所     | 随意性 | 名称            | 内容                |
| -------- | ------ | --------------- | ------------------- |
| ヘッダー | 必須   | `Authorization` | `` Bearer `JWT`  `` |

### UserDelete

#### パラメーター

| 場所     | 随意性 | 名称            | 内容                |
| -------- | ------ | --------------- | ------------------- |
| ヘッダー | 必須   | `Authorization` | `` Bearer `JWT`  `` |
| ボディ   | 必須   | `id`            | `` `DataID` ``      |
