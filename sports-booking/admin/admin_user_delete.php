<?php

session_start();

include "../includes/db.php";


/* =========================
   Check Login
========================= */

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit();

}


/* =========================
   Check Admin Role
========================= */

if ($_SESSION["role"] !== "admin") {

    header("Location: ../index.php");
    exit();

}


/* =========================
   Get User ID
========================= */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: admin_users.php");
    exit();

}

$delete_user_id = (int) $_GET["id"];


/* =========================
   Prevent Admin Deleting
   Their Own Account
========================= */

if ($delete_user_id == $_SESSION["user_id"]) {

    header("Location: admin_users.php?error=self_delete");
    exit();

}


/* =========================
   Check User Exists
========================= */

$sql = "SELECT user_id
        FROM users
        WHERE user_id = '$delete_user_id'";

$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) != 1) {

    header("Location: admin_users.php?error=not_found");
    exit();

}


/* =========================
   Delete User
========================= */

$delete_sql = "DELETE FROM users
               WHERE user_id = '$delete_user_id'";


if (mysqli_query($conn, $delete_sql)) {

    header("Location: admin_users.php?success=deleted");
    exit();

} else {

    header(
        "Location: admin_users.php?error=delete_failed"
    );

    exit();

}

?>