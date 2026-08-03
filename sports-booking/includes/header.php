<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Campus Sports Booking System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<!-- Navigation Bar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">

    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            Campus Sports
        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">


                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="facilities.php">
                        Facilities
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="schedule.php">
                        Schedule
                    </a>
                </li>

                <li class="nav-item">
                     <a class="nav-link fs-6 fw-medium" href="booking.php">
                        Booking
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fs-6 fw-medium" href="contact.php">
                        Contact
                    </a>
                </li>

                <li class="nav-item dropdown ms-lg-3">

                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       data-bs-offset="0, 8"> 

                        <!-- Profile Circle -->
                        <img src="images/profile.png"
                             class="rounded-circle me-2"
                             width="38"
                             height="38"
                             alt="Profile">

                        
                        <div class="text-white fs-6 lh-sm text-start me-1">
                            <span class="d-block fw-light text-white-50">Welcome,</span>
                            <span class="d-block fw-semibold text-white">
                                <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; ?>
                            </span>
                        </div>

                    </a>


                    
                    <ul class="dropdown-menu dropdown-menu-center">


                        <li>
                            <a class="dropdown-item" href="user.php">
                                User Profile
                            </a>
                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <li>
                            <a class="dropdown-item text-danger"
                               href="logout.php">
                                Logout
                            </a>
                        </li>


                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>

<!-- Main Content -->

<main>