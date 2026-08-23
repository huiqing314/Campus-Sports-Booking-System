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
$success = "";


/* =========================
   Add User
========================= */

if (isset($_POST["add_user"])) {

    $student_id = mysqli_real_escape_string(
        $conn,
        trim($_POST["student_id"])
    );

    $name = mysqli_real_escape_string(
        $conn,
        trim($_POST["name"])
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST["email"])
    );

    $password = mysqli_real_escape_string(
        $conn,
        $_POST["password"]
    );

    $role = mysqli_real_escape_string(
        $conn,
        $_POST["role"]
    );


    /* Check duplicate Student ID */

    $check_sql = "SELECT user_id
                  FROM users
                  WHERE student_id = '$student_id'";

    $check_result = mysqli_query($conn, $check_sql);


    if (mysqli_num_rows($check_result) > 0) {

        $error = "This Student ID already exists.";

    } else {


        /* Check duplicate Email */

        $email_sql = "SELECT user_id
                      FROM users
                      WHERE email = '$email'";

        $email_result = mysqli_query($conn, $email_sql);


        if (mysqli_num_rows($email_result) > 0) {

            $error = "This email already exists.";

        } else {


            /* Insert User */

            $sql = "INSERT INTO users
                    (
                        student_id,
                        name,
                        email,
                        password,
                        role,
                        photo
                    )
                    VALUES
                    (
                        '$student_id',
                        '$name',
                        '$email',
                        '$password',
                        '$role',
                        'default-avatar.jpg'
                    )";


            if (mysqli_query($conn, $sql)) {

                header("Location: admin_users.php");
                exit();

            } else {

                $error = "Database Error: "
                       . mysqli_error($conn);

            }

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Add User - Campus Sports
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


            <!-- Page Header -->

            <div class="mb-4">

                <h1 class="fw-bold">

                    Add User

                </h1>

                <p class="text-muted">

                    Create a new student or admin account.

                </p>

            </div>


            <!-- Error -->

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <!-- Form Card -->

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">


                    <form method="POST">


                        <!-- Student ID -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Student ID

                            </label>

                            <input
                                type="text"
                                name="student_id"
                                class="form-control"
                                placeholder="e.g. 2505125"
                                required>

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
                                placeholder="Enter full name"
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
                                placeholder="example@student.com"
                                required>

                        </div>


                        <!-- Password -->

                        <div class="mb-3">

                            <label
                                class="form-label fw-semibold">

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter password"
                                required>

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
                                    selected>

                                    Student

                                </option>

                                <option value="admin">

                                    Admin

                                </option>

                            </select>

                        </div>


                        <!-- Buttons -->

                        <div
                            class="d-flex gap-2">


                            <a
                                href="admin_users.php"
                                class="btn btn-secondary">

                                <i class="bi bi-arrow-left me-1"></i>

                                Cancel

                            </a>


                            <button
                                type="submit"
                                name="add_user"
                                class="btn btn-primary">

                                <i class="bi bi-person-plus-fill me-1"></i>

                                Add User

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