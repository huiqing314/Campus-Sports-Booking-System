```php
<?php

include "includes/db.php";
include "includes/header.php";

// available slots
$time_slots = [
    '08:00:00' => '8:00 AM',
    '10:00:00' => '10:00 AM',
    '12:00:00' => '12:00 PM',
    '14:00:00' => '2:00 PM',
    '16:00:00' => '4:00 PM',
    '18:00:00' => '6:00 PM',
];

// available facilities
$facilities_sql = "SELECT facility_id, facility_name FROM facilities WHERE status = 'Available'";
$facilities_result = mysqli_query($conn, $facilities_sql);

$facilities = [];
while ($row = mysqli_fetch_assoc($facilities_result)) {
    $facilities[] = $row;
}

?>


<section class="container py-5">

    <h1 class="text-center mb-4">Facility Schedule</h1>
    <p class="text-center text-muted mb-5">
        View available time slots for the next 7 days.
    </p>

    <div class="table-responsive">
        <table class="table table-bordered table-hover shadow">
            <thead class="table-success">
                <tr>
                    <th>Facility</th>
                    <th>Date</th>
                    <th>Time Slot</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php
            // generate available time slots for the next 7 days
            for ($i = 0; $i < 7; $i++) {
                $date = date('Y-m-d', strtotime("+$i days"));
                $display_date = date('d M Y (D)', strtotime($date));

                foreach ($facilities as $facility) {

                    $icon = "🏟️";
                    if ($facility['facility_name'] == "Badminton Court") $icon = "🏸";
                    elseif ($facility['facility_name'] == "Basketball Court") $icon = "🏀";
                    elseif ($facility['facility_name'] == "Football Field") $icon = "⚽";
                    elseif ($facility['facility_name'] == "Swimming Pool") $icon = "🏊‍♀️";
                    elseif ($facility['facility_name'] == "Tennis Court") $icon = "🎾";
                    elseif ($facility['facility_name'] == "Gymnasium") $icon = "🏋️";

                    foreach ($time_slots as $time_db => $time_display) {

                        // check is it booked
                        $check_sql = "
                            SELECT booking_id 
                            FROM bookings 
                            WHERE facility_id = '{$facility['facility_id']}' 
                            AND booking_date = '$date' 
                            AND start_time = '$time_db'
                            AND status IN ('Pending', 'Approved')
                        ";
                        $check_result = mysqli_query($conn, $check_sql);
                        $is_booked = mysqli_num_rows($check_result) > 0;

                        // calculate end time for display
                        $end_time_display = date("g:i A", strtotime("+2 hours", strtotime($time_display)));
            ?>

                <tr>
                    <td><?= $icon ?> <?= htmlspecialchars($facility['facility_name']) ?></td>
                    <td><?= $display_date ?></td>
                    <td><?= $time_display ?> - <?= $end_time_display ?></td>
                    <td>
                        <?php if ($is_booked): ?>
                            <span class="badge bg-danger">Booked</span>
                        <?php else: ?>
                            <span class="badge bg-success">Available</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($is_booked): ?>
                            <span class="text-muted">-</span>
                        <?php else: ?>
                            <a href="booking.php?facility=<?= urlencode($facility['facility_name']) ?>&date=<?= $date ?>&time=<?= urlencode($time_display) ?>" 
                               class="btn btn-sm btn-success">
                               Book
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>

            <?php
                    }
                }
            }
            ?>

            </tbody>
        </table>
    </div>

</section>

<?php
include "includes/footer.php";
?>