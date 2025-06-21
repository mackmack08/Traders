<?php
include("logincode.php");
$page_title = "View Ticket";
include("sidebar.php");
include("includes/header.php"); 
include("dbcon.php");

$loggedUserId = $_SESSION['userId'];

// Initialize trscnType and reqserv
$trscnType = "Ticket No. - Title"; 

// Check if 'trscnType' and 'orderNo' are passed through GET
if (isset($_GET['trscnType']) && isset($_GET['tickNo'])) {
    $trscnType = "Ticket No. " . htmlspecialchars($_GET['tickNo']) . " - " . htmlspecialchars($_GET['trscnType']);
}

// Check if feedback has already been submitted for this user and service type
$checkQuery = "SELECT COUNT(*) FROM feedback WHERE userId = ? AND trscnType = ?";
$checkStmt = $con->prepare($checkQuery);
$checkStmt->bind_param("is", $loggedUserId, $trscnType);
$checkStmt->execute();
$checkStmt->bind_result($feedbackCount);
$checkStmt->fetch();
$checkStmt->close();

// Redirect if feedback already submitted
if ($feedbackCount > 0) {
    echo "<script>alert('You have already submitted feedback for this Order.');</script>";
    echo "<script>window.location.href = 'vticket_customer.php';</script>";
    exit();
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $description = $_POST['description'];

    // Prepare the SQL statement to insert feedback
    $query = "INSERT INTO feedback (userId, trscnType, description) VALUES (?, ?, ?)";
    
    // Initialize prepared statement
    $stmt = $con->prepare($query);
    $stmt->bind_param("iss", $loggedUserId, $trscnType, $description);
    
    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Feedback submitted successfully!');</script>";
        echo "<script>window.location.href = 'vticket_customer.php?feedback=success';</script>";
        exit();
    } else {
        echo "<script>alert('Error submitting feedback. Please try again later.');</script>";
        echo "<script>window.location.href = 'vticket_customer.php';</script>";
        exit();
    }
    
    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
</head>
<body>
    <div class="container py-5">
        <h2>Feedback for Ticket</h2>
        <!-- If feedback is already submitted, don't display the form -->
        <?php if ($feedbackCount == 0): ?>
            <form action="" method="POST">
                <input type="hidden" name="userId" value="<?php echo $loggedUserId; ?>">
                <input type="hidden" name="trscnType" value="<?php echo htmlspecialchars($trscnType); ?>">
                <div class="mb-3">
                    <label for="description" class="form-label">Your Feedback:</label>
                    <input type="text" class="form-control" name="description" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit Feedback</button>
                <a href="vticket_customer.php" class="btn btn-secondary">Cancel</a>
            </form>
        <?php else: ?>
            <p class="alert alert-info">You have already submitted feedback for this order.</p>
            <a href="vticket_customer.php" class="btn btn-secondary">Back to Order Account</a>
        <?php endif; ?>
    </div>
</body>
</html>
