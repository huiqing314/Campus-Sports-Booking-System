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
   Get All Bookings
========================= */

$sql = "SELECT
            b.booking_id,
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

        ORDER BY b.booking_id ASC";


$result = mysqli_query($conn, $sql);


if (!$result) {

    die("Database Error: " . mysqli_error($conn));

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
        Booking Management - Campus Sports
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

<main class="container-fluid py-5">


    <div class="container-fluid">


        <!-- Page Header -->

        <div class="mb-4">

            <h1 class="fw-bold mb-1">

                Booking Management

            </h1>

            <p class="text-muted mb-0">

                View and manage all facility bookings.

            </p>

        </div>


        <!-- =========================
             Booking Table
        ========================= -->

        <div class="card border-0 shadow-sm">


            <div class="card-body p-0">


                <div class="table-responsive">


                    <table
                        class="table table-hover align-middle mb-0">


                        <thead class="table-dark">

                            <tr>


                                <th class="px-3">

                                    Booking ID

                                </th>


                                <th>

                                    Student ID

                                </th>


                                <th>

                                    Student Name

                                </th>


                                <th>

                                    Email

                                </th>


                                <th>

                                    Facility

                                </th>


                                <th>

                                    Date

                                </th>


                                <th>

                                    Start Time

                                </th>


                                <th>

                                    End Time

                                </th>


                                <th>

                                    Number of Pax

                                </th>


                                <th>

                                    Status

                                </th>


                                <th class="text-center">

                                    Action

                                </th>


                            </tr>

                        </thead>


                        <tbody>


                        <?php

                        while (
                            $booking =
                            mysqli_fetch_assoc($result)
                        ) {

                        ?>


                            <tr>


                                <!-- Booking ID -->

                                <td class="px-3">

                                    <?= htmlspecialchars(
                                        $booking["booking_id"]
                                    ); ?>

                                </td>


                                <!-- Student ID -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["student_id"]
                                    ); ?>

                                </td>


                                <!-- Student Name -->

                                <td>

                                    <strong>

                                        <?= htmlspecialchars(
                                            $booking["name"]
                                        ); ?>

                                    </strong>

                                </td>


                                <!-- Email -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["email"]
                                    ); ?>

                                </td>


                                <!-- Facility -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["facility_name"]
                                    ); ?>

                                </td>


                                <!-- Booking Date -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["booking_date"]
                                    ); ?>

                                </td>


                                <!-- Start Time -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["start_time"]
                                    ); ?>

                                </td>


                                <!-- End Time -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["end_time"]
                                    ); ?>

                                </td>


                                <!-- Number of Pax -->

                                <td>

                                    <?= htmlspecialchars(
                                        $booking["group_size"]
                                    ); ?>

                                </td>


                                <!-- Status -->

                                <td>


                                    <?php

                                    if (
                                        $booking["status"]
                                        == "Approved"
                                    ) {

                                    ?>

                                        <span
                                            class="badge bg-success">

                                            Approved

                                        </span>

                                    <?php

                                    } elseif (
                                        $booking["status"]
                                        == "Pending"
                                    ) {

                                    ?>

                                        <span
                                            class="badge bg-warning text-dark">

                                            Pending

                                        </span>

                                    <?php

                                    } elseif (
                                        $booking["status"]
                                        == "Cancelled"
                                    ) {

                                    ?>

                                        <span
                                            class="badge bg-danger">

                                            Cancelled

                                        </span>

                                    <?php

                                    } else {

                                    ?>

                                        <span
                                            class="badge bg-secondary">

                                            <?= htmlspecialchars(
                                                $booking["status"]
                                            ); ?>

                                        </span>

                                    <?php

                                    }

                                    ?>


                                </td>


                                <!-- Action -->

                                <td class="text-center">


                                    <a
                                        href="admin_booking_edit.php?id=<?= $booking["booking_id"]; ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        <i
                                            class="bi bi-pencil">
                                        </i>

                                        Edit

                                    </a>


                                </td>


                            </tr>


                        <?php

                        }

                        ?>


                        <?php

                        if (mysqli_num_rows($result) == 0) {

                        ?>

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center text-muted py-5">

                                    No bookings found.

                                </td>

                            </tr>

                        <?php

                        }

                        ?>


                        </tbody>

                    </table>

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