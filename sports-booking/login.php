<?php

session_start();

include "includes/db.php";

$error = "";

if (isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["login"])) {

    $student_id = mysqli_real_escape_string($conn, $_POST["student_id"]);
    $password   = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT * FROM users
            WHERE student_id='$student_id'
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["student_id"] = $user["student_id"];
        $_SESSION["name"] = $user["name"];
        $_SESSION["role"] = $user["role"];

        header("Location: index.php");
        exit();

    } else {

        $error = "Invalid Student ID or Password.";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Sports Booking Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<link rel="stylesheet" href="css/style.css">

</head>

<body class="login-page">

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">

    <div class="login-box">

        <div class="text-center mb-4">

            <h1>Campus Sports</h1>

            <p>Booking System</p>

        </div>

        <?php if($error!=""){ ?>

        <div class="alert alert-danger">

            <?= $error ?>

        </div>

        <?php } ?>

        <form method="POST">

            <div class="mb-3">

                <label>Student ID</label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-person-fill"></i>

                    </span>

                    <input
                    type="text"
                    name="student_id"
                    class="form-control"
                    placeholder="Enter Student ID"
                    required>

                </div>

            </div>

            <div class="mb-4">

                <label>Password</label>

                <div class="input-group">

                    <span class="input-group-text">

                        <i class="bi bi-lock-fill"></i>

                    </span>

                    <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter Password"
                    required>

                </div>

            </div>

            <button
            type="submit"
            name="login"
            class="btn btn-warning w-100">

                Login

            </button>

        </form>

    </div>

</div>

</body>

</html>