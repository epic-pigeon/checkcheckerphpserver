<?php

if (isset($_GET["query"])) {
    $dbc = mysqli_connect("localhost", "root", "", isset($_GET["db"]) ? $_GET["db"] : "checkchecker");
    $result = mysqli_query($dbc, $_GET["query"]);
    if (!$result) die("[]");
    $resultArray = [];
    while ($row = mysqli_fetch_array($result)) {
        array_push($resultArray, $row);
    }
    die(json_encode($resultArray));
} else die("wtf");