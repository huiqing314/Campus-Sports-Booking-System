<?php

include "sports-booking/includes/header.php";

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



<a href="sports-booking/booking.php" 
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


$facilities = [

[
"icon"=>"🏸",
"name"=>"Badminton Court",
"description"=>"Indoor badminton courts with excellent lighting."
],


[
"icon"=>"🏀",
"name"=>"Basketball Court",
"description"=>"Outdoor basketball courts for students."
],


[
"icon"=>"⚽",
"name"=>"Football Field",
"description"=>"Full-size football field with quality grass."
],


[
"icon"=>"🏊",
"name"=>"Swimming Pool",
"description"=>"Olympic-size swimming pool."
],


[
"icon"=>"🎾",
"name"=>"Tennis Court",
"description"=>"Professional outdoor tennis courts."
],


[
"icon"=>"🏋",
"name"=>"Gymnasium",
"description"=>"Modern fitness equipment for students."
]


];



foreach($facilities as $facility)

{


?>


<div class="col-md-4 mb-4">


<div class="card h-100 shadow facility-card">


<div class="card-body text-center">


<h1>

<?= $facility["icon"]; ?>

</h1>


<h5>

<?= $facility["name"]; ?>

</h5>



<p>

<?= $facility["description"]; ?>

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

include "sports-booking/includes/footer.php";

?>