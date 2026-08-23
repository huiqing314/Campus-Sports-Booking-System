<?php
session_start();

// 引入数据库配置文件
require_once 'db.php';

// 如果取消注释这几行，可以启用强制登录逻辑：
/*
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
*/

// 获取当前登录用户 ID（如果 session 没有，默认使用 ID = 1 测试）
$user_id = $_SESSION['user_id'] ?? 1;

$success_msg = '';
$error_msg   = '';

/* ===================================================
   1. 处理表单提交 (POST Requests)
   =================================================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // A. 处理个人资料更新
    if (isset($_POST['update_profile'])) {
        $username = trim($_POST['username']);
        $email    = trim($_POST['email']);
        $phone    = trim($_POST['phone']);

        if (empty($username) || empty($email)) {
            $error_msg = "Username and Email cannot be empty!";
        } else {
            try {
                $sql  = "UPDATE users SET username = :username, email = :email, phone = :phone WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':username' => $username,
                    ':email'    => $email,
                    ':phone'    => $phone,
                    ':id'       => $user_id
                ]);

                $_SESSION['username'] = $username;
                $success_msg = "Profile updated successfully!";
            } catch (PDOException $e) {
                $error_msg = "Database Error: " . $e->getMessage();
            }
        }
    }

    // B. 处理密码修改
    if (isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass     = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
            $error_msg = "Please fill in all password fields.";
        } elseif ($new_pass !== $confirm_pass) {
            $error_msg = "New password and Confirm password do not match.";
        } elseif (strlen($new_pass) < 6) {
            $error_msg = "New password must be at least 6 characters long.";
        } else {
            try {
                // 验证旧密码
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
                $stmt->execute([':id' => $user_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($current_pass, $user['password'])) {
                    // 哈希加密新密码
                    $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                    $update_stmt = $pdo->prepare("UPDATE users SET password = :pass WHERE id = :id");
                    $update_stmt->execute([':pass' => $hashed_pass, ':id' => $user_id]);

                    $success_msg = "Password changed successfully!";
                } else {
                    $error_msg = "Current password is incorrect.";
                }
            } catch (PDOException $e) {
                $error_msg = "Database Error: " . $e->getMessage();
            }
        }
    }
}

/* ===================================================
   2. 从数据库读取当前用户信息 (Fetch User Data)
   =================================================== */
try {
    $stmtUser = $pdo->prepare("SELECT username, email, phone, created_at FROM users WHERE id = :id");
    $stmtUser->execute([':id' => $user_id]);
    $currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$currentUser) {
        // 如果数据库没有该 ID 的备用保底数据
        $currentUser = [
            'username'   => $_SESSION['username'] ?? 'User',
            'email'      => 'notfound@example.com',
            'phone'      => 'N/A',
            'created_at' => date('Y-m-d')
        ];
    }
} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

/* ===================================================
   3. 从数据库读取预定历史 (Fetch Booking History)
   =================================================== */
