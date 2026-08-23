<?php
// 确保 Session 已开启
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 获取当前登录用户的信息（如果已登录）
$is_logged_in = isset($_SESSION['user_id']);
$user_name    = $is_logged_in ? $_SESSION['name'] : 'Guest';
$user_role    = $is_logged_in && isset($_SESSION['role']) ? $_SESSION['role'] : '';

// 设置头像路径：如果用户有 photo 且不为空，则读取，否则用默认图片
$user_photo = 'images/profile.png'; // 默认头像
if ($is_logged_in && !empty($_SESSION['photo'])) {
    $custom_photo = 'images/' . $_SESSION['photo'];
    if (file_exists($custom_photo)) {
        $user_photo = $custom_photo;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Campus Sports Booking System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            Campus Sports
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="facilities.php">
                        Facilities
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="schedule.php">
                        Schedule
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="booking.php">
                        Booking
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="contact.php">
                        Contact
                    </a>
                </li>

                <?php if ($is_logged_in): ?>
                    <!-- 已登录状态 -->
                    <li class="nav-item dropdown ms-lg-3">

                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           data-bs-offset="0, 8"> 

                            <!-- Profile Circle -->
                            <img src="<?php echo htmlspecialchars($user_photo); ?>"
                                 class="rounded-circle me-2"
                                 width="38"
                                 height="38"
                                 style="object-fit: cover;"
                                 alt="Profile">

                            <div class="text-white fs-6 lh-sm text-start me-1">
                                <span class="d-block fw-light text-white-50">Welcome,</span>
                                <span class="d-block fw-semibold text-white">
                                    <?php echo htmlspecialchars($user_name); ?>
                                </span>
                            </div>

                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow">

                            <li>
                                <a class="dropdown-item" href="user.php">
                                    User Profile
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item" href="my_booking.php">
                                    My Booking
                                </a>
                            </li>

                            <?php if ($user_role === 'admin'): ?>
                                <li>
                                    <a class="dropdown-item text-primary" href="admin_dashboard.php">
                                        Admin Dashboard
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    Logout
                                </a>
                            </li>

                        </ul>

                    </li>
                <?php else: ?>
                    <!-- 未登录状态 -->
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-light btn-sm px-3" href="login.php">Login</a>
                    </li>
                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>

<!-- Main Content -->
<main>