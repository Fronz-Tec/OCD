<?php

unset($_SESSION['counter']);
session_destroy();

header("Location: login.php");
?>