<?php

include 'config.php';
include 'classes.php';

$submitted = false;
$booking_id = null;
$hotels = [];
$errors = [];

$hotel_query = "SELECT hotel_id, hotel_name, city FROM hotels ORDER BY hotel_name";
$hotel_result = $conn->query($hotel_query);

if ($hotel_result->num_rows > 0) {
    while ($row = $hotel_result->fetch_assoc()) {
        $hotels[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotel_id = (int)($_POST['hotel_id'] ?? 0);
    $first_name = $conn->real_escape_string($_POST['first_name'] ?? '');
    $last_name = $conn->real_escape_string($_POST['last_name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $check_in = $conn->real_escape_string($_POST['check_in_date'] ?? '');
    $check_out = $conn->real_escape_string($_POST['check_out_date'] ?? '');
    $adults = (int)($_POST['adults'] ?? 1);
    $children = (int)($_POST['children'] ?? 0);
    $rooms = (int)($_POST['rooms'] ?? 1);
    $special_requests = $conn->real_escape_string($_POST['special_requests'] ?? '');
    $confirmation_method = $conn->real_escape_string($_POST['confirmation_method'] ?? '');
    
    if (empty($first_name)) $errors[] = 'First name is required.';
    if (empty($last_name)) $errors[] = 'Last name is required.';
    if (empty($email)) $errors[] = 'Email is required.';
    if (empty($check_in)) $errors[] = 'Check-in date is required.';
    if (empty($check_out)) $errors[] = 'Check-out date is required.';
    if ($hotel_id == 0) $errors[] = 'Please select a hotel.';
    
    if (empty($errors)) {
        $checkin_date = new DateTime($check_in);
        $checkout_date = new DateTime($check_out);
        $nights = $checkout_date->diff($checkin_date)->days;
        
        $price_query = "SELECT price_per_night FROM hotels WHERE hotel_id = $hotel_id";
        $price_result = $conn->query($price_query);
        $price_row = $price_result->fetch_assoc();
        $total_price = $price_row['price_per_night'] * $nights * $rooms;
        
        $insert_query = "INSERT INTO bookings 
                        (hotel_id, first_name, last_name, email, phone, check_in_date, check_out_date, 
                         number_of_adults, number_of_children, number_of_rooms, special_requests, 
                         confirmation_method, total_price, booking_status) 
                        VALUES 
                        ($hotel_id, '$first_name', '$last_name', '$email', '$phone', '$check_in', '$check_out', 
                         $adults, $children, $rooms, '$special_requests', '$confirmation_method', $total_price, 'confirmed')";
        
        if ($conn->query($insert_query)) {
            $booking_id = $conn->insert_id;
            $submitted = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Booking - Booklify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="index.html">
                <img src="images/booklify.png" alt="Booklify Logo" class="me-2" style="height: 48px;">
                <span class="fw-bold text-primary">Booklify</span>
            </a>
        </div>
    </nav>

    <main>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="bg-primary text-white text-center p-4 rounded mb-4">
                        <h1 class="display-5 fw-bold mb-2"><i class="bi bi-plus-circle me-2"></i>Add New Booking</h1>
                        <p class="lead mb-0">Insert a new booking record into the system</p>
                    </div>

                    <?php if (!$submitted): ?>
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong><i class="bi bi-exclamation-triangle me-2"></i>Errors Found:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Booking Details</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="hotel_id" class="form-label">Hotel <span class="text-danger">*</span></label>
                                            <select class="form-select" id="hotel_id" name="hotel_id" required>
                                                <option value="">Select Hotel</option>
                                                <?php foreach ($hotels as $hotel): ?>
                                                    <option value="<?php echo $hotel['hotel_id']; ?>">
                                                        <?php echo htmlspecialchars($hotel['hotel_name'] . ' - ' . $hotel['city']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="confirmation_method" class="form-label">Confirmation Method</label>
                                            <select class="form-select" id="confirmation_method" name="confirmation_method">
                                                <option value="email">Email</option>
                                                <option value="sms">SMS</option>
                                                <option value="both">Both</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="check_in_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="check_in_date" name="check_in_date" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="check_out_date" class="form-label">Check-out Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="check_out_date" name="check_out_date" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="adults" class="form-label">Adults</label>
                                            <input type="number" class="form-control" id="adults" name="adults" value="1" min="1">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="children" class="form-label">Children</label>
                                            <input type="number" class="form-control" id="children" name="children" value="0" min="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="rooms" class="form-label">Rooms</label>
                                            <input type="number" class="form-control" id="rooms" name="rooms" value="1" min="1">
                                        </div>

                                        <div class="col-12">
                                            <label for="special_requests" class="form-label">Special Requests</label>
                                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3"></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-plus-circle me-2"></i>Create Booking
                                            </button>
                                            <a href="index.html" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Booking Created Successfully!</strong> Booking ID: #<?php echo $booking_id; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <div class="text-center">
                            <a href="search_bookings.php" class="btn btn-primary"><i class="bi bi-list me-2"></i>View All Bookings</a>
                            <a href="insert_booking.php" class="btn btn-secondary"><i class="bi bi-plus-circle me-2"></i>Add Another</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2025 booklify.com. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
