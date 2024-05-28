<?php
include('config.php');
session_start(); // Start the session

// Check if account_id and id_number are set in the session
if (isset($_SESSION['account_id']) && isset($_SESSION['id_number'])) {
    // Retrieve account_id and id_number from the session
    $account_id = $_SESSION['account_id'];
    $id_number = $_SESSION['id_number'];
    // Use $account_id and $id_number as needed in your view_items.php file
} else {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemId = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $borrowedDate = date("Y-m-d");
    $borrowedTime = date("H:i:s");
    $status = "pending request";

    // Fetching item name and quantity from items table
    $itemInfoResult = mysqli_query($conn, "SELECT name, quantity FROM items WHERE item_id = $itemId");
    if ($itemInfoResult && mysqli_num_rows($itemInfoResult) > 0) {
        $itemInfoRow = mysqli_fetch_assoc($itemInfoResult);
        $itemName = $itemInfoRow['name'];
        $availableQuantity = $itemInfoRow['quantity'];

        if ($availableQuantity >= $quantity) {
            $sql = "INSERT INTO transaction (item_id, account_id, quantity, borrow_date, borrow_time, status) 
                    VALUES ('$itemId', '$account_id', '$quantity', '$borrowedDate', '$borrowedTime', '$status')";

            if (mysqli_query($conn, $sql)) {
                $addedDate = date("Y-m-d"); 
                $description = "Borrow item: $itemName"; 
                $sqlHistory = "INSERT INTO transaction_history (account_id, description, date) 
                               VALUES ('$account_id', '$description', '$addedDate')";
                if (mysqli_query($conn, $sqlHistory)) {
                    // Deduct the quantity from items table
                    $updateQuantitySql = "UPDATE items SET quantity = quantity - $quantity WHERE item_id = $itemId";
                    if (mysqli_query($conn, $updateQuantitySql)) {
                        ?>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Borrow request submitted successfully.'
                                }).then(function() {
                                    window.location.href = 'view_items.php';
                                });
                            };
                        </script>
                        <?php
                    } else {
                        ?>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error updating item quantity: <?= mysqli_error($conn) ?>'
                                }).then(function() {
                                    window.location.href = 'view_items.php';
                                });
                            };
                        </script>
                        <?php
                    }
                } else {
                    ?>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                    <script>
                        window.onload = function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error inserting transaction history: <?= mysqli_error($conn) ?>'
                            }).then(function() {
                                window.location.href = 'view_items.php';
                            });
                        };
                    </script>
                    <?php
                }
            } else {
                ?>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                <script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error inserting transaction: <?= mysqli_error($conn) ?>'
                        }).then(function() {
                            window.location.href = 'view_items.php';
                        });
                    };
                </script>
                <?php
            }
        } else {
            ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
            <script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Borrow request unsuccessful. Not enough items available.'
                    }).then(function() {
                        window.location.href = 'view_items.php';
                    });
                };
            </script>
            <?php
        }
    } else {
        ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Item not found.'
                }).then(function() {
                    window.location.href = 'view_items.php';
                });
            };
        </script>
        <?php
    }

    mysqli_close($conn);
}
?>
