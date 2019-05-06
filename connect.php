<?php

require_once 'user.php';

$conn = new Mysqli(MY_SERVER, MY_USERNAME, MY_PASSWORD,MY_DBNAME);

if($conn ->connect_error){
    die("connection failed:".$conn -> connect_error);
}
?>