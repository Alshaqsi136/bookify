<?php

include 'config.php';

$page_title = 'Contact Message Received';

$submitted = false;
$contacts = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    
    $firstname = $conn->real_escape_string($_POST['firstname'] ?? '');
    $lastname = $conn->real_escape_string($_POST['lastname'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $subject = $conn->real_escape_string($_POST['subject'] ?? '');
    $message = $conn->real_escape_string($_POST['message'] ?? '');
    
    $insert_query = "INSERT INTO contacts 
                    (first_name, last_name, email, phone, subject, message, status) 
                    VALUES 
                    ('$firstname', '$lastname', '$email', '$phone', '$subject', '$message', 'new')";
    
    if ($conn->query($insert_query) === TRUE) {
        $contact_id = $conn->insert_id;
    } else {
        echo "<!-- Error: " . $conn->error . " -->";
    }
}

$query = "SELECT * FROM contacts ORDER BY created_at DESC LIMIT 10";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
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
            <div class="col-12 bg-info text-white p-4 mb-4">
                <h1 class="mb-0"><i class="bi bi-envelope-check me-2"></i><?php echo $page_title; ?></h1>
            </div>
        </div>

        <div class="container my-4">
            <?php if ($submitted): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Message Sent!</strong> Thank you for contacting us. We will get back to you as soon as possible.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-table me-2"></i>Recent Contact Messages</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($contacts)): ?>
                        <p class="alert alert-info">No contact messages found.</p>
                    <?php else: ?>
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($contacts as $contact): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($contact['contact_id']); ?></td>
                                        <td><?php echo htmlspecialchars($contact['first_name'] . ' ' . $contact['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($contact['email']); ?></td>
                                        <td><?php echo htmlspecialchars($contact['phone'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($contact['subject']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($contact['message'], 0, 50)) . '...'; ?></td>
                                        <td>
                                            <span class='badge bg-<?php echo ($contact['status'] === 'new' ? 'warning' : ($contact['status'] === 'resolved' ? 'success' : 'info')); ?>'>
                                                <?php echo ucfirst(htmlspecialchars($contact['status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars(date('M d, Y', strtotime($contact['created_at']))); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center my-4">
                <a href="contact.html" class="btn btn-primary"><i class="bi bi-arrow-left me-2"></i>Back to Contact Form</a>
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
