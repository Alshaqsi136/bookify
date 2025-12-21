<?php

include 'config.php';
include 'classes.php';

$search_performed = false;
$bookings = [];
$search_email = '';
$search_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['search'])) {
    $search_performed = true;
    
    $search_email = $conn->real_escape_string($_POST['email'] ?? $_GET['email'] ?? '');
    $search_status = $conn->real_escape_string($_POST['status'] ?? $_GET['status'] ?? '');
    
    $query = "SELECT * FROM bookings WHERE 1=1";
    
    if (!empty($search_email)) {
        $query .= " AND email LIKE '%$search_email%'";
    }
    
    if (!empty($search_status)) {
        $query .= " AND booking_status = '$search_status'";
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
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
} else {
    // Show all bookings by default
    $query = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 50";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bookings - Booklify</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="booking.html">New Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="insert_booking.php">Admin Panel</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="bg-primary text-white text-center p-4 rounded mb-4">
                        <h1 class="display-5 fw-bold mb-2"><i class="bi bi-search me-2"></i>Search Bookings</h1>
                        <p class="lead mb-0">Find and manage bookings in the system</p>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Search Criteria</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Search by Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               placeholder="guest@example.com" value="<?php echo htmlspecialchars($search_email); ?>">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Booking Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">All Status</option>
                                            <option value="confirmed" <?php echo $search_status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="pending" <?php echo $search_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="cancelled" <?php echo $search_status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bi bi-search me-2"></i>Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0">
                                        <i class="bi bi-list me-2"></i>Bookings (<?php echo count($bookings); ?> found)
                                    </h5>
                                </div>
                                <div class="col-auto">
                                    <a href="insert_booking.php" class="btn btn-sm btn-success">
                                        <i class="bi bi-plus-circle me-1"></i>Add Booking
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (count($bookings) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Guest</th>
                                                <th>Email</th>
                                                <th>Dates</th>
                                                <th>Guests</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bookings as $booking): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($booking->getBookingId()); ?></td>
                                                    <td><?php echo htmlspecialchars($booking->getFirstName() . ' ' . $booking->getLastName()); ?></td>
                                                    <td><?php echo htmlspecialchars($booking->getEmail()); ?></td>
                                                    <td><?php echo htmlspecialchars($booking->getCheckInDate() . ' to ' . $booking->getCheckOutDate()); ?></td>
                                                    <td><?php echo $booking->getNumberOfAdults() . ' adults, ' . $booking->getNumberOfChildren() . ' children'; ?></td>
                                                    <td>$<?php echo number_format($booking->getTotalPrice(), 2); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $booking->getBookingStatus() === 'confirmed' ? 'success' : 'warning'; ?>">
                                                            <?php echo htmlspecialchars($booking->getBookingStatus()); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="update_booking.php?id=<?php echo $booking->getBookingId(); ?>" class="btn btn-sm btn-warning">
                                                            <i class="bi bi-pencil me-1"></i>Edit
                                                        </a>
                                                        <a href="delete_booking.php?delete_id=<?php echo $booking->getBookingId(); ?>" class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Are you sure?');">
                                                            <i class="bi bi-trash me-1"></i>Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    No bookings found. <a href="insert_booking.php">Create a new booking</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="text-center my-4">
                        <a href="search_hotels.php" class="btn btn-primary"><i class="bi bi-search me-2"></i>Search Hotels</a>
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
