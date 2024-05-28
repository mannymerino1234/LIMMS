<?php
include('config.php');

// Check if item ID is provided in the URL
if(isset($_GET['id'])) {
    $account_id = $_GET['id'];

    try {
        // Delete account from the database
        $sql = "DELETE FROM accounts WHERE account_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $account_id);
        mysqli_stmt_execute($stmt);

        // Check if any rows were affected
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Account Deleted successfully.'
                    }).then(function() {
                        window.location.href = 'manage_accounts.php';
                    });
                };
            </script>
            <?php
        } else {
            echo "Failed to delete account.";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } catch (Exception $e) {
        ?>
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'You cant delete this account as it is currently being used.'
                }).then(function() {
                    window.location.href = 'manage_accounts.php';
                });
            };
        </script>
        <?php
    }
} else {
    echo "Item ID not provided.";
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>