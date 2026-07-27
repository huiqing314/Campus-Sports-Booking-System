<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "campus_sports_booking_system"
);


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>