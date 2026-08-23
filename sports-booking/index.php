<?php  //全部ok了 

require_once __DIR__ . '/includes/session.php';


include("includes/db.php");
include("includes/header.php");


?>




<!-- Hero -->


<section class="hero">


<div class="container text-center">



<h1>
Campus Sports & Facilities Amenities
</h1>



<p>
Reserve your favourite sports facilities online anytime,
anywhere with TAR UMT Sports Booking System.
</p>




<a href="booking.php" 
class="btn btn-warning btn-lg">


Book Now


</a>



</div>


</section>






<!-- Facilities -->



<section class="container py-5">



<h2 class="text-center mb-5">


Available Facilities


</h2>





<div class="row">




<?php



// Get facilities from database


$sql = "SELECT * FROM facilities WHERE status='Available'";



$result = mysqli_query($conn, $sql);




if(!$result){


    die("Database Error: " . mysqli_error($conn));


}





while($facility = mysqli_fetch_assoc($result))


{

    // Set icon based on facility name
    $icon = "🏟️"; // default

    if ($facility["facility_name"] == "Badminton Court") {
        $icon = "🏸";
    } elseif ($facility["facility_name"] == "Basketball Court") {
        $icon = "🏀";
    } elseif ($facility["facility_name"] == "Football Field") {
        $icon = "⚽";
    } elseif ($facility["facility_name"] == "Swimming Pool") {
        $icon = "🏊‍♀️";
    } elseif ($facility["facility_name"] == "Tennis Court") {
        $icon = "🎾";
    } elseif ($facility["facility_name"] == "Gymnasium") {
        $icon = "🏋️";
    }

?>




<div class="col-md-4 mb-4">



<div class="card h-100 shadow facility-card">



<div class="card-body text-center">



<h1>


<?= $icon; ?>


</h1>




<h5>


<?= $facility["facility_name"]; ?>


</h5>





<p>


Location:


<?= $facility["location"]; ?>


</p>




<p>


Status:


<?= $facility["status"]; ?>


</p>





<a href="booking.php" 
class="btn btn-success">


Book


</a>




</div>



</div>



</div>




<?php


}


?>




</div>



</section>





<?php


include("includes/footer.php");


?>