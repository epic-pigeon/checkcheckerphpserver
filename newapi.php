<?php

$dbc = mysqli_connect("localhost", "checkchecker", "JJWMdF6riGuHDoVr", "checkchecker") or die("failed to connect to db");

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

$operations = [
    'get' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['table'])) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $query['table'] . "`");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('table');
    },
    'getAll' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        $results = [];
        foreach (
            ['groups', 'users', 'accounts', 'categories', 'checks', 'products', 'groups-users_connections', 'operations', 'roles', 'operations-categories_connections']
            as $value) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $value . "`");
            if ($result) $results[$value] = $result; else $rejectMYSQLError(mysqli_error($dbc));
        }
        $resolve($results);
    },
    'createUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['username']) && isset($query['password']) && isset($query['email'])) {
            if (isset($query['avatar'])) {
                $result = mysqli_query($dbc,
                    "INSERT INTO users (username, password, avatar, email) VALUES ('". $query["username"] ."', '". $query["password"] ."', '". $query["avatar"] ."', '". $query['email'] ."')");
                //if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else {
                $result = mysqli_query($dbc,
                    "INSERT INTO users (username, password, email) VALUES ('". $query["username"] ."', '". $query["password"] ."', '". $query['email'] ."')");
                //if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            }
            $result = mysqli_query($dbc, "SELECT user_id FROM users WHERE `username` = '" . $query['username'] . "'");
            if ($result) {
                $id = mysqli_fetch_array($result)['user_id'];
                $token = generateRandomString(20);
                //mail($query['email'], "CheckChecker", "http://3.89.196.174/checkchecker/newapi.php?operation=verifyUser&token=$token");
                echo "http://3.89.196.174/checkchecker/newapi.php?operation=verifyUser&token=$token\n";
                echo "INSERT INTO tokens (user_id, `value`) VALUES (".$id.", '$token')";
                $result = mysqli_query($dbc, "INSERT INTO tokens (user_id, `value`) VALUES ($id, '$token')");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('username', 'password', 'email');
    },
    'changeUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['username'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET username = '". $query['username'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['password'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET password = '". $query['password'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
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
                    if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
                } else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['avatar'])) {
                $result = mysqli_query($dbc,
                    "UPDATE users SET avatar = '". $query['avatar'] ."' WHERE user_id = " . $query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
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
                } else $rejectArgumentError('token');
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('token');
    },
    'createAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['name']) && isset($query['initial_amount'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO accounts (account_name, user_id, initial_amount) VALUES ('".$query['name']."', ".$query['user_id'].", ".$query['initial_amount'].")");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE accounts SET account_name = '".$query['name']."' WHERE account_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['initial_amount'])) {
                $result = mysqli_query($dbc,
                    "UPDATE accounts SET initial_amount = ".$query['initial_amount']." WHERE account_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "initial_amount");
        } else $rejectArgumentError('id');
    },
    'createGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO groups (group_name) VALUES ('".$query['name']."'");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("name");
    },
    'changeGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE groups SET group_name = '".$query['name']."' WHERE group_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    "createRole" => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['permissions'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO roles (role_name, role_permissions) VALUES ('".$query['name']."', ".$query['permissions'].")");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("name", "permissions");
    },
    'changeRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE roles SET role_name = '".$query['name']."' WHERE role_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['permissions'])) {
                $result = mysqli_query($dbc,
                    "UPDATE roles SET role_permissions = ".$query['permissions']." WHERE role_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "permissions");
        } else $rejectArgumentError('id');
    },
    'addUserToGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            if (isset($query['role_id'])) {
                $result = mysqli_query($dbc,
                    "INSERT INTO `groups-users_connections` (user_id, group_id, role_id) VALUES (".$query['user_id'].", ".$query['group_id'].", ".$query['role_id'].")");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else {
                $result = mysqli_query($dbc,
                    "INSERT INTO `groups-users_connections` (user_id, group_id) VALUES (".$query['user_id'].", ".$query['group_id'].")");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            }
        } else $rejectArgumentError('user_id', "group_id");
    },
    'changeUserRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            $result = mysqli_query($dbc,
                "UPDATE `groups-users_connections` SET role_id = '".$query['role_id']."' WHERE group_id = ".$query['group_id']." AND user_id = ".$query['user_id']);
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteUserFromGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            $result = mysqli_query($dbc,
                "DELETE FROM `groups-users_connections` WHERE user_id = ".$query['user_id']." AND group_id = ".$query['group_id']);
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('user_id', 'group_id');
    },
    'createOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['account_id'])) {
            if (isset($query['value'])) {
                $result = mysqli_query($dbc,
                    "INSERT INTO operations (operation_name, account_id, `value`) VALUES ('".$query['name']."', ".$query['account_id'].", ".$query['value'].")");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else {
                $result = mysqli_query($dbc,
                    "INSERT INTO operations (operation_name, account_id) VALUES ('".$query['name']."', ".$query['account_id'].")");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            }
        } else $rejectArgumentError('name', 'account_id');
    },
    'changeOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE operations SET operation_name = '".$query['name']."' WHERE operation_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['value'])) {
                $result = mysqli_query($dbc,
                    "UPDATE operations SET `value` = ".$query['value']." WHERE operation_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "value");
        } else $rejectArgumentError('id');
    },
    'createCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO categories (category_name) VALUES ('".$query['name']."'");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("name");
    },
    'changeCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE categories SET category_name = '".$query['name']."' WHERE category_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'addCategoryToOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['category_id']) && isset($query['operation_id'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO `operations-categories_connections` (operation_id, category_name) VALUES (".$query['operation_id'].", ".$query['category_id'].")");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('category_id', 'operation_id');
    },
    'createCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['operation_id']) && isset($query['name'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO checks (check_name, operation_id) VALUES ('".$query['name']."', ".$query['operation_id'].")");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE checks SET check_name = '".$query['name']."' WHERE check_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'createProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['check_id']) && isset($query['name']) && isset($query['price']) && isset($query['amount'])) {
            $result = mysqli_query($dbc,
                "INSERT INTO products (product_name, check_id, price, amount)
                        VALUES ('".$query['name']."', ".$query['check_id'].", ".$query['price'].", ".$query['amount'].")");
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                $result = mysqli_query($dbc,
                    "UPDATE products SET product_name = '".$query['name']."' WHERE product_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['price'])) {
                $result = mysqli_query($dbc,
                    "UPDATE products SET price = ".$query['price']." WHERE product_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['amount'])) {
                $result = mysqli_query($dbc,
                    "UPDATE products SET amount = ".$query['amount']." WHERE product_id = ".$query['id']);
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "price", "amount");
        } else $rejectArgumentError('id');
    },
];

$methods = [$_GET, $_POST];

foreach ($methods as $query) if (isset($query['operation'])) {
    $name = $query['operation'];
    if (isset($operations[$name])) $operations[$name](
        function ($result) {
            if ($result === true) {
                echo "[]";
            } else if (gettype($result) == "array") {
                foreach ($result as $key => $value) {
                    $toJSON = [];
                    while ($row = mysqli_fetch_array($value)) {
                        array_push($toJSON, $row);
                    }
                    $result[$key] = $toJSON;
                }
                echo json_encode($result);
            } else {
                $toJSON = [];
                while ($row = mysqli_fetch_array($result)) {

                    array_push($toJSON, $row);
                }
                echo json_encode($toJSON);
            }
        },
        function (...$errors) {
            echo 'Bad arguments: ' . implode(", ", $errors);
        },
        function ($err) {
            echo 'MYSQL error: '. $err;
        },
        $dbc,
        $query
    ); else echo "No such operation exists";
}


// dima pidor