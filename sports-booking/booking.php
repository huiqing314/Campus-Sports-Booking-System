<?php

include "includes/session.php";  // Session check
include "includes/db.php";
include "includes/header.php";

// ---------- Form processing ----------
$errors  = [];
$success = false;
$booking = [];

$allowed_facilities = [
    'Badminton Court',
    'Basketball Court',
    'Football Field',
    'Swimming Pool',
    'Tennis Court',
    'Gymnasium',
];

$allowed_times = [
    '8:00 AM',
    '10:00 AM',
    '12:00 PM',
    '2:00 PM',
    '4:00 PM',
    '6:00 PM',
];

$pre_facility = trim($_GET['facility'] ?? $_POST['facility'] ?? '');
$pre_date     = trim($_GET['date']     ?? $_POST['booking_date'] ?? '');
$pre_time     = trim($_GET['time']     ?? $_POST['booking_time'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student_name  = trim($_POST['student_name']  ?? '');
    $student_id    = trim($_POST['student_id']    ?? '');
    $email         = trim($_POST['email']         ?? '');
    $facility      = trim($_POST['facility']      ?? '');
    $booking_date  = trim($_POST['booking_date']  ?? '');
    $booking_time  = trim($_POST['booking_time']  ?? '');
    $quantity      = trim($_POST['quantity']      ?? '');

    // Validation
    if ($student_name === '') {
        $errors[] = 'Student Name is required.';
    } elseif (strlen($student_name) < 2) {
        $errors[] = 'Student Name must be at least 2 characters.';
    }

    if ($student_id === '') {
        $errors[] = 'Student ID is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($facility === '' || !in_array($facility, $allowed_facilities, true)) {
        $errors[] = 'Please select a valid facility.';
    }

    if ($booking_date === '') {
        $errors[] = 'Booking Date is required.';
    } elseif ($booking_date < date('Y-m-d')) {
        $errors[] = 'Booking Date cannot be in the past.';
    }

    if ($booking_time === '' || !in_array($booking_time, $allowed_times, true)) {
        $errors[] = 'Please select a valid booking time.';
    }

    if ($quantity === '' || !is_numeric($quantity) || (int)$quantity < 1) {
        $errors[] = 'Number of Participants must be at least 1.';
    } elseif ((int)$quantity > 50) {
        $errors[] = 'Number of Participants cannot exceed 50.';
    }

    // Insert booking into database
    if (empty($errors)) {

        // Get facility_id
        $facility_sql = "
            SELECT facility_id 
            FROM facilities 
            WHERE facility_name='$facility'
        ";

        $facility_result = mysqli_query($conn, $facility_sql);
        $facility_data = mysqli_fetch_assoc($facility_result);

        if ($facility_data) {

            $facility_id = $facility_data['facility_id'];

            // Convert time format
            $booking_time_db = date("H:i:s", strtotime($booking_time));
            $end_time_db     = date("H:i:s", strtotime("+2 hours", strtotime($booking_time)));

            // ===== Check if already booked =====
            $check_sql = "
                SELECT * FROM bookings 
                WHERE facility_id = '$facility_id' 
                AND booking_date = '$booking_date' 
                AND start_time = '$booking_time_db'
                AND status != 'Cancelled'
            ";

            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                $errors[] = "Sorry, this facility is already booked at the selected date and time.";
            } else {

                // Insert booking
                $insert_sql = "
                INSERT INTO bookings
                (
                    student_name,
                    student_id,
                    email,
                    facility_id,
                    booking_date,
                    start_time,
                    end_time,
                    quantity,
                    status
                )
                VALUES
                (
                    '$student_name',
                    '$student_id',
                    '$email',
                    '$facility_id',
                    '$booking_date',
                    '$booking_time_db',
                    '$end_time_db',
                    '$quantity',
                    'Pending'
                )
                ";

                $insert_result = mysqli_query($conn, $insert_sql);

                if ($insert_result) {

                    $success = true;

                    $booking = [
                        'student_name' => $student_name,
                        'student_id'   => $student_id,
                        'email'        => $email,
                        'facility'     => $facility,
                        'booking_date' => $booking_date,
                        'booking_time' => $booking_time,
                        'quantity'     => (int)$quantity,
                    ];

                } else {
                    $errors[] = "Database Error: " . mysqli_error($conn);
                }
            }

        } else {
            $errors[] = "Facility not found.";
        }
    }
}
?>


<section class="container py-5">


<div class="row justify-content-center">


<div class="col-md-8">


<?php if ($success) : ?>

<!-- ===== Success Message ===== -->
<div class="card shadow border-success p-4">

<div class="text-center mb-4">
<h2 class="text-success">✅ Booking Submitted Successfully!</h2>
<p class="text-muted">Your booking request has been received.</p>
</div>

