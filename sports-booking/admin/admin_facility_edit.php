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
   Get Facility
========================= */

$sql = "SELECT *
        FROM facilities
        WHERE facility_id = '$facility_id'";

$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) != 1) {

    header("Location: admin_facilities.php");
    exit();

}

$facility = mysqli_fetch_assoc($result);

$error = "";


/* =========================
   Update Facility
========================= */

if (isset($_POST["update_facility"])) {

    $facility_name = mysqli_real_escape_string(
        $conn,
        trim($_POST["facility_name"])
    );

    $location = mysqli_real_escape_string(
        $conn,
        trim($_POST["location"])
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST["status"]
    );


    /* =========================
       Check Duplicate
    ========================= */

    $check_sql = "SELECT facility_id
                  FROM facilities
                  WHERE facility_name = '$facility_name'
                  AND location = '$location'
                  AND facility_id != '$facility_id'";

    $check_result = mysqli_query(
        $conn,
        $check_sql
    );


    if (mysqli_num_rows($check_result) > 0) {

        $error = "This facility already exists at this location.";

    } else {


        /* =========================
           Update
        ========================= */

        $update_sql = "UPDATE facilities
                       SET facility_name = '$facility_name',
                           location = '$location',
                           status = '$status'
                       WHERE facility_id = '$facility_id'";


        if (mysqli_query($conn, $update_sql)) {

            header("Location: admin_facilities.php");
            exit();

        } else {

            $error = "Database Error: "
                   . mysqli_error($conn);

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Edit Facility - Campus Sports
    </title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">


    <!-- Bootstrap Icons -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet">


    <!-- Custom CSS -->

    <link
        rel="stylesheet"
        href="../css/style.css">

</head>


<body class="bg-light">


<!-- =========================
     Navbar
========================= -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

    <div class="container">


        <a
            class="navbar-brand fw-bold"
            href="admin.php">

            Campus Sports Admin

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarNav">


            <ul class="navbar-nav ms-auto">


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin.php">

                        Dashboard

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin_users.php">

                        Users

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="admin_facilities.php">

                        Facilities

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin_bookings.php">

                        Bookings

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link text-danger"
                        href="../logout.php">

                        Logout

                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>


<!-- =========================
     Main Content
========================= -->

<main class="container py-5">


    <div class="row justify-content-center">

        <div class="col-lg-7">


            <!-- Header -->

            <div class="mb-4">

                <h1 class="fw-bold">

                    Edit Facility

                </h1>

                <p class="text-muted">

                    Update sports facility information.

                </p>

            </div>


            <!-- Error -->

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <i
                        class="bi bi-exclamation-circle me-2">
                    </i>

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <!-- Form Card -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <form method="POST">


                        <!-- Facility ID -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Facility ID

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($facility["facility_id"]); ?>"
                                readonly>


                            <small class="text-muted">

                                Facility ID cannot be changed.

                            </small>

                        </div>


                        <!-- Facility Name -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Facility Name

                            </label>


                            <input
                                type="text"
                                name="facility_name"
                                class="form-control"
                                value="<?= htmlspecialchars($facility["facility_name"]); ?>"
                                required>

                        </div>


                        <!-- Location -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Location

                            </label>


                            <input
                                type="text"
                                name="location"
                                class="form-control"
                                value="<?= htmlspecialchars($facility["location"]); ?>"
                                required>

                        </div>


                        <!-- Status -->

                        <div class="mb-4">

                            <label
                                class="form-label fw-semibold">

                                Status

                            </label>


                            <select
                                name="status"
                                class="form-select"
                                required>


                                <option
                                    value="Available"
                                    <?php
                                    if (
                                        $facility["status"]
                                        == "Available"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>

                                    Available

                                </option>


                                <option
                                    value="Unavailable"
                                    <?php
                                    if (
                                        $facility["status"]
                                        == "Unavailable"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>

                                    Unavailable

                                </option>


                            </select>

                        </div>


                        <!-- Buttons -->

                        <div class="d-flex gap-2">


                            <a
                                href="admin_facilities.php"
                                class="btn btn-secondary">

                                <i
                                    class="bi bi-arrow-left me-1">
                                </i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                name="update_facility"
                                class="btn btn-primary">

                                <i
                                    class="bi bi-check-circle me-1">
                                </i>

                                Update Facility

                            </button>


                        </div>


                    </form>

                </div>

            </div>


        </div>

    </div>

</main>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>