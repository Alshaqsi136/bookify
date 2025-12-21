<?php

include 'config.php';

$hotels_query = "SELECT COUNT(*) as count FROM hotels";
$bookings_query = "SELECT COUNT(*) as count FROM bookings";
$feedback_query = "SELECT COUNT(*) as count FROM feedback";
$contacts_query = "SELECT COUNT(*) as count FROM contacts";

$hotels_count = $conn->query($hotels_query)->fetch_assoc()['count'];
$bookings_count = $conn->query($bookings_query)->fetch_assoc()['count'];
$feedback_count = $conn->query($feedback_query)->fetch_assoc()['count'];
$contacts_count = $conn->query($contacts_query)->fetch_assoc()['count'];

$recent_bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5");
$recent_feedback = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC LIMIT 5");
$recent_contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Booklify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .stat-card-hotels { border-left-color: #0d6efd; }
        .stat-card-bookings { border-left-color: #198754; }
        .stat-card-feedback { border-left-color: #ffc107; }
        .stat-card-contacts { border-left-color: #0dcaf0; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="admin_dashboard.php">
                <i class="bi bi-speedometer2 me-2"></i>Booklify Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="search_hotels.php">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="search_bookings.php">Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.html">Public Site</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <!-- Statistics Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card stat-card-hotels shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total Hotels</p>
                                        <h3 class="mb-0"><?php echo $hotels_count; ?></h3>
                                    </div>
                                    <i class="bi bi-hotel text-primary fs-3"></i>
                                </div>
                            </div>
                            <a href="search_hotels.php" class="card-footer bg-white text-decoration-none text-primary small">
                                View Details <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card stat-card-bookings shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Total Bookings</p>
                                        <h3 class="mb-0"><?php echo $bookings_count; ?></h3>
                                    </div>
                                    <i class="bi bi-calendar-check text-success fs-3"></i>
                                </div>
                            </div>
                            <a href="search_bookings.php" class="card-footer bg-white text-decoration-none text-success small">
                                Manage Bookings <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card stat-card-feedback shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Feedback Records</p>
                                        <h3 class="mb-0"><?php echo $feedback_count; ?></h3>
                                    </div>
                                    <i class="bi bi-chat-left-heart text-warning fs-3"></i>
                                </div>
                            </div>
                            <a href="#" class="card-footer bg-white text-decoration-none text-warning small">
                                View Feedback <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card stat-card-contacts shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <p class="text-muted small mb-1">Contact Messages</p>
                                        <h3 class="mb-0"><?php echo $contacts_count; ?></h3>
                                    </div>
                                    <i class="bi bi-envelope-check text-info fs-3"></i>
                                </div>
                            </div>
                            <a href="process_contact.php" class="card-footer bg-white text-decoration-none text-info small">
                                View Messages <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                <!-- Quick Actions -->
                <div class="row g-4 mb-5">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <a href="insert_booking.php" class="btn btn-success btn-lg w-100">
                                            <i class="bi bi-plus-circle me-2"></i>Add New Booking
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="search_bookings.php" class="btn btn-primary btn-lg w-100">
                                            <i class="bi bi-search me-2"></i>Search Bookings
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="update_booking.php" class="btn btn-warning btn-lg w-100">
                                            <i class="bi bi-pencil-square me-2"></i>Update Booking
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="delete_booking.php" class="btn btn-danger btn-lg w-100">
                                            <i class="bi bi-trash me-2"></i>Delete Booking
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>System Info</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <strong>Database:</strong><br>
                                        <small class="text-muted">booklify</small>
                                    </li>
                                    <li class="mb-3">
                                        <strong>Tables:</strong><br>
                                        <small class="text-muted">4 (hotels, bookings, feedback, contacts)</small>
                                    </li>
                                    <li class="mb-3">
                                        <strong>Last Updated:</strong><br>
                                        <small class="text-muted" id="update-time">Just now</small>
                                    </li>
                                    <li>
                                        <strong>Status:</strong><br>
                                        <span class="badge bg-success">Online</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="row g-4 mb-5">
                    <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Recent Bookings</h5>
                                    <a href="search_bookings.php" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>ID</th>
                                                <th>Guest</th>
                                                <th>Email</th>
                                                <th>Check-in</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if ($recent_bookings->num_rows > 0) {
                                                while ($row = $recent_bookings->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . $row['booking_id'] . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . $row['check_in_date'] . "</td>";
                                                    echo "<td><span class='badge bg-" . ($row['booking_status'] === 'confirmed' ? 'success' : 'warning') . "'>" . $row['booking_status'] . "</span></td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
