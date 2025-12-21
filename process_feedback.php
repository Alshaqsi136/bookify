<?php

include 'config.php';
include 'classes.php';

$page_title = 'Feedback Submitted';

$submitted = false;
$feedback_items = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    
    $feedbackName = $conn->real_escape_string($_POST['feedbackName'] ?? '');
    $feedbackEmail = $conn->real_escape_string($_POST['feedbackEmail'] ?? '');
    $feedbackPhone = $conn->real_escape_string($_POST['feedbackPhone'] ?? '');
    $bookingRef = $conn->real_escape_string($_POST['bookingRef'] ?? NULL);
    $satisfaction = (int)($_POST['satisfaction'] ?? 0);
    $hearAbout = $conn->real_escape_string($_POST['hearAbout'] ?? '');
    
    $features = isset($_POST['features']) ? implode(', ', array_map(array($conn, 'real_escape_string'), $_POST['features'])) : '';
    
    $feedbackMessage = $conn->real_escape_string($_POST['feedbackMessage'] ?? '');
    $improvementSuggestions = $conn->real_escape_string($_POST['improvementSuggestions'] ?? '');
    $recommend = $conn->real_escape_string($_POST['recommend'] ?? '');
    
    $insert_query = "INSERT INTO feedback 
                    (customer_name, email, phone, booking_reference, overall_satisfaction, 
                     how_heard_about, features_used, feedback_message, improvement_suggestions, would_recommend) 
                    VALUES 
                    ('$feedbackName', '$feedbackEmail', '$feedbackPhone', '$bookingRef', $satisfaction, 
                     '$hearAbout', '$features', '$feedbackMessage', '$improvementSuggestions', '$recommend')";
    
    if ($conn->query($insert_query) === TRUE) {
        $feedback_id = $conn->insert_id;
    } else {
        echo "<!-- Feedback Error: " . $conn->error . " -->";
    }
}

$query = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedback = new Feedback(
            $row['feedback_id'],
            $row['customer_name'],
            $row['email'],
            $row['phone'],
            $row['booking_reference'],
            $row['overall_satisfaction'],
            $row['how_heard_about'],
            $row['features_used'],
            $row['feedback_message'],
            $row['improvement_suggestions'],
            $row['would_recommend']
        );
        $feedback_items[] = $feedback;
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
            <div class="col-12 bg-success text-white p-4 mb-4">
                <h1 class="mb-0"><i class="bi bi-chat-left-check me-2"></i><?php echo $page_title; ?></h1>
            </div>
        </div>

        <div class="container my-4">
            <?php if ($submitted): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Thank You!</strong> Your feedback has been submitted successfully. We appreciate your valuable input.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Recent Feedback Submissions</h5>
                </div>
                <div class="card-body">
                    <?php displayFeedbackTable($feedback_items); ?>
                </div>
            </div>

            <div class="text-center my-4">
                <a href="questionnaire.html" class="btn btn-primary"><i class="bi bi-arrow-left me-2"></i>Back to Feedback Form</a>
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
