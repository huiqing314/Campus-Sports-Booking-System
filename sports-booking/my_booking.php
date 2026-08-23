<?php
// 1. 引入 Session
require_once __DIR__ . '/includes/session.php';

// 验证登录
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. 引入数据库连接 (MySQLi)
require_once __DIR__ . '/includes/db.php';

$user_id = $_SESSION['user_id'];
$message = '';
$error   = '';

// 3. 处理取消预订请求 (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $booking_id = intval($_POST['booking_id']);

    // 安全检查：确保该预订记录属于当前登录用户
    $check_stmt = $conn->prepare("SELECT booking_id FROM bookings WHERE booking_id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $booking_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // 更新预订状态为 Cancelled
        $update_stmt = $conn->prepare("UPDATE bookings SET status = 'Cancelled' WHERE booking_id = ?");
        $update_stmt->bind_param("i", $booking_id);
        
        if ($update_stmt->execute()) {
            $message = "Booking #{$booking_id} has been cancelled successfully.";
        } else {
            $error = "Failed to cancel the booking: " . $conn->error;
        }
        $update_stmt->close();
    } else {
        $error = "Invalid booking or permission denied.";
    }
    $check_stmt->close();
}

// 4. 从数据库获取该用户的所有预订记录（关联 facilities 设施表）
$query = "
    SELECT b.*, f.facility_name, f.location 
    FROM bookings b
    JOIN facilities f ON b.facility_id = f.facility_id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC, b.start_time DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// 引入 Header
include __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">My Bookings</h2>
            <p class="text-muted mb-0">Manage and view all your sports facility reservations</p>
        </div>
        <a href="booking.php" class="btn btn-primary fw-semibold">+ New Booking</a>
    </div>

    <!-- 消息提示 -->
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

    <!-- 预订列表 -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="ps-4">ID</th>
                                <th scope="col">Facility</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time Slot</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-secondary">
                                        #<?php echo htmlspecialchars($row['booking_id']); ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            <?php echo htmlspecialchars($row['facility_name']); ?>
                                        </div>
                                        <?php if (!empty($row['location'])): ?>
                                            <small class="text-muted"><?php echo htmlspecialchars($row['location']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($row['booking_date'])); ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $start = date('h:i A', strtotime($row['start_time']));
                                            $end   = date('h:i A', strtotime($row['end_time']));
                                            echo $start . ' - ' . $end;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                            $status = strtolower($row['status'] ?? 'pending');
                                            $badgeClass = 'bg-secondary';
                                            
                                            if ($status === 'approved' || $status === 'confirmed') {
                                                $badgeClass = 'bg-success';
                                            } elseif ($status === 'pending') {
                                                $badgeClass = 'bg-warning text-dark';
                                            } elseif ($status === 'cancelled' || $status === 'rejected') {
                                                $badgeClass = 'bg-danger';
                                            }
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?> px-2 py-1">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if ($status !== 'cancelled' && $status !== 'rejected'): ?>
                                            <form action="my_booking.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                <input type="hidden" name="action" value="cancel">
                                                <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted fs-7">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <p class="text-muted mb-3">You don't have any bookings yet.</p>
                    <a href="booking.php" class="btn btn-outline-primary btn-sm">Make a Booking Now</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$stmt->close();

if (file_exists(__DIR__ . '/includes/footer.php')) {
    include __DIR__ . '/includes/footer.php';
} else {
    echo '</main></body></html>';
}
?>