<?php
include('config.php');
session_start(); // Start the session
// Check if account_id and id_number are set in the session
if(isset($_SESSION['account_id']) && isset($_SESSION['id_number'])) {
    // Retrieve account_id and id_number from the session
    $account_id = $_SESSION['account_id'];
    $id_number = $_SESSION['id_number'];
    // Use $account_id and $id_number as needed in your view_items.php file
} else {
    // Redirect user to login page if not logged in
    header("Location: login.php");
    exit();
}

// Retrieve item ID from URL parameter
if(isset($_GET['id'])) {
    $itemId = $_GET['id'];

    // Fetch item details from the database
    $sql = "SELECT * FROM items WHERE item_id = $itemId";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $imagePath = 'uploads/' . $row["image"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Item</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Borrow Item</h1>
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img class="card-img card-img-left" src="<?php echo $imagePath; ?>" alt="<?php echo $row["name"]; ?>" />
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div>
                                    <h5 class="card-title"><?php echo $row["name"]; ?></h5>
                                    <p class="card-text"><?php echo $row["description"]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="borrowForm" action="process_borrow.php" method="POST">
                            <div class="form-group">
                                <label for="quantity">Quantity:</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1">
                            </div>
                            <input type="hidden" name="item_id" value="<?php echo $row["item_id"]; ?>">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('borrowForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to borrow this item?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, borrow it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Submit the form if user confirms
                }
            });
        });
    </script>
</body>
</html>
<?php
    } else {
        echo "Item not found";
    }
} else {
    echo "Item ID not provided";
}
mysqli_close($conn);
?>
