<?php
include('config.php');

// Check if item ID is provided in the URL
if(isset($_GET['id'])) {
    $item_id = $_GET['id'];

    try {
        // Delete item from the database
        $sql = "DELETE FROM ITEMS WHERE item_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $item_id);
        mysqli_stmt_execute($stmt);

        // Check if any rows were affected
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Item Deleted successfully.'
                    }).then(function() {
                        window.location.href = 'manage_items.php';
                    });
                };
            </script>
            <?php
        } else {
            echo "Failed to delete item.";
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
                    text: 'You cant delete this Item as it is currently being used.'
                }).then(function() {
                    window.location.href = 'manage_items.php';
                });
            };
        </script>
        <?php
    }
} else {
    echo "Item ID not provided.";
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>