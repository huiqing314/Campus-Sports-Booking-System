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
   Get Facility ID
========================= */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: admin_facilities.php");
    exit();

}

$facility_id = (int) $_GET["id"];


/* =========================
   Check Facility Exists
========================= */

$sql = "SELECT facility_id
        FROM facilities
        WHERE facility_id = '$facility_id'";

$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) != 1) {

    header("Location: admin_facilities.php?error=not_found");
    exit();

}


/* =========================
   Delete Facility
========================= */

$delete_sql = "DELETE FROM facilities
               WHERE facility_id = '$facility_id'";


if (mysqli_query($conn, $delete_sql)) {

    header("Location: admin_facilities.php?success=deleted");
    exit();

} else {

    header("Location: admin_facilities.php?error=delete_failed");
    exit();

}

?>