<table class="table table-bordered">
<tr>
<th style="width:40%">Student Name</th>
<td><?= htmlspecialchars($booking['student_name']) ?></td>
</tr>
<tr>
<th>Student ID</th>
<td><?= htmlspecialchars($booking['student_id']) ?></td>
</tr>
<tr>
<th>Email</th>
<td><?= htmlspecialchars($booking['email']) ?></td>
</tr>
<tr>
<th>Facility</th>
<td><?= htmlspecialchars($booking['facility']) ?></td>
</tr>
<tr>
<th>Booking Date</th>
<td><?= htmlspecialchars($booking['booking_date']) ?></td>
</tr>
<tr>
<th>Time Slot</th>
<td>
<?= htmlspecialchars($booking['booking_time']) ?>
-
<?= date("h:i A", strtotime("+2 hours", strtotime($booking['booking_time']))) ?>
</td>
</tr>
<tr>
<th>Participants</th>
<td><?= htmlspecialchars((string)$booking['quantity']) ?></td>
</tr>
</table>

<div class="text-center mt-3">
<a href="booking.php" class="btn btn-success me-2">Make Another Booking</a>
<a href="index.php" class="btn btn-outline-secondary">Back to Home</a>
</div>

</div>

<?php else : ?>

<!-- ===== Booking Form ===== -->
<div class="card shadow p-4">


<h2 class="text-center mb-4">

Book Sports Facility

</h2>


<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
<strong>Please fix the following errors:</strong>
<ul class="mb-0 mt-2">
<?php foreach ($errors as $err) : ?>
<li><?= htmlspecialchars($err) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endif; ?>


<form method="POST" action="">



<!-- Student Name -->

<div class="mb-3">


<label class="form-label">

Student Name

</label>


<input type="text" 
name="student_name"
class="form-control"
placeholder="Enter your name"
value="<?= htmlspecialchars($_POST['student_name'] ?? '') ?>"
required>


</div>



<!-- Student ID -->


<div class="mb-3">


<label class="form-label">

Student ID

</label>


<input type="text"
name="student_id"
class="form-control"
placeholder="Enter student ID"
value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>"
required>


</div>




<!-- Email -->


<div class="mb-3">


<label class="form-label">

Email

</label>


<input type="email"
name="email"
class="form-control"
placeholder="Enter email"
value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
required>


</div>




<!-- Facility -->


<div class="mb-3">


<label class="form-label">

Select Facility

</label>


<select name="facility"
class="form-select"
required>


<option value="">

-- Select Facility --

</option>


<option value="Badminton Court" <?= ($pre_facility === 'Badminton Court') ? 'selected' : '' ?>>

Badminton Court

</option>


<option value="Basketball Court" <?= ($pre_facility === 'Basketball Court') ? 'selected' : '' ?>>

Basketball Court

</option>


<option value="Football Field" <?= ($pre_facility === 'Football Field') ? 'selected' : '' ?>>

Football Field

</option>


<option value="Swimming Pool" <?= ($pre_facility === 'Swimming Pool') ? 'selected' : '' ?>>

Swimming Pool

</option>


<option value="Tennis Court" <?= ($pre_facility === 'Tennis Court') ? 'selected' : '' ?>>

Tennis Court

</option>


<option value="Gymnasium" <?= ($pre_facility === 'Gymnasium') ? 'selected' : '' ?>>

Gymnasium

</option>



</select>


</div>





<!-- Date -->


<div class="mb-3">


<label class="form-label">

Booking Date

</label>


<input type="date"
name="booking_date"
class="form-control"
min="<?= date('Y-m-d') ?>"
value="<?= htmlspecialchars($pre_date) ?>"
required>


</div>





<!-- Time -->


<div class="mb-3">


<label class="form-label">

Booking Time

</label>



<select name="booking_time"
class="form-select"
required>


<option value="">-- Select Time --</option>


<option value="8:00 AM" <?= ($pre_time === '8:00 AM') ? 'selected' : '' ?>>

8:00 AM

</option>


<option value="10:00 AM" <?= ($pre_time === '10:00 AM') ? 'selected' : '' ?>>

10:00 AM

</option>


<option value="12:00 PM" <?= ($pre_time === '12:00 PM') ? 'selected' : '' ?>>

12:00 PM

</option>


<option value="2:00 PM" <?= ($pre_time === '2:00 PM') ? 'selected' : '' ?>>

2:00 PM

</option>


<option value="4:00 PM" <?= ($pre_time === '4:00 PM') ? 'selected' : '' ?>>

4:00 PM

</option>


<option value="6:00 PM" <?= ($pre_time === '6:00 PM') ? 'selected' : '' ?>>

6:00 PM

</option>



</select>


</div>





<!-- Quantity -->


<div class="mb-3">


<label class="form-label">

Number of Participants

</label>


<input type="number"
name="quantity"
class="form-control"
min="1"
max="50"
value="<?= htmlspecialchars($_POST['quantity'] ?? '1') ?>"
required>


</div>





<button type="submit"
class="btn btn-success w-100">


Submit Booking


</button>



</form>


</div>

<?php endif; ?>


</div>


</div>


</section>




<?php

include "includes/footer.php";

?>