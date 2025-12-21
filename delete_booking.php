<?php

include 'config.php';
include 'classes.php';

$bookings = [];
$search_email = '';
$delete_success = false;
$delete_error = '';

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    $delete_query = "DELETE FROM bookings WHERE booking_id = $delete_id";
    
    if ($conn->query($delete_query)) {
        $delete_success = true;
    } else {
        $delete_error = "Error deleting booking: " . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_GET['search'])) {
    $search_email = $conn->real_escape_string($_POST['search_email'] ?? $_GET['search_email'] ?? '');
    
    if (!empty($search_email)) {
        $query = "SELECT * FROM bookings WHERE email LIKE '%$search_email%' ORDER BY created_at DESC";
    } else {
        $query = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 20";
    }
} else {
    $query = "SELECT * FROM bookings ORDER BY created_at DESC LIMIT 20";
}

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
        $bookings[] = $row; // Store raw data for delete links
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking - Booklify</title>
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
                <div class="col-lg-12">
                    <div class="bg-danger text-white text-center p-4 rounded mb-4">
                        <h1 class="display-5 fw-bold mb-2"><i class="bi bi-trash me-2"></i>Delete Booking</h1>
                        <p class="lead mb-0">Remove bookings from the system</p>
                    </div>

                    <?php if ($delete_success): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Booking Deleted!</strong> The booking has been successfully removed from the system.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($delete_error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> <?php echo htmlspecialchars($delete_error); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Search Bookings</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label for="search_email" class="form-label">Search by Email</label>
                                        <input type="email" class="form-control" id="search_email" name="search_email" 
                                               placeholder="Enter email to search" value="<?php echo htmlspecialchars($search_email); ?>">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
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
                            <h5 class="mb-0"><i class="bi bi-list me-2"></i>Bookings (<?php echo count($bookings); ?> found)</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($bookings)): ?>
                                <p class="alert alert-info">No bookings found.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>ID</th>
                                                <th>Guest</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Check-in</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bookings as $booking): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['check_in_date']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $booking['booking_status'] === 'confirmed' ? 'success' : 'warning'; ?>">
                                                            <?php echo htmlspecialchars($booking['booking_status']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="?delete_id=<?php echo $booking['booking_id']; ?>" 
                                                           class="btn btn-sm btn-danger"
                                                           onclick="return confirm('Are you sure you want to delete this booking?');">
                                                            <i class="bi bi-trash me-1"></i>Delete
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

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
