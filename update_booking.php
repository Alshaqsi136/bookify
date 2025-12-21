<?php

include 'config.php';
include 'classes.php';

$booking_data = null;
$update_success = false;
$update_error = '';
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($booking_id) {
    $query = "SELECT * FROM bookings WHERE booking_id = $booking_id";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $booking_data = $result->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $booking_id) {
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
    $booking_status = $conn->real_escape_string($_POST['booking_status'] ?? 'confirmed');
    
    // Calculate total price
    $checkin_date = new DateTime($check_in);
    $checkout_date = new DateTime($check_out);
    $nights = $checkout_date->diff($checkin_date)->days;
    
    $hotel_id = $booking_data['hotel_id'];
    $price_query = "SELECT price_per_night FROM hotels WHERE hotel_id = $hotel_id";
    $price_result = $conn->query($price_query);
    $price_row = $price_result->fetch_assoc();
    $total_price = $price_row['price_per_night'] * $nights * $rooms;
    
    // Update booking
    $update_query = "UPDATE bookings SET 
                    first_name = '$first_name',
                    last_name = '$last_name',
                    email = '$email',
                    phone = '$phone',
                    check_in_date = '$check_in',
                    check_out_date = '$check_out',
                    number_of_adults = $adults,
                    number_of_children = $children,
                    number_of_rooms = $rooms,
                    special_requests = '$special_requests',
                    confirmation_method = '$confirmation_method',
                    total_price = $total_price,
                    booking_status = '$booking_status'
                    WHERE booking_id = $booking_id";
    
    if ($conn->query($update_query)) {
        $update_success = true;
        // Reload the data
        $result = $conn->query("SELECT * FROM bookings WHERE booking_id = $booking_id");
        $booking_data = $result->fetch_assoc();
    } else {
        $update_error = "Error updating booking: " . $conn->error;
    }
}

// Get all bookings for reference
$all_bookings = [];
$all_query = "SELECT booking_id, first_name, last_name, email FROM bookings ORDER BY created_at DESC LIMIT 20";
$all_result = $conn->query($all_query);

if ($all_result && $all_result->num_rows > 0) {
    while ($row = $all_result->fetch_assoc()) {
        $all_bookings[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Booking - Booklify</title>
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
                <div class="col-lg-10">
                    <div class="bg-warning text-dark text-center p-4 rounded mb-4">
                        <h1 class="display-5 fw-bold mb-2"><i class="bi bi-pencil-square me-2"></i>Update Booking</h1>
                        <p class="lead mb-0">Modify booking details</p>
                    </div>

                    <?php if (!$booking_data): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-list me-2"></i>Select Booking to Update</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($all_bookings)): ?>
                                    <p class="alert alert-info">No bookings found in the system.</p>
                                <?php else: ?>
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Guest Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($all_bookings as $booking): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                                    <td>
                                                        <a href="?id=<?php echo $booking['booking_id']; ?>" class="btn btn-sm btn-warning">
                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php else: ?>
                        <?php if ($update_success): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="bi bi-check-circle me-2"></i>
                                <strong>Booking Updated!</strong> The booking has been successfully updated.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($update_error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Error:</strong> <?php echo htmlspecialchars($update_error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Booking #<?php echo htmlspecialchars($booking_data['booking_id']); ?> Details</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" 
                                                   value="<?php echo htmlspecialchars($booking_data['first_name']); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" 
                                                   value="<?php echo htmlspecialchars($booking_data['last_name']); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" 
                                                   value="<?php echo htmlspecialchars($booking_data['email']); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="tel" class="form-control" id="phone" name="phone" 
                                                   value="<?php echo htmlspecialchars($booking_data['phone']); ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="check_in_date" class="form-label">Check-in Date</label>
                                            <input type="date" class="form-control" id="check_in_date" name="check_in_date" 
                                                   value="<?php echo htmlspecialchars($booking_data['check_in_date']); ?>" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="check_out_date" class="form-label">Check-out Date</label>
                                            <input type="date" class="form-control" id="check_out_date" name="check_out_date" 
                                                   value="<?php echo htmlspecialchars($booking_data['check_out_date']); ?>" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="adults" class="form-label">Adults</label>
                                            <input type="number" class="form-control" id="adults" name="adults" 
                                                   value="<?php echo htmlspecialchars($booking_data['number_of_adults']); ?>" min="1">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="children" class="form-label">Children</label>
                                            <input type="number" class="form-control" id="children" name="children" 
                                                   value="<?php echo htmlspecialchars($booking_data['number_of_children']); ?>" min="0">
                                        </div>

                                        <div class="col-md-4">
                                            <label for="rooms" class="form-label">Rooms</label>
                                            <input type="number" class="form-control" id="rooms" name="rooms" 
                                                   value="<?php echo htmlspecialchars($booking_data['number_of_rooms']); ?>" min="1">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="confirmation_method" class="form-label">Confirmation Method</label>
                                            <select class="form-select" id="confirmation_method" name="confirmation_method">
                                                <option value="email" <?php echo $booking_data['confirmation_method'] === 'email' ? 'selected' : ''; ?>>Email</option>
                                                <option value="sms" <?php echo $booking_data['confirmation_method'] === 'sms' ? 'selected' : ''; ?>>SMS</option>
                                                <option value="both" <?php echo $booking_data['confirmation_method'] === 'both' ? 'selected' : ''; ?>>Both</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="booking_status" class="form-label">Status</label>
                                            <select class="form-select" id="booking_status" name="booking_status">
                                                <option value="confirmed" <?php echo $booking_data['booking_status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                <option value="pending" <?php echo $booking_data['booking_status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="cancelled" <?php echo $booking_data['booking_status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <label for="special_requests" class="form-label">Special Requests</label>
                                            <textarea class="form-control" id="special_requests" name="special_requests" rows="3"><?php echo htmlspecialchars($booking_data['special_requests']); ?></textarea>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>Save Changes
                                            </button>
                                            <a href="update_booking.php" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="text-center my-4">
                        <a href="search_bookings.php" class="btn btn-primary"><i class="bi bi-list me-2"></i>View All Bookings</a>
                        <a href="insert_booking.php" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i>Add Booking</a>
                        <a href="index.html" class="btn btn-secondary"><i class="bi bi-house me-2"></i>Home</a>
                    </div>
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
