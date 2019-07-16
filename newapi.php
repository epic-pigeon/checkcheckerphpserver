<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

define('GUSER', 'no.reply.checkchecker0@gmail.com'); // GMail username
define('GPWD', 'F429D420086F70A3B72B10BFF0446D87DEA2A385AD9EA49A135E547414D91CC2'); // GMail password


function sendConfirmation($token, $email, $username) {
    $from = "no-reply@checkchecker.com";
    $to   = $email;
    $subject = "Confirm registration";
    $body = file_get_contents("mail.html");
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->isHTML(true);
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = GUSER;
    $mail->Password = GPWD;
    $mail->SetFrom($from, "CheckChecker");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    $mail->AddEmbeddedImage('download.jpg','dog','download.jpg');
    $mail->AddEmbeddedImage('karkar (1).jpg','logo','karkar (1).jpg');
    if (!$mail->send()) {
        throw new \Exception($mail->ErrorInfo . "(" . $email . ")");
    }
}


$dbc = mysqli_connect("localhost", "checkchecker", "JJWMdF6riGuHDoVr", "checkchecker") or die("failed to connect to db");
mysqli_set_charset($dbc, 'utf8');

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$operations = [];

function executeInsert($dbc, $table, $args, $resolve, $rejectMYSQLError) {
    $query = "INSERT INTO `$table` (";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "`".$k."`");
    }
    $query .= implode(", ", $array);
    $query .= ") VALUES (";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "'".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(", ", $array);
    $query .= ")";
    $result = mysqli_query($dbc, $query);
    if ($result) $resolve($result, null, null, mysqli_insert_id($dbc)); else $rejectMYSQLError(mysqli_error($dbc));
}

function executeDelete($dbc, $table, $args, $resolve, $rejectMYSQLError) {
    $query = "DELETE FROM `$table` WHERE ";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "`".$k."` = '".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(" AND ", $array);
    $result = mysqli_query($dbc, $query);
    if ($result) $resolve($result, null); else $rejectMYSQLError(mysqli_error($dbc));
}

