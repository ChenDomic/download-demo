<?php

require "d:/web/phplearn/download/src/db.php";

$instance = Db::getInstance();
$id = 1;
$sql = "select * from dload_file";


var_dump($instance->query($sql,PDO::FETCH_ASSOC));
var_dump($instance->query($sql,PDO::FETCH_NUM));
var_dump($instance->query($sql,PDO::FETCH_BOTH));

var_dump($instance->execute($sql));
