<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

define('GUSER', 'noreply.checkchecker@gmail.com'); // GMail username
define('GPWD', 'wkvaJ?46msbYAbbT'); // GMail password


function sendConfirmation($token, $email) {
    $from = "no-reply@checkchecker.com";
    $to   = $email;
    $subject = "Confirm registration";
    $body = '
        Click <a href="http://3.89.196.174/checkchecker/newapi.php?operation=verifyToken&token='.$token.'">here</a> to confirm your account
    ';
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 1;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = GUSER;
    $mail->Password = GPWD;
    $mail->setFrom($from, "CheckChecker");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->addAddress($to);
    try {
        if (!$mail->send()) {
            echo $mail->ErrorInfo;
        }
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        echo $e->getMessage();
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
    if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
}

$operations = [
    'get' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['table'])) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $query['table'] . "`");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('table');
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
            $result = mysqli_query($dbc, "SELECT user_id, email FROM users WHERE `username` = '" . $query['username'] . "'");
            if ($result) {
                $arr = mysqli_fetch_array($result);
                $id = $arr['user_id'];
                $email = $arr['email'];
                $token = generateRandomString(20);

                sendConfirmation($token, $email);

                $result = mysqli_query($dbc, "INSERT INTO tokens (user_id, `value`) VALUES ($id, '$token')");
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('username', 'password', 'email');
    },
    'changeUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['username'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET username = '". $query['username'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['password'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET password = '". $query['password'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['avatar']) && isset($query['extension'])) {
                $newfilename = time() . "." . $query['extension'];
                $content = base64_decode($query['avatar']);
                $file = fopen("images/" . $newfilename, "wb");
                fwrite($file, $content);
                fclose($file);
                $result = mysqli_query($dbc,
                    "SELECT avatar FROM users WHERE user_id = " . $query['id']);
                if ($result) {
                    $filename = mysqli_fetch_array($result)['avatar'];
                    if ($filename != "unknown.png") unlink("images/" . $filename);
                    $result = mysqli_query($dbc,
                        "UPDATE users SET avatar = '". $newfilename ."' WHERE user_id = " . $query['id']);
                    if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
                } else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['avatar'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET avatar = '". $query['avatar'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['email'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET email = '". $query['email'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError('username', 'password', 'avatar');
        } else $rejectArgumentError('id');
    },
    'verifyUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['token'])) {
            $result = mysqli_query($dbc, "SELECT user_id FROM tokens WHERE `value` = '". $query['token'] ."'");
            if ($result) {
                if ($row = mysqli_fetch_array($result)) {
                    $id = $row['user_id'];
                    if (!mysqli_query($dbc, "UPDATE users SET approved = 1 WHERE user_id = ".$id)) $rejectMYSQLError(mysqli_error($dbc)); else $resolve(true);
                    echo "<script>window.close()</script>";
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
                $result = mysqli_query($dbc,
                    "UPDATE accounts SET account_name = '".$query['name']."' WHERE account_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['initial_amount'])) {
                $result = mysqli_query($dbc,
                    "UPDATE accounts SET initial_amount = ".$query['initial_amount']." WHERE account_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "initial_amount");
        } else $rejectArgumentError('id');
    },
    'createGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO groups (group_name) VALUES ('".$query['name']."')");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("name");
    },
    'changeGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE groups SET group_name = '".$query['name']."' WHERE group_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    "createRole" => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['permissions'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO roles (role_name, role_permissions) VALUES ('".$query['name']."', ".$query['permissions'].")");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("name", "permissions");
    },
    'changeRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE roles SET role_name = '".$query['name']."' WHERE role_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['permissions'])) {
                $result = mysqli_query($dbc,
                    "UPDATE roles SET role_permissions = ".$query['permissions']." WHERE role_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "permissions");
        } else $rejectArgumentError('id');
    },
    'addUserToGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
                $result = mysqli_query($dbc,
                    "INSERT INTO `groups-users_connections` (user_id, group_id) VALUES (".$query['user_id'].", ".$query['group_id'].")");
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('user_id', "group_id");
    },
    'addRoleToUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO `groups-users_connections` (user_id, group_id, role_id) VALUES (".$query['user_id'].", ".$query['group_id'].", ".$query['role_id'].")");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteRoleFromUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            $result = mysqli_query($dbc,
                "DELETE FROM `groups-users_connections` WHERE user_id = ".$query['user_id']." AND group_id = ".$query['group_id']." AND role_id = ".$query['role_id']);
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'changeUserRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            $result = mysqli_query($dbc,
                "UPDATE `groups-users_connections` SET role_id = '".$query['role_id']."' WHERE group_id = ".$query['group_id']." AND user_id = ".$query['user_id']);
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteUserFromGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            $result = mysqli_query($dbc,
                "DELETE FROM `groups-users_connections` WHERE user_id = ".$query['user_id']." AND group_id = ".$query['group_id']);
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
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
                $result = mysqli_query($dbc,
                    "UPDATE operations SET operation_name = '".$query['name']."' WHERE operation_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['value'])) {
                $result = mysqli_query($dbc,
                    "UPDATE operations SET `value` = ".$query['value']." WHERE operation_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "value");
        } else $rejectArgumentError('id');
    },
    'createCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO categories (category_name) VALUES ('".$query['name']."')");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("name");
    },
    'changeCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE categories SET category_name = '".$query['name']."' WHERE category_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'addCategoryToOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['category_id']) && isset($query['operation_id'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO `operations-categories_connections` (operation_id, category_id) VALUES (".$query['operation_id'].", ".$query['category_id'].")");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('category_id', 'operation_id');
    },
    'createCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['operation_id']) && isset($query['name'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO checks (check_name, operation_id) VALUES ('".$query['name']."', ".$query['operation_id'].")");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE checks SET check_name = '".$query['name']."' WHERE check_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'createProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['check_id']) && isset($query['name']) && isset($query['price']) && isset($query['amount'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO products (product_name, check_id, price, amount)
                        VALUES ('".$query['name']."', ".$query['check_id'].", ".$query['price'].", ".$query['amount'].")");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE products SET product_name = '".$query['name']."' WHERE product_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['price'])) {
                $result = mysqli_query($dbc,
                    "UPDATE products SET price = ".$query['price']." WHERE product_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['amount'])) {
                $result = mysqli_query($dbc,
                    "UPDATE products SET amount = ".$query['amount']." WHERE product_id = ".$query['id']);
                if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "price", "amount");
        } else $rejectArgumentError('id');
    },
    'deleteOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            $result = mysqli_query($dbc, "DELETE FROM operations WHERE operation_id = ".$query['id']);
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'deleteAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            $result = mysqli_query($dbc, "DELETE FROM accounts WHERE account_id = ".$query['id']);
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
];

$methods = [$_GET, $_POST];

foreach ($methods as $query) if (isset($query['operation'])) {
    $name = $query['operation'];
    if (isset($operations[$name])) $operations[$name](
        function ($result, $query) {
            $output = [
                'success' => "true",
                'result' => null
            ];
            if ($result === true) {
                $output['result'] = [];
                $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                @socket_connect($socket, '127.0.0.1', 8080);
                $msg = ["type" => "update"];
                if (isset($query['client_id'])) $msg['client_id'] = $query['client_id'];
                @socket_write($socket, json_encode($msg));
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
            echo json_encode($output);
        },
        function (...$errors) {
            echo '{"success":"false", "error":"Bad arguments: ' . implode(", ", $errors) . '"}';
        },
        function ($err) {
            echo '{"success":"false", "error":"MYSQL error: '. $err . '"}';
        },
        $dbc,
        $query
    ); else echo '{"success":"false", "error":"No such operation exists"}';
}


// dima pidor