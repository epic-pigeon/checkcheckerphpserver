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

$operations = [
    'get' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['table'])) {
            $result = mysqli_query($dbc, "SELECT * FROM " . $query['table']);
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('table');
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
            $result = mysqli_query($dbc, "SELECT user_id FROM users WHERE `name` = '" . $query['username']) . "''";
            if ($result) {
                $id = mysqli_fetch_array($result);
                $token = generateRandomString(20);
                mail($query['email'], "CheckChecker", "http://3.89.196.174/checkchecker/newapi.php?operation=verifyUser&token=$token");
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
            $result = mysqli_query($dbc, "SELECT user_id FROM tokens WHERE `value` = '". $query['token']) ."'";
            if ($result) {
                if ($row = mysqli_fetch_array($result)) {
                    $id = $row['user_id'];
                    if (!mysqli_query($dbc, "UPDATE users SET approved = 1 WHERE user_id = ".$id)) $rejectMYSQLError(mysqli_error($dbc)); else $resolve(true);
                } else $rejectArgumentError('token');
            } else $rejectMYSQLError(mysqli_error($dbc));
        }
    }
];

$methods = [$_GET, $_POST];

foreach ($methods as $query) if (isset($query['operation'])) {
    $name = $query['operation'];
    if (isset($operations[$name])) $operations[$name](
        function ($result) {
            if ($result === true) {
                echo "[]";
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
