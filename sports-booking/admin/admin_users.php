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
   Get All Users
========================= */

$sql = "SELECT
            user_id,
            student_id,
            name,
            email,
            role,
            photo
        FROM users
        ORDER BY user_id ASC";

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
        User Management - Campus Sports
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
                            class="nav-link active"
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

                    User Management

                </h1>

                <p class="text-muted mb-0">

                    Manage student and admin accounts.

                </p>

            </div>


            <!-- Add User Button -->

            <a
                href="admin_user_add.php"
                class="btn btn-primary">

                <i class="bi bi-person-plus-fill me-1"></i>

                Add User

            </a>


        </div>


        <!-- =========================
         Users Table
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

                                    Photo

                                </th>


                                <th>

                                    Student ID

                                </th>


                                <th>

                                    Name

                                </th>


                                <th>

                                    Email

                                </th>


                                <th>

                                    Role

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
                                $user =
                                mysqli_fetch_assoc($result)
                            ) {


                                $photo = $user["photo"];


                                /* Default Profile Photo */

                                if (empty($photo)) {

                                    $photo = "profile.png";
                                }

                            ?>


                                <tr>


                                    <!-- Number -->

                                    <td class="px-4">

                                        <?= $counter; ?>

                                    </td>


                                    <!-- Photo -->

                                    <td>

                                        <img
                                            src="../uploads/<?= htmlspecialchars($photo); ?>"
                                            alt="Profile Photo"
                                            width="50"
                                            height="50"
                                            class="rounded-circle"
                                            style="object-fit:cover;">

                                    </td>


                                    <!-- Student ID -->

                                    <td>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $user["student_id"]
                                            ); ?>

                                        </strong>

                                    </td>


                                    <!-- Name -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $user["name"]
                                        ); ?>

                                    </td>


                                    <!-- Email -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $user["email"]
                                        ); ?>

                                    </td>


                                    <!-- Role -->

                                    <td>


                                        <?php

                                        if (
                                            $user["role"]
                                            === "admin"
                                        ) {

                                        ?>

                                            <span
                                                class="badge bg-danger">

                                                Admin

                                            </span>

                                        <?php

                                        } else {

                                        ?>

                                            <span
                                                class="badge bg-primary">

                                                Student

                                            </span>

                                        <?php

                                        }


                                        ?>

                                    </td>


                                    <!-- Actions -->

                                    <td class="text-center">


                                        <!-- Edit -->

                                        <a
                                            href="admin_user_edit.php?id=<?= $user["user_id"]; ?>"
                                            class="btn btn-sm btn-outline-primary me-1">

                                            <i
                                                class="bi bi-pencil">
                                            </i>

                                            Edit

                                        </a>


                                        <!-- Delete -->

                                        <?php

                                        if (
                                            $user["user_id"]
                                            == $_SESSION["user_id"]
                                        ) {

                                        ?>

                                            <!-- Current Admin -->

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary"
                                                disabled
                                                title="You cannot delete your own account">

                                                <i
                                                    class="bi bi-trash">
                                                </i>

                                                Delete

                                            </button>

                                        <?php

                                        } else {

                                        ?>

                                            <!-- Other Users -->

                                            <a
                                                href="admin_user_delete.php?id=<?= $user["user_id"]; ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this user?');">

                                                <i
                                                    class="bi bi-trash">
                                                </i>

                                                Delete

                                            </a>

                                        <?php

                                        }


                                        ?>

                                    </td>


                                </tr>


                            <?php

                                $counter++;
                            }

                            ?>


                            <?php

                            if ($counter === 1) {

                            ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center text-muted py-5">

                                        No users found.

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


    </main>


    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>


</body>

</html>