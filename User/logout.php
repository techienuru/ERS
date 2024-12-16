<?php
session_start();
require_once "../includes/connect.php";

if (isset($_SESSION["user_id"])) {
    session_unset();
    session_destroy();
    header("location:../auth-signin.php");
}