function executeUpdate($dbc, $table, $args, $conditions, $resolve, $rejectMYSQLError, $info = null) {
    $query = "UPDATE `$table` SET ";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "`".$k."` = '".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(", ", $array);
    $query .= " WHERE ";
    $array = [];
    foreach ($conditions as $k => $v) {
        array_push($array, "`".$k."` = '".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(" AND ", $array);
    $result = mysqli_query($dbc, $query);
    if ($result) $resolve($result, null, $info); else $rejectMYSQLError(mysqli_error($dbc));
}

$operations = [
    'get' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['table'])) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $query['table'] . "`");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('table');
    },
    'login' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['username']) && isset($query['password'])) {
            $result = mysqli_query($dbc, "SELECT * FROM users WHERE username = "
                .mysqli_escape_string($dbc, $query['username'])
                ." AND password = "
                .mysqli_escape_string($dbc, $query['password']));
            if (mysqli_num_rows($result) == 1) {
                $id = mysqli_fetch_array($result, MYSQLI_ASSOC)["id"];
                $resolve($id);
            } else throw new \Exception("Bad username or password");
        } else $rejectArgumentError("username", "password");
    },
    'getAll' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        $results = [];
        foreach (
            ['labels', 'groups', 'users', 'accounts', 'categories', 'checks', 'products', 'groups-users_connections', 'operations', 'roles', 'operations-categories_connections', 'currencies']
            as $value) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $value . "`");
            if ($result) $results[$value] = $result; else $rejectMYSQLError(mysqli_error($dbc));
        }
        $resolve($results, $query);
    },
    'createUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['username']) && isset($query['password']) && isset($query['email'])) {
            $args = [
                "username" => $query['username'],
                "password" => $query['password'],
                "email" => $query['email'],
            ];
            if (isset($query['avatar'])) {
                $args['avatar'] = $query['avatar'];
            }
            if (isset($query['average_income'])) {
                $args['average_income'] = $query['average_income'];
            }
            executeInsert($dbc, "users", $args, function(){}, $rejectMYSQLError);
            $result = mysqli_query($dbc, "SELECT user_id, email FROM users WHERE `username` = '". mysqli_real_escape_string($dbc, $query['username']) . "'");
            if ($result) {
                $arr = mysqli_fetch_array($result);
                $id = $arr['user_id'];
                $email = $arr['email'];
                $token = generateRandomString(20);

                sendConfirmation($token, $email, $query['username']);

                mysqli_query($dbc, "INSERT INTO tokens (user_id, `value`) VALUES ($id, '$token')");
                if ($result) $resolve($result, $query, null, mysqli_insert_id($dbc)); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('username', 'password', 'email');
    },
    'changeUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['username'])) {
                executeUpdate($dbc, 'users', [
                    'username' => $query['username']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['password'])) {
                executeUpdate($dbc, 'users', [
                    'password' => $query['password']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['avatar']) && isset($query['extension'])) {
                $newfilename = time() . "." . $query['extension'];
                $content = base64_decode($query['avatar']);
                $file = fopen("images/" . $newfilename, "wb");
                fwrite($file, $content);
                fclose($file);
                $result = mysqli_query($dbc,
                    "SELECT avatar FROM users WHERE user_id = " . mysqli_real_escape_string($dbc, $query['id']));
                if ($result) {
                    $filename = mysqli_fetch_array($result)['avatar'];
                    if ($filename != "unknown.png") unlink("images/" . $filename);
                    executeUpdate($dbc, 'users', [
                        'avatar' => $newfilename
                    ], [
                        'user_id' => $query['id']
                    ], $resolve, $rejectMYSQLError, $query['avatar']);
                } else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['avatar'])) {
                executeUpdate($dbc, 'users', [
                    'avatar' => $query['avatar']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['email'])) {
                executeUpdate($dbc, 'users', [
                    'email' => $query['email']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError('username', 'password', 'avatar');
        } else $rejectArgumentError('id');
    },
    'verifyUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['token'])) {
            $result = mysqli_query($dbc, "SELECT user_id FROM tokens WHERE `value` = '". $query['token'] ."'");
            if ($result) {
                if ($row = mysqli_fetch_array($result)) {
                    $id = $row['user_id'];
                    if (!(mysqli_query($dbc, "UPDATE users SET approved = 1 WHERE user_id = ".$id) && mysqli_query($dbc, "DELETE FROM tokens WHERE user_id = ".$id))) $rejectMYSQLError(mysqli_error($dbc)); else $resolve(true, $query);
                    echo "<script>window.history.back();window.close()</script>";
                } else $rejectArgumentError('token');
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('token');
    },
    'createAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['name']) && isset($query['initial_amount'])) {
            $args = [
                "user_id" => $query["user_id"],
                "account_name" => $query["name"],
                "initial_amount" => $query["initial_amount"]
            ];
            if (isset($query['currency_id'])) $args['currency_id'] = $query['currency_id'];
            executeInsert($dbc, 'accounts', $args, $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'accounts', [
                    'account_name' => $query['name']
                ], [
                    'account_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['initial_amount'])) {
                executeUpdate($dbc, 'accounts', [
                    'initial_amount' => $query['initial_amount']
                ], [
                    'account_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name", "initial_amount");
        } else $rejectArgumentError('id');
    },
    'createGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name'])) {
            executeInsert($dbc, 'groups', [
                'group_name' => $query['name']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("name");
    },
    'changeGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'groups', [
                    'group_name' => $query['name']
                ], [
                    'group_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    "createRole" => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['permissions'])) {
            executeInsert($dbc, 'roles', [
                'role_name' => $query['name'],
                'role_permissions' => $query['permissions']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("name", "permissions");
    },
    'changeRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'roles', [
                    'role_name' => $query['name']
                ], [
                    'role_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['permissions'])) {
                executeUpdate($dbc, 'roles', [
                    'role_permissions' => $query['permissions']
                ], [
                    'role_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name", "permissions");
        } else $rejectArgumentError('id');
    },
    'addUserToGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            executeInsert($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id");
    },
    'addRoleToUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            executeInsert($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id'],
                'role_id' => $query['role_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteRoleFromUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            executeDelete($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id'],
                'role_id' => $query['role_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'changeUserRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            executeUpdate($dbc, 'groups-users_connections', [
                'role_id' => $query['role_id']
            ], [
                'group_id' => $query['group_id'],
                'user_id' => $query['user_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteUserFromGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            executeDelete($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', 'group_id');
    },
    'createOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['account_id'])) {
            $args = [
                "operation_name" => $query["name"],
                "account_id" => $query['account_id']
            ];
            if (isset($query['value'])) $args['value'] = $query['value'];
            executeInsert($dbc, "operations", $args, $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('name', 'account_id');
    },
    'changeOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'operations', [
                    'operation_name' => $query['name']
                ], [
                    'operation_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['value'])) {
                executeUpdate($dbc, 'operations', [
                    'value' => $query['value']
                ], [
                    'operation_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['timestamp'])) {
                $result = mysqli_query($dbc, "UPDATE operations SET added_timestamp = FROM_UNIXTIME(".$query['timestamp'].") WHERE operation_id = ".$query['id']);
                if ($result) $resolve($result, $query, null); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "value");
        } else $rejectArgumentError('id');
    },
    'createCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['user_id'])) {
            $args = [
                'category_name' => $query['name'],
                'user_id' => $query['user_id']
            ];
            if (isset($query['label_id'])) $args['label_id'] = $query['label_id'];
            executeInsert($dbc, 'categories', $args, $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("name");
    },
    'changeCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'categories', [
                    'category_name' => $query['name']
                ], [
                    'category_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['label_id'])) {
                executeUpdate($dbc, 'categories', [
                    'label_id' => $query['label_id']
                ], [
                    'category_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'addCategoryToOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['category_id']) && isset($query['operation_id'])) {
            executeInsert($dbc, 'operations-categories_connections', [
                'operation_id' => $query['operation_id'],
                'category_id' => $query['category_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('category_id', 'operation_id');
    },
    'createCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['operation_id']) && isset($query['name'])) {
            executeInsert($dbc, 'checks', [
                'check_name' => $query['name'],
                'operation_id' => $query['operation_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'checks', [
                    'check_name' => $query['name']
                ], [
                    'check_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'createProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['check_id']) && isset($query['name']) && isset($query['price']) && isset($query['amount'])) {
            executeInsert($dbc, 'products', [
                'product_name' => $query['name'],
                'check_id' => $query['check_id'],
                'price' => $query['price'],
                'amount' => $query['amount']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'products', [
                    'product_name' => $query['name']
                ], [
                    'product_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['price'])) {
                executeUpdate($dbc, 'products', [
                    'price' => $query['price']
                ], [
                    'product_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['amount'])) {
                executeUpdate($dbc, 'products', [
                    'amount' => $query['amount']
                ], [
                    'product_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name", "price", "amount");
        } else $rejectArgumentError('id');
    },
    'deleteOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            executeDelete($dbc, 'operations', [
                'operation_id' => $query['id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("id");
    },
    'deleteCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            executeDelete($dbc, 'categories', [
                'category_id' => $query['id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("id");
    },
    'deleteAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            executeDelete($dbc, 'accounts', [
                'account_id' => $query['id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'createLabel' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['type']) && isset($query['r']) && isset($query['g']) && isset($query['b']) && isset($query['alpha'])) {
            executeInsert($dbc, 'labels', [
                'type' => $query['type'],
                'r' => $query['r'],
                'g' => $query['g'],
                'b' => $query['b'],
                'alpha' => $query['alpha'],
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("type", 'r', 'g', 'b', 'alpha');
    },
    'changeLabel' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['type'])) {
                executeUpdate($dbc, 'labels', [
                    'type' => $query['type']
                ], [
                    'label_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['r']) && isset($query['g']) && isset($query['b']) && isset($query['alpha'])) {
                executeUpdate($dbc, 'labels', [
                    'r' => $query['r'],
                    'g' => $query['g'],
                    'b' => $query['b'],
                    'alpha' => $query['alpha']
                ], [
                    'label_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("type", 'r', 'g', 'b', 'alpha');
        } else $rejectArgumentError('id');
    }
];

$methods = [$_GET, $_POST];

foreach ($methods as $query) if (isset($query['operation'])) {
    $name = $query['operation'];
    $access = ($name == "login") || ($name == "createUser");
    if ((isset($query['login_token']) && intval($query['login_token']) != 0) || $access) {
        if (isset($operations[$name])) {
            if (!$access) {
                $token = intval($query['login_token']);
                $result = mysqli_query($dbc, "SELECT * FROM login_tokens WHERE id = " . $token);
            }
            if ($access || (mysqli_num_rows($result) == 1)) {
                try {
                    $operations[$name](
                        function ($result, $query = null, $info = null, $lastID = null) {
                            $output = [
                                'success' => "true",
                                'result' => null
                            ];
                            if ($result === true) {
                                $output['result'] = [];
                                /*$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                                @socket_connect($socket, '127.0.0.1', 8080);
                                $msg = ["type" => "update"];
                                if (($query != null) && isset($query['client_id'])) $msg['client_id'] = $query['client_id'];
                                if ($lastID != null) $msg['last_id'] = $lastID;
                                @socket_write($socket, json_encode($msg));*/
                            } else if (gettype($result) === "integer") {
                                $output['result'] = [$result];
                            } else if (gettype($result) == "array") {
                                foreach ($result as $key => $value) {
                                    $toJSON = [];
                                    while ($row = mysqli_fetch_array($value)) {
                                        array_push($toJSON, $row);
                                    }
                                    $output['result'][$key] = $toJSON;
                                }
                            } else {
                                $toJSON = [];
                                while ($row = mysqli_fetch_array($result)) {

                                    array_push($toJSON, $row);
                                }
                                $output['result'] = $toJSON;
                            }
                            if ($info != null) {
                                $output['info'] = $info;
                            }
                            if ($lastID != null) {
                                $output['last_id'] = $lastID;
                            }
                            echo json_encode($output);
                        },
                        function (...$errors) {
                            echo '{"success":"false", "error":"Bad arguments: ' . implode(", ", $errors) . '"}';
                        },
                        function ($err) {
                            echo '{"success":"false", "error":"MYSQL error: ' . $err . '"}';
                        },
                        $dbc,
                        $query
                    );
                } catch (\Exception $e) {
                    echo '{"success":"false", "error":"' . $e->getMessage() . '"}';
                }
            }
        } else echo '{"success":"false", "error":"No such operation exists"}';
    } else echo '{"success":"false", "error":"No login token supplied"}';
}



// dima pidor