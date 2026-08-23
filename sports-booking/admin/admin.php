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
   Get Dashboard Statistics
========================= */


/* Total Users */

$user_sql = "SELECT COUNT(*) AS total FROM users";

$user_result = mysqli_query($conn, $user_sql);

$user_data = mysqli_fetch_assoc($user_result);

$total_users = $user_data["total"];


/* Total Facilities */

$facility_sql = "SELECT COUNT(*) AS total FROM facilities";

$facility_result = mysqli_query($conn, $facility_sql);

$facility_data = mysqli_fetch_assoc($facility_result);

$total_facilities = $facility_data["total"];


/* Total Bookings */

$booking_sql = "SELECT COUNT(*) AS total FROM bookings";

$booking_result = mysqli_query($conn, $booking_sql);

$booking_data = mysqli_fetch_assoc($booking_result);

$total_bookings = $booking_data["total"];


/* Pending Bookings */

$pending_sql = "SELECT COUNT(*) AS total
                FROM bookings
                WHERE status = 'Pending'";

$pending_result = mysqli_query($conn, $pending_sql);

$pending_data = mysqli_fetch_assoc($pending_result);

$pending_bookings = $pending_data["total"];

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Admin Dashboard - Campus Sports
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
                        class="nav-link active"
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
                        class="nav-link"
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
     Dashboard
========================= -->

<main class="container py-5">


    <!-- Header -->

    <div class="mb-5">

        <h1 class="fw-bold">

            Admin Dashboard

        </h1>


        <p class="text-muted">

            Welcome back,
            <?= htmlspecialchars($_SESSION["name"]); ?>.

            Manage the Campus Sports Booking System here.

        </p>

    </div>


    <!-- =========================
         Statistics
    ========================= -->

    <div class="row g-4 mb-5">


        <!-- Total Users -->

        <div class="col-md-6 col-lg-3">

            <div
                class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p class="text-muted mb-1">

                                Total Users

                            </p>


                            <h2 class="fw-bold">

                                <?= $total_users; ?>

                            </h2>

                        </div>


                        <i
                            class="bi bi-people-fill
                                   fs-1 text-primary">

                        </i>


                    </div>

                </div>

            </div>

        </div>


        <!-- Total Facilities -->

        <div class="col-md-6 col-lg-3">

            <div
                class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p class="text-muted mb-1">

                                Facilities

                            </p>


                            <h2 class="fw-bold">

                                <?= $total_facilities; ?>

                            </h2>

                        </div>


                        <i
                            class="bi bi-building-fill
                                   fs-1 text-success">

                        </i>


                    </div>

                </div>

            </div>

        </div>


        <!-- Total Bookings -->

        <div class="col-md-6 col-lg-3">

            <div
                class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p class="text-muted mb-1">

                                Total Bookings

                            </p>


                            <h2 class="fw-bold">

                                <?= $total_bookings; ?>

                            </h2>

                        </div>


                        <i
                            class="bi bi-calendar-check-fill
                                   fs-1 text-warning">

                        </i>


                    </div>

                </div>

            </div>

        </div>


        <!-- Pending Bookings -->

        <div class="col-md-6 col-lg-3">

            <div
                class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div
                        class="d-flex justify-content-between">


                        <div>

                            <p class="text-muted mb-1">

                                Pending Bookings

                            </p>


                            <h2 class="fw-bold">

                                <?= $pending_bookings; ?>

                            </h2>

                        </div>


                        <i
                            class="bi bi-hourglass-split
                                   fs-1 text-danger">

                        </i>


                    </div>

                </div>

            </div>

        </div>


    </div>


    <!-- =========================
         Management
    ========================= -->

    <h3 class="fw-bold mb-4">

        Management

    </h3>


    <div class="row g-4">


        <!-- User Management -->

        <div class="col-md-4">

            <div
                class="card border-0 shadow-sm h-100">

                <div
                    class="card-body text-center p-4">


                    <i
                        class="bi bi-people-fill
                               display-4 text-primary">

                    </i>


                    <h4 class="fw-bold mt-3">

                        User Management

                    </h4>


                    <p class="text-muted">

                        View and manage student and admin accounts.

                    </p>


                    <a
                        href="admin_users.php"
                        class="btn btn-primary">

                        Manage Users

                    </a>


                </div>

            </div>

        </div>


        <!-- Facility Management -->

        <div class="col-md-4">

            <div
                class="card border-0 shadow-sm h-100">

                <div
                    class="card-body text-center p-4">


                    <i
                        class="bi bi-building-fill
                               display-4 text-success">

                    </i>


                    <h4 class="fw-bold mt-3">

                        Facility Management

                    </h4>


                    <p class="text-muted">

                        Add, edit and manage sports facilities.

                    </p>


                    <a
                        href="admin_facilities.php"
                        class="btn btn-success">

                        Manage Facilities

                    </a>


                </div>

            </div>

        </div>


        <!-- Booking Management -->

        <div class="col-md-4">

            <div
                class="card border-0 shadow-sm h-100">

                <div
                    class="card-body text-center p-4">


                    <i
                        class="bi bi-calendar-check-fill
                               display-4 text-warning">

                    </i>


                    <h4 class="fw-bold mt-3">

                        Booking Management

                    </h4>


                    <p class="text-muted">

                        Review and manage student bookings.

                    </p>


                    <a
                        href="admin_bookings.php"
                        class="btn btn-warning">

                        Manage Bookings

                    </a>


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