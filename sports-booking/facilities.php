<?php

include "includes/session.php";  // Session check
include "includes/db.php";
include "includes/header.php";

?>


<section class="container py-5">
    


<h1 class="text-center mb-5">

Available Sports Facilities

</h1>



<div class="row">


<?php


$sql = "SELECT * FROM facilities WHERE status='Available'";


$result = mysqli_query($conn, $sql);



if(!$result){

    die("Database Error: " . mysqli_error($conn));

}



while($facility = mysqli_fetch_assoc($result)){

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

<?= $facility['facility_name']; ?>

</h5>



<p>

<?= $facility['location']; ?>

</p>



<p>

<b>Status:</b>

<?= $facility['status']; ?>

</p>



<a href="booking.php"
class="btn btn-success">

Book Now

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

include "includes/footer.php";

?>