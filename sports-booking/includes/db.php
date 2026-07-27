<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "campus_sports_booking_system"
);


if (!$conn) {

    die("Database Connection Failed: " . mysqli_connect_error());

}

?>