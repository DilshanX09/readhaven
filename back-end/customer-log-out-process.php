<?php
session_start();
if ($_SESSION["user"]) {
    $_SESSION["user"] = null;
    session_destroy();

    setcookie("email", "", time() - 3600, "/");
    setcookie("password", "", time() - 3600, "/");

    echo ("success");
}
