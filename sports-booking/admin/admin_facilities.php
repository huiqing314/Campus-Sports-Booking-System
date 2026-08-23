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
   Get All Facilities
========================= */

$sql = "SELECT *
        FROM facilities
        ORDER BY facility_id ASC";

$result = mysqli_query($conn, $sql);


if (!$result) {

    die("Database Error: " . mysqli_error($conn));

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Facility Management - Campus Sports
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


    <!-- Page Header -->

    <div class="d-flex
                justify-content-between
                align-items-center
                mb-4">


        <div>

            <h1 class="fw-bold mb-1">

                Facility Management

            </h1>

            <p class="text-muted mb-0">

                Manage sports facilities and their availability.

            </p>

        </div>


        <!-- Add Facility -->

        <a
            href="admin_facility_add.php"
            class="btn btn-success">

            <i class="bi bi-plus-circle me-1"></i>

            Add Facility

        </a>


    </div>


    <!-- =========================
         Facilities Table
    ========================= -->

    <div class="card border-0 shadow-sm">


        <div class="card-body p-0">


            <div class="table-responsive">


                <table
                    class="table table-hover align-middle mb-0">


                    <thead class="table-dark">

                        <tr>


                            <th class="px-4">

                                No.

                            </th>


                            <th>

                                Facility ID

                            </th>


                            <th>

                                Facility

                            </th>


                            <th>

                                Location

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

                    $counter = 1;


                    while (
                        $facility =
                        mysqli_fetch_assoc($result)
                    ) {


                        /* =========================
                           Facility Icon
                        ========================= */

                        $icon = "🏟️";


                        if (
                            $facility["facility_name"]
                            == "Badminton Court"
                        ) {

                            $icon = "🏸";

                        } elseif (
                            $facility["facility_name"]
                            == "Basketball Court"
                        ) {

                            $icon = "🏀";

                        } elseif (
                            $facility["facility_name"]
                            == "Football Field"
                        ) {

                            $icon = "⚽";

                        } elseif (
                            $facility["facility_name"]
                            == "Swimming Pool"
                        ) {

                            $icon = "🏊‍♀️";

                        } elseif (
                            $facility["facility_name"]
                            == "Tennis Court"
                        ) {

                            $icon = "🎾";

                        } elseif (
                            $facility["facility_name"]
                            == "Gymnasium"
                        ) {

                            $icon = "🏋️";

                        }

                    ?>


                        <tr>


                            <!-- Number -->

                            <td class="px-4">

                                <?= $counter; ?>

                            </td>


                            <!-- Facility ID -->

                            <td>

                                <?= htmlspecialchars(
                                    $facility["facility_id"]
                                ); ?>

                            </td>


                            <!-- Facility -->

                            <td>

                                <span
                                    class="fs-4 me-2">

                                    <?= $icon; ?>

                                </span>


                                <strong>

                                    <?= htmlspecialchars(
                                        $facility["facility_name"]
                                    ); ?>

                                </strong>

                            </td>


                            <!-- Location -->

                            <td>

                                <?= htmlspecialchars(
                                    $facility["location"]
                                ); ?>

                            </td>


                            <!-- Status -->

                            <td>


                                <?php

                                if (
                                    $facility["status"]
                                    == "Available"
                                ) {

                                ?>

                                    <span
                                        class="badge bg-success">

                                        Available

                                    </span>

                                <?php

                                } else {

                                ?>

                                    <span
                                        class="badge bg-secondary">

                                        <?= htmlspecialchars(
                                            $facility["status"]
                                        ); ?>

                                    </span>

                                <?php

                                }

                                ?>


                            </td>


                            <!-- Actions -->

                            <td class="text-center">


                                <!-- Edit -->

                                <a
                                    href="admin_facility_edit.php?id=<?= $facility["facility_id"]; ?>"
                                    class="btn btn-sm btn-outline-primary me-1">

                                    <i class="bi bi-pencil"></i>

                                    Edit

                                </a>


                                <!-- Delete -->

                                <a
                                    href="admin_facility_delete.php?id=<?= $facility["facility_id"]; ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this facility?');">

                                    <i class="bi bi-trash"></i>

                                    Delete

                                </a>


                            </td>


                        </tr>


                    <?php

                        $counter++;

                    }

                    ?>


                    <?php if ($counter === 1) { ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-5">

                                No facilities found.

                            </td>

                        </tr>

                    <?php } ?>


                    </tbody>

                </table>

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