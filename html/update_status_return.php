<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'admin') {
    header("Location: login.php");
    exit();
}
include('config.php');
$account_id = $_SESSION['account_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $transaction_id = $_POST['transaction_id'];
    $status = $_POST['status'];

    // Check current status and get the item name using inner join
    $current_status_sql = "
        SELECT t.status, i.name, a.id_number
        FROM transaction t 
        INNER JOIN items i ON t.item_id = i.item_id 
        INNER JOIN accounts a ON t.account_id = a.account_id
        WHERE t.transaction_id = '$transaction_id'
    ";
    $current_status_result = mysqli_query($conn, $current_status_sql);

    if ($current_status_result && mysqli_num_rows($current_status_result) > 0) {
        $row = mysqli_fetch_assoc($current_status_result);
        $current_status = $row['status'];
        $item_name = $row['name'];
        $id_number = $row['id_number'];

        // Check if status is not "Pending"
        if ($current_status !== 'pending request') {
            // Update status to "Returned"
            $return_date = date("Y-m-d"); // Get current date as return date

            // Update status and return date in the database
            $update_sql = "UPDATE transaction SET status='$status', return_date='$return_date' WHERE transaction_id='$transaction_id'";
            $update_result = mysqli_query($conn, $update_sql);

            if ($update_result) {
                // Insert data into transaction_history
                $description = "Item $item_name returned successfully by account ID number $id_number";
                $history_sql = "INSERT INTO transaction_history (description, date, account_id) VALUES ('$description', '$return_date', '$account_id')";
                $history_result = mysqli_query($conn, $history_sql);

                if ($history_result) {
                    ?>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                    <script>
                        window.onload = function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Item "<?php echo $item_name; ?>" has been returned successfully by account ID number <?php echo $id_number; ?>'
                            }).then(function() {
                                window.location.href = 'manage_return.php';
                            });
                        };
                    </script>
                    <?php
                } else {
                    echo "Error inserting transaction history: " . mysqli_error($conn);
                }
            } else {
                echo "Error updating status: " . mysqli_error($conn);
            }
        } else {
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Cannot update status to Returned as it is currently Pending.'
                    }).then(function() {
                        window.location.href = 'manage_return.php';
                    });
                };
            </script>
            <?php
        }
    } else {
        echo "Error retrieving current status.";
    }
}

mysqli_close($conn);
?>