try {
    // 假设联表查询 bookings 和 facilities 表
    $sqlHistory = "SELECT b.booking_id, f.facility_name, b.booking_date, b.time_slot, b.status 
                   FROM bookings b 
                   LEFT JOIN facilities f ON b.facility_id = f.facility_id 
                   WHERE b.user_id = :user_id 
                   ORDER BY b.booking_date DESC";
    
    $stmtHistory = $pdo->prepare($sqlHistory);
    $stmtHistory->execute([':user_id' => $user_id]);
    $bookingHistory = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // 如果没有 facilities 表，使用单表查询逻辑：
    $bookingHistory = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile - Campus Sports Booking System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light">

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
                    <a class="nav-link fs-6 fw-medium" href="facilities.php">Facilities</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="schedule.php">Schedule</a>
                </li>
                <li class="nav-item">
                     <a class="nav-link fs-6 fw-medium" href="booking.php">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="contact.php">Contact</a>
                </li>

                <li class="nav-item dropdown ms-lg-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       data-bs-offset="0, 8">

                        <!-- Profile Circle -->
                        <img src="images/profile.png"
                             class="rounded-circle me-2"
                             width="38"
                             height="38"
                             alt="Profile">

                        <!-- Welcome & Username -->
                        <div class="text-white fs-6 lh-sm text-start me-1">
                            <span class="d-block fw-light text-white-50">Welcome,</span>
                            <span class="d-block fw-semibold text-white">
                                <?php echo htmlspecialchars($currentUser['username']); ?>
                            </span>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-center shadow">
                        <li>
                            <a class="dropdown-item active" href="user.php">User Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

    </div>
</nav>

<!-- Main Content Area -->
<main class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- 标题区 -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="fw-bold text-dark mb-0">User Profile</h2>
                <span class="badge bg-primary px-3 py-2 fs-6">Student Account</span>
            </div>

            <!-- 操作成功提示词 -->
            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <strong>Success!</strong> <?php echo htmlspecialchars($success_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- 操作错误提示词 -->
            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <strong>Error!</strong> <?php echo htmlspecialchars($error_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- 左侧：个人信息预览卡片 -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center p-4">
                        <div class="mb-3">
                            <img src="images/profile.png" 
                                 class="rounded-circle img-thumbnail shadow-sm" 
                                 width="120" 
                                 height="120" 
                                 alt="Profile Picture">
                        </div>
                        <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($currentUser['username']); ?></h4>
                        <p class="text-muted mb-3"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                        <hr class="my-3">
                        <div class="text-start fs-6 text-secondary">
                            <p class="mb-2"><strong>Phone:</strong> <?php echo htmlspecialchars($currentUser['phone']); ?></p>
                            <p class="mb-0"><strong>Member Since:</strong> <?php echo htmlspecialchars($currentUser['created_at']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- 右侧：Tab 标签切换 -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                            <ul class="nav nav-tabs card-header-tabs" id="profileTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold" 
                                            id="edit-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#edit-profile" 
                                            type="button" 
                                            role="tab">
                                        Edit Profile
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold text-danger" 
                                            id="password-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#change-password" 
                                            type="button" 
                                            role="tab">
                                        Change Password
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold text-success" 
                                            id="history-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#booking-history" 
                                            type="button" 
                                            role="tab">
                                        Booking History
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content" id="profileTabContent">

                                <!-- Tab 1: 修改个人资料表单 -->
                                <div class="tab-pane fade show active" id="edit-profile" role="tabpanel">
                                    <form action="user.php" method="POST">
                                        
                                        <div class="mb-3">
                                            <label for="username" class="form-label fw-semibold">Username</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="username" 
                                                   name="username" 
                                                   value="<?php echo htmlspecialchars($currentUser['username']); ?>" 
                                                   required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label fw-semibold">Email Address</label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="email" 
                                                   name="email" 
                                                   value="<?php echo htmlspecialchars($currentUser['email']); ?>" 
                                                   required>
                                        </div>

                                        <div class="mb-4">
                                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="<?php echo htmlspecialchars($currentUser['phone']); ?>">
                                        </div>

                                        <button type="submit" name="update_profile" class="btn btn-primary px-4 fw-semibold">
                                            Save Changes
                                        </button>
                                    </form>
                                </div>

                                <!-- Tab 2: 修改密码表单 -->
                                <div class="tab-pane fade" id="change-password" role="tabpanel">
                                    <form action="user.php" method="POST">

                                        <div class="mb-3">
                                            <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label fw-semibold">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>

                                        <div class="mb-4">
                                            <label for="confirm_password" class="form-label fw-semibold">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                        </div>

                                        <button type="submit" name="change_password" class="btn btn-danger px-4 fw-semibold">
                                            Update Password
                                        </button>
                                    </form>
                                </div>

                                <!-- Tab 3: 预定历史表格 -->
                                <div class="tab-pane fade" id="booking-history" role="tabpanel">
                                    <h5 class="fw-bold mb-3 text-secondary">Your Past Bookings</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Facility</th>
                                                    <th>Date</th>
                                                    <th>Time Slot</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($bookingHistory)): ?>
                                                    <?php foreach ($bookingHistory as $row): ?>
                                                        <tr>
                                                            <td class="fw-semibold">
                                                                <?php echo htmlspecialchars($row['facility_name'] ?? 'Facility'); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                                            <td><?php echo htmlspecialchars($row['time_slot']); ?></td>
                                                            <td>
                                                                <?php 
                                                                    $status = $row['status'];
                                                                    $badgeBg = 'bg-secondary';
                                                                    if ($status === 'Confirmed') $badgeBg = 'bg-success';
                                                                    elseif ($status === 'Completed') $badgeBg = 'bg-primary';
                                                                    elseif ($status === 'Cancelled') $badgeBg = 'bg-danger';
                                                                ?>
                                                                <span class="badge <?php echo $badgeBg; ?> px-2 py-1">
                                                                    <?php echo htmlspecialchars($status); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-4">No booking history found.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</main>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>