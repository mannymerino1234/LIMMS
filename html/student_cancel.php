<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if transaction_id and status are set
    if (isset($_POST["transaction_id"]) && isset($_POST["status"])) {
        // Sanitize input
        $transaction_id = mysqli_real_escape_string($conn, $_POST["transaction_id"]);
        $status = mysqli_real_escape_string($conn, $_POST["status"]);

        // Update status in the database
        $sql = "UPDATE transaction SET status = ? WHERE transaction_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $status, $transaction_id);

        if (mysqli_stmt_execute($stmt)) {
            // Query the current quantity from the transaction
            $sql = "SELECT item_id, quantity FROM transaction WHERE transaction_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $transaction_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $item_id, $quantity);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            // Update the quantity of the item in the inventory
            $sql = "UPDATE items SET quantity = quantity + ? WHERE item_id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $quantity, $item_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Request Cancelled Successfully'
                    }).then(function() {
                        window.location.href = 'student&e.php';
                    });
                };
            </script>
            <?php
        } else {
            // Error updating status
            echo "Error updating status: " . mysqli_error($conn);
        }
    } else {
        // Transaction ID or status not set
        echo "Transaction ID or status not provided.";
    }
} else {
    // Redirect to home page if accessed directly
    header("Location: index.php");
    exit();
}

mysqli_close($conn);
?>
