<?php
include "includes/header.php";
?>

<div class="container py-5">

    <h1 class="text-center mb-4">
        Facility Schedule
    </h1>

    <p class="text-center text-muted mb-5">
        View available time slots for campus sports facilities.
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

                <tr>
                    <td>🏸 Badminton Court</td>
                    <td>2026-08-01</td>
                    <td>8:00 AM - 10:00 AM</td>
                    <td>
                        <span class="badge bg-success">
                            Available
                        </span>
                    </td>
                    <td>
                        <a href="booking.php" class="btn btn-sm btn-success">
                            Book
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>🏸 Badminton Court</td>
                    <td>2026-08-01</td>
                    <td>10:00 AM - 12:00 PM</td>
                    <td>
                        <span class="badge bg-danger">
                            Booked
                        </span>
                    </td>
                    <td>
                        -
                    </td>
                </tr>

                <tr>
                    <td>🏀 Basketball Court</td>
                    <td>2026-08-01</td>
                    <td>2:00 PM - 4:00 PM</td>
                    <td>
                        <span class="badge bg-success">
                            Available
                        </span>
                    </td>
                    <td>
                        <a href="booking.php" class="btn btn-sm btn-success">
                            Book
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>⚽ Football Field</td>
                    <td>2026-08-02</td>
                    <td>4:00 PM - 6:00 PM</td>
                    <td>
                        <span class="badge bg-success">
                            Available
                        </span>
                    </td>
                    <td>
                        <a href="booking.php" class="btn btn-sm btn-success">
                            Book
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>🏊 Swimming Pool</td>
                    <td>2026-08-02</td>
                    <td>9:00 AM - 11:00 AM</td>
                    <td>
                        <span class="badge bg-danger">
                            Booked
                        </span>
                    </td>
                    <td>
                        -
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

<?php
include "includes/footer.php";
?>