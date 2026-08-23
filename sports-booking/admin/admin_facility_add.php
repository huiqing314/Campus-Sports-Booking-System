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


$error = "";


/* =========================
   Add Facility
========================= */

if (isset($_POST["add_facility"])) {

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
       Check Duplicate Facility
    ========================= */

    $check_sql = "SELECT facility_id
                  FROM facilities
                  WHERE facility_name = '$facility_name'
                  AND location = '$location'";

    $check_result = mysqli_query(
        $conn,
        $check_sql
    );


    if (mysqli_num_rows($check_result) > 0) {

        $error = "This facility already exists at this location.";

    } else {


        /* =========================
           Insert Facility
        ========================= */

        $sql = "INSERT INTO facilities
                (
                    facility_name,
                    location,
                    status
                )
                VALUES
                (
                    '$facility_name',
                    '$location',
                    '$status'
                )";


        if (mysqli_query($conn, $sql)) {

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
        Add Facility - Campus Sports
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


                <!-- Dashboard -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin.php">

                        Dashboard

                    </a>

                </li>


                <!-- Users -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin_users.php">

                        Users

                    </a>

                </li>


                <!-- Facilities -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="admin_facilities.php">

                        Facilities

                    </a>

                </li>


                <!-- Bookings -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="admin_bookings.php">

                        Bookings

                    </a>

                </li>


                <!-- Logout -->

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

                    Add Facility

                </h1>

                <p class="text-muted">

                    Add a new sports facility to the system.

                </p>

            </div>


            <!-- Error Message -->

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <i
                        class="bi bi-exclamation-circle me-2">
                    </i>

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <!-- Form -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <form method="POST">


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
                                placeholder="e.g. Badminton Court"
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
                                placeholder="e.g. Sports Complex"
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
                                    selected>

                                    Available

                                </option>


                                <option
                                    value="Unavailable">

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
                                name="add_facility"
                                class="btn btn-success">

                                <i
                                    class="bi bi-plus-circle me-1">
                                </i>

                                Add Facility

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