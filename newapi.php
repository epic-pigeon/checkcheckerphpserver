<?php

$dbc = mysqli_connect("localhost", "checkchecker", "PrObaHeD&tH@fet@lcr3", "checkchecker") or die("failed to connect to db");

$operations = [
    'get' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['table'])) {
            $result = mysqli_query($dbc, "SELECT * FROM " . $query['table']);
            if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('table');
    },
    'createUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['username']) && isset($query['password'])) {
            if (isset($query['avatar'])) {
                $result = mysqli_query($dbc,
                    "INSERT INTO users (username, password, avatar) VALUES ('". $query["username"] ."', '". $query["password"] ."', '". $query["avatar"] ."')");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            } else {
                $result = mysqli_query($dbc,
                    "INSERT INTO users (username, password) VALUES ('". $query["username"] ."', '". $query["password"] ."')");
                if ($result) $resolve($result); else $rejectMYSQLError(mysqli_error($dbc));
            }
        } else $rejectArgumentError('username', 'password');
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
