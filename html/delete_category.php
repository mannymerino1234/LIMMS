<?php
include('config.php');

// Check if item ID is provided in the URL
if(isset($_GET['id'])) {
    $category_id = $_GET['id'];

    try {
        // Delete item from the database
        $sql = "DELETE FROM items_category WHERE category_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $category_id);
        mysqli_stmt_execute($stmt);

        // Check if any rows were affected
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            ?>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Delete Successfully.'
                    }).then(function() {
                        window.location.href = 'manage_item_categories.php';
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
        <Script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: 'You cant delete this Category as it is currently being used.'
                }).then(function() {
                    window.location.href = 'manage_item_categories.php';
                });
            };
        </Script>
        <?php
    }
} else {
    echo "Item ID not provided.";
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>