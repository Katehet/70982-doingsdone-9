<?php

unset($_SESSION["user"]);
$_SESSION = [];

header("Location: /guest.php");
?>
