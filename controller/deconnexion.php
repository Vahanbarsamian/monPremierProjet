<?php
session_start();
$_SESSION=[];
session_destroy();
$template = "index";
header('Location: /');




?>