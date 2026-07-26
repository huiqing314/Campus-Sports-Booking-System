<?php

include "includes/header.php";

?>


<section class="container py-5">


<div class="row justify-content-center">


<div class="col-md-8">


<div class="card shadow p-4">


<h2 class="text-center mb-4">

Book Sports Facility

</h2>



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


<option>

Badminton Court

</option>


<option>

Basketball Court

</option>


<option>

Football Field

</option>


<option>

Swimming Pool

</option>


<option>

Tennis Court

</option>


<option>

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


<option>

8:00 AM

</option>


<option>

10:00 AM

</option>


<option>

12:00 PM

</option>


<option>

2:00 PM

</option>


<option>

4:00 PM

</option>


<option>

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
value="1"
required>


</div>





<button type="submit"
class="btn btn-success w-100">


Submit Booking


</button>



</form>


</div>


</div>


</div>


</section>




<?php

include "includes/footer.php";

?>