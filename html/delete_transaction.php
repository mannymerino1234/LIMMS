<?php
// Include database configuration
include('config.php');

// Check if transaction ID is received
if (isset($_POST['transaction_id'])) {
    // Sanitize the transaction ID to prevent SQL injection
    $transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);

    // Delete the transaction from the database
    $sql = "DELETE FROM transaction WHERE transaction_id = '$transaction_id'";
    if (mysqli_query($conn, $sql)) {
        ?>
         <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Transaction deleted successfully.'
                    }).then(function() {
                        window.location.href = 'manage_returned.php';
                    });
                };
            </script>
            <?php
    } else {
        // Error deleting transaction
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If transaction ID is not received, redirect back to the manage items page
    header("Location: manage_items.php");
}

// Close database connection
mysqli_close($conn);
?>
