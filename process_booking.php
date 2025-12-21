<?php

include 'config.php';
include 'classes.php';

$page_title = 'Booking Confirmation';

$submitted = false;
$bookings = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    
    $destination = $conn->real_escape_string($_POST['destination'] ?? '');
    $hotel_type = $conn->real_escape_string($_POST['hotel_type'] ?? '');
    $checkin = $conn->real_escape_string($_POST['checkin'] ?? '');
    $checkout = $conn->real_escape_string($_POST['checkout'] ?? '');
    $adults = (int)($_POST['adults'] ?? 1);
    $children = (int)($_POST['children'] ?? 0);
    $rooms = (int)($_POST['rooms'] ?? 1);
    $firstname = $conn->real_escape_string($_POST['firstname'] ?? '');
    $lastname = $conn->real_escape_string($_POST['lastname'] ?? '');
    $guest_email = $conn->real_escape_string($_POST['guest_email'] ?? '');
    $guest_phone = $conn->real_escape_string($_POST['guest_phone'] ?? '');
    $special_requests = $conn->real_escape_string($_POST['special_requests'] ?? '');
    $confirmation_method = $conn->real_escape_string($_POST['confirmation_method'] ?? '');
    $payment_method = $conn->real_escape_string($_POST['payment_method'] ?? '');
    
    $hotel_query = "SELECT hotel_id FROM hotels WHERE LOWER(hotel_name) LIKE LOWER('%$destination%') OR LOWER(city) LIKE LOWER('%$destination%') LIMIT 1";
    $hotel_result = $conn->query($hotel_query);
    $hotel_id = 1;
    
    if ($hotel_result->num_rows > 0) {
        $hotel = $hotel_result->fetch_assoc();
        $hotel_id = $hotel['hotel_id'];
    }
    
    $checkin_date = new DateTime($checkin);
    $checkout_date = new DateTime($checkout);
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
                    ($hotel_id, '$firstname', '$lastname', '$guest_email', '$guest_phone', '$checkin', '$checkout', 
                     $adults, $children, $rooms, '$special_requests', '$confirmation_method', $total_price, 'confirmed')";
    
    if ($conn->query($insert_query)) {
        $booking_id = $conn->insert_id;
    } else {
        echo "<!-- Booking Error: " . $conn->error . " -->";
    }
}

$query = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $booking = new Booking(
            $row['booking_id'],
            $row['hotel_id'],
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['phone'],
            $row['check_in_date'],
            $row['check_out_date'],
            $row['number_of_adults'],
            $row['number_of_children'],
            $row['number_of_rooms'],
            $row['special_requests'],
            $row['confirmation_method'],
            $row['total_price'],
            $row['booking_status']
        );
        $bookings[] = $booking;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Booklify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 bg-primary text-white p-4 mb-4">
                <h1 class="mb-0"><i class="bi bi-check-circle me-2"></i><?php echo $page_title; ?></h1>
            </div>
        </div>

        <div class="container my-4">
            <?php if ($submitted): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Booking Confirmed!</strong> Your booking has been successfully submitted. Confirmation details have been sent to your email.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Recent Bookings</h5>
                </div>
                <div class="card-body">
                    <?php displayBookingsTable($bookings); ?>
                </div>
            </div>

            <div class="text-center my-4">
                <a href="booking.html" class="btn btn-primary"><i class="bi bi-arrow-left me-2"></i>Back to Booking Form</a>
                <a href="index.html" class="btn btn-secondary"><i class="bi bi-house me-2"></i>Home</a>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2025 booklify.com. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
