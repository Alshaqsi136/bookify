<?php

include 'config.php';
include 'classes.php';

$search_performed = false;
$hotels = [];
$search_city = '';
$search_type = '';
$search_min_price = '';
$search_max_price = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['search'])) {
    $search_performed = true;
    
    $search_city = $conn->real_escape_string($_POST['city'] ?? $_GET['city'] ?? '');
    $search_type = $conn->real_escape_string($_POST['type'] ?? $_GET['type'] ?? '');
    $search_min_price = isset($_POST['min_price']) || isset($_GET['min_price']) ? (float)($_POST['min_price'] ?? $_GET['min_price']) : '';
    $search_max_price = isset($_POST['max_price']) || isset($_GET['max_price']) ? (float)($_POST['max_price'] ?? $_GET['max_price']) : '';
    
    $query = "SELECT * FROM hotels WHERE 1=1";
    
    if (!empty($search_city)) {
        $query .= " AND (city LIKE '%$search_city%' OR hotel_name LIKE '%$search_city%')";
    }
    
    if (!empty($search_type)) {
        $query .= " AND hotel_type = '$search_type'";
    }
    
    if (!empty($search_min_price)) {
        $query .= " AND price_per_night >= $search_min_price";
    }
    
    if (!empty($search_max_price)) {
        $query .= " AND price_per_night <= $search_max_price";
    }
    
    $query .= " ORDER BY hotel_name ASC";
    
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $hotel = new Hotel(
                $row['hotel_id'],
                $row['hotel_name'],
                $row['city'],
                $row['country'],
                $row['hotel_type'],
                $row['star_rating'],
                $row['price_per_night'],
                $row['description'],
                $row['amenities']
            );
            $hotels[] = $hotel;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Hotels - Booklify</title>
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
                    <li class="nav-item"><a class="nav-link" href="hotels.html">Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="booking.html">My Booking</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="bg-primary text-white text-center p-4 rounded mb-4">
                        <h1 class="display-5 fw-bold mb-2"><i class="bi bi-search me-2"></i>Search Hotels</h1>
                        <p class="lead mb-0">Find the perfect hotel for your stay</p>
                    </div>

                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Search Criteria</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="city" class="form-label">City or Hotel Name</label>
                                        <input type="text" class="form-control" id="city" name="city" 
                                               placeholder="e.g., Muscat, Salalah" value="<?php echo htmlspecialchars($search_city); ?>">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="type" class="form-label">Hotel Type</label>
                                        <select class="form-select" id="type" name="type">
                                            <option value="">All Types</option>
                                            <option value="luxury" <?php echo $search_type === 'luxury' ? 'selected' : ''; ?>>Luxury</option>
                                            <option value="business" <?php echo $search_type === 'business' ? 'selected' : ''; ?>>Business</option>
                                            <option value="budget" <?php echo $search_type === 'budget' ? 'selected' : ''; ?>>Budget</option>
                                            <option value="resort" <?php echo $search_type === 'resort' ? 'selected' : ''; ?>>Resort</option>
                                            <option value="boutique" <?php echo $search_type === 'boutique' ? 'selected' : ''; ?>>Boutique</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="min_price" class="form-label">Min Price ($)</label>
                                        <input type="number" class="form-control" id="min_price" name="min_price" 
                                               placeholder="0" value="<?php echo htmlspecialchars($search_min_price); ?>" min="0" step="10">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="max_price" class="form-label">Max Price ($)</label>
                                        <input type="number" class="form-control" id="max_price" name="max_price" 
                                               placeholder="1000" value="<?php echo htmlspecialchars($search_max_price); ?>" min="0" step="10">
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

                    <?php if ($search_performed): ?>
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="bi bi-list me-2"></i>
                                    Search Results (<?php echo count($hotels); ?> hotels found)
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (count($hotels) > 0): ?>
                                    <div class="table-responsive">
                                        <?php displayHotelsTable($hotels); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        No hotels found matching your search criteria. Please try different filters.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-4">
                        <a href="booking.html" class="btn btn-primary"><i class="bi bi-calendar-check me-2"></i>Book Now</a>
                        <a href="index.html" class="btn btn-secondary"><i class="bi bi-house me-2"></i>Back Home</a>
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
