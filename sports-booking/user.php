<?php
// 1. 开启 Session
require_once __DIR__ . '/includes/session.php';

// 如果未登录，直接跳回登录页面
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 包含数据库配置文件 (MySQLi)
require_once __DIR__ . '/includes/db.php';

$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

// 2. 处理表单提交（更新个人资料）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email)) {
        $error = "Name and Email cannot be empty!";
    } else {
        // 处理头像上传
        $photo_name = $_SESSION['photo'] ?? null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $file_tmp      = $_FILES['photo']['tmp_name'];
            $original_name = $_FILES['photo']['name'];
            $file_ext      = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
            $allowed_exts  = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_exts)) {
                $new_photo_name = 'user_' . $user_id . '_' . time() . '.' . $file_ext;
                $upload_dir     = __DIR__ . '/images/';

                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                if (move_uploaded_file($file_tmp, $upload_dir . $new_photo_name)) {
                    $photo_name = $new_photo_name;
                } else {
                    $error = "Failed to upload image.";
                }
            } else {
                $error = "Invalid image format! Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        }

        // 更新数据库 (MySQLi)
        if (empty($error)) {
            if (!empty($password)) {
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, photo = ? WHERE user_id = ?");
                $stmt->bind_param("ssssi", $name, $email, $password, $photo_name, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, photo = ? WHERE user_id = ?");
                $stmt->bind_param("sssi", $name, $email, $photo_name, $user_id);
            }

            if ($stmt->execute()) {
                $_SESSION['name']  = $name;
                $_SESSION['photo'] = $photo_name;
                $message = "Profile updated successfully!";
            } else {
                $error = "Database error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// 3. 从数据库查询最新的用户信息 (MySQLi)
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user   = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found!");
}

// 判断头像路径
$avatar = 'images/profile.png';
if (!empty($user['photo']) && file_exists(__DIR__ . '/images/' . $user['photo'])) {
    $avatar = 'images/' . $user['photo'];
}

// 引入 Header
include __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="mb-0 fw-bold">User Profile</h4>
                </div>
                <div class="card-body p-4">

                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="user.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="text-center mb-4">
                            <img src="<?php echo htmlspecialchars($avatar); ?>" 
                                 class="rounded-circle img-thumbnail shadow-sm mb-2" 
                                 width="120" height="120" 
                                 style="object-fit: cover;" 
                                 alt="User Photo">
                            
                            <div class="mt-2">
                                <label for="photo" class="form-label text-muted fs-7">Change Profile Photo</label>
                                <input class="form-control form-control-sm" type="file" id="photo" name="photo" accept="image/*">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Student / Staff ID</label>
                            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['student_id']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Role</label>
                            <input type="text" class="form-control bg-light text-capitalize" value="<?php echo htmlspecialchars($user['role']); ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-semibold">Save Changes</button>
                            <a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
if (file_exists(__DIR__ . '/includes/footer.php')) {
    include __DIR__ . '/includes/footer.php';
} else {
    echo '</main></body></html>';
}
?>