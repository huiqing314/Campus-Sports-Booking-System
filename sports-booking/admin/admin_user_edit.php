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

$edit_user_id = (int) $_GET["id"];


/* =========================
   Get User Information
========================= */

$sql = "SELECT *
        FROM users
        WHERE user_id = '$edit_user_id'";

$result = mysqli_query($conn, $sql);


if (!$result || mysqli_num_rows($result) != 1) {

    header("Location: admin_users.php");
    exit();

}


$user = mysqli_fetch_assoc($result);

$error = "";


/* =========================
   Update User
========================= */

if (isset($_POST["update_user"])) {

    $name = mysqli_real_escape_string(
        $conn,
        trim($_POST["name"])
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST["email"])
    );

    $role = mysqli_real_escape_string(
        $conn,
        $_POST["role"]
    );

    $password = $_POST["password"];


    /* =========================
       Check Email
    ========================= */

    $check_email_sql = "SELECT user_id
                        FROM users
                        WHERE email = '$email'
                        AND user_id != '$edit_user_id'";

    $check_email_result = mysqli_query(
        $conn,
        $check_email_sql
    );


    if (mysqli_num_rows($check_email_result) > 0) {

        $error = "This email already exists.";

    } else {


        /* =========================
           Update With Password
        ========================= */

        if (!empty($password)) {

            $password = mysqli_real_escape_string(
                $conn,
                $password
            );

            $update_sql = "UPDATE users
                           SET name = '$name',
                               email = '$email',
                               password = '$password',
                               role = '$role'
                           WHERE user_id = '$edit_user_id'";

        } else {


            /* =========================
               Update Without Password
            ========================= */

            $update_sql = "UPDATE users
                           SET name = '$name',
                               email = '$email',
                               role = '$role'
                           WHERE user_id = '$edit_user_id'";

        }


        if (mysqli_query($conn, $update_sql)) {

            header("Location: admin_users.php");
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
        Edit User - Campus Sports
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
                        class="nav-link active"
                        href="admin_users.php">

                        Users

                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
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

                    Edit User

                </h1>

                <p class="text-muted">

                    Update user account information.

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


                    <!-- Current Photo -->

                    <?php

                    $photo = $user["photo"];

                    if (empty($photo)) {

                        $photo = "default-avatar.jpg";

                    }

                    ?>


                    <div class="text-center mb-4">

                        <img
                            src="../uploads/<?= htmlspecialchars($photo); ?>"
                            width="120"
                            height="120"
                            class="rounded-circle shadow-sm"
                            style="object-fit: cover;"
                            alt="Profile Photo">

                    </div>


                    <form method="POST">


                        <!-- Student ID -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Student ID

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="<?= htmlspecialchars($user["student_id"]); ?>"
                                readonly>


                            <small class="text-muted">

                                Student ID cannot be changed.

                            </small>

                        </div>


                        <!-- Name -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Name

                            </label>


                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="<?= htmlspecialchars($user["name"]); ?>"
                                required>

                        </div>


                        <!-- Email -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Email

                            </label>


                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($user["email"]); ?>"
                                required>

                        </div>


                        <!-- Password -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                New Password

                            </label>


                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Leave blank to keep current password">


                            <small class="text-muted">

                                Leave blank if you do not want to change
                                the password.

                            </small>

                        </div>


                        <!-- Role -->

                        <div class="mb-4">

                            <label
                                class="form-label fw-semibold">

                                Role

                            </label>


                            <select
                                name="role"
                                class="form-select"
                                required>


                                <option
                                    value="student"
                                    <?php
                                    if ($user["role"] == "student") {
                                        echo "selected";
                                    }
                                    ?>>

                                    Student

                                </option>


                                <option
                                    value="admin"
                                    <?php
                                    if ($user["role"] == "admin") {
                                        echo "selected";
                                    }
                                    ?>>

                                    Admin

                                </option>


                            </select>

                        </div>


                        <!-- Buttons -->

                        <div class="d-flex gap-2">


                            <a
                                href="admin_users.php"
                                class="btn btn-secondary">

                                <i
                                    class="bi bi-arrow-left me-1">
                                </i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                name="update_user"
                                class="btn btn-primary">

                                <i
                                    class="bi bi-check-circle me-1">
                                </i>

                                Update User

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