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
   Get Booking ID
========================= */

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    header("Location: admin_bookings.php");
    exit();

}

$booking_id = (int) $_GET["id"];

$error = "";


/* =========================
   Get Booking Information
========================= */

$sql = "SELECT
            b.booking_id,
            b.user_id,
            b.facility_id,
            b.booking_date,
            b.start_time,
            b.end_time,
            b.group_size,
            b.status,

            u.student_id,
            u.name,
            u.email,

            f.facility_name

        FROM bookings b

        LEFT JOIN users u
        ON b.user_id = u.user_id

        LEFT JOIN facilities f
        ON b.facility_id = f.facility_id

        WHERE b.booking_id = '$booking_id'";


$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) != 1) {

    header("Location: admin_bookings.php");
    exit();

}


$booking = mysqli_fetch_assoc($result);


/* =========================
   Update Booking Status
========================= */

if (isset($_POST["update_booking"])) {

    $status = mysqli_real_escape_string(
        $conn,
        $_POST["status"]
    );


    /* =========================
       Update Status
    ========================= */

    $update_sql = "UPDATE bookings
                   SET status = '$status'
                   WHERE booking_id = '$booking_id'";


    if (mysqli_query($conn, $update_sql)) {

        header("Location: admin_bookings.php");
        exit();

    } else {

        $error = "Database Error: "
               . mysqli_error($conn);

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
        Edit Booking - Campus Sports
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
                        class="nav-link"
                        href="admin_facilities.php">

                        Facilities

                    </a>

                </li>


                <!-- Bookings -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
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

                    Edit Booking

                </h1>

                <p class="text-muted">

                    Update the booking status.

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


            <!-- Booking Card -->

            <div class="card border-0 shadow-sm">


                <div class="card-body p-4">


                    <!-- Booking ID -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Booking ID

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($booking["booking_id"]); ?>"
                            readonly>

                    </div>


                    <!-- Student -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Student

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $booking["name"]
                                . " (" .
                                $booking["student_id"]
                                . ")"
                            ); ?>"
                            readonly>

                    </div>


                    <!-- Email -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Email

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($booking["email"]); ?>"
                            readonly>

                    </div>


                    <!-- Facility -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Facility

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($booking["facility_name"]); ?>"
                            readonly>

                    </div>


                    <!-- Booking Date -->

                    <div class="mb-3">

                        <label
                            class="form-label fw-semibold">

                            Booking Date

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($booking["booking_date"]); ?>"
                            readonly>

                    </div>


                    <!-- Time -->

                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label
                                class="form-label fw-semibold">

                                Start Time

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($booking["start_time"]); ?>"
                                readonly>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label
                                class="form-label fw-semibold">

                                End Time

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($booking["end_time"]); ?>"
                                readonly>

                        </div>


                    </div>


                    <!-- Number of Pax -->

                    <div class="mb-4">

                        <label
                            class="form-label fw-semibold">

                            Number of Pax

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            value="<?= htmlspecialchars($booking["group_size"]); ?>"
                            readonly>

                    </div>


                    <!-- Update Form -->

                    <form method="POST">


                        <!-- Status -->

                        <div class="mb-4">

                            <label
                                class="form-label fw-semibold">

                                Booking Status

                            </label>


                            <select
                                name="status"
                                class="form-select"
                                required>


                                <option
                                    value="Pending"
                                    <?php
                                    if (
                                        $booking["status"]
                                        == "Pending"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>

                                    Pending

                                </option>


                                <option
                                    value="Approved"
                                    <?php
                                    if (
                                        $booking["status"]
                                        == "Approved"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>

                                    Approved

                                </option>


                                <option
                                    value="Cancelled"
                                    <?php
                                    if (
                                        $booking["status"]
                                        == "Cancelled"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>>

                                    Cancelled

                                </option>


                            </select>

                        </div>


                        <!-- Buttons -->

                        <div class="d-flex gap-2">


                            <a
                                href="admin_bookings.php"
                                class="btn btn-secondary">

                                <i
                                    class="bi bi-arrow-left me-1">
                                </i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                name="update_booking"
                                class="btn btn-primary">

                                <i
                                    class="bi bi-check-circle me-1">
                                </i>

                                Update Status

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