<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'staff') {
    header("Location: login.php");
    exit();
}
$id_number = $_SESSION['id_number'];
include('config.php');

$name = $description = $quantity = $category_id = '';
$category_options = '';

// Fetch category options
$sql = "SELECT category_id, category_name FROM ITEMS_CATEGORY";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $category_options .= "<option value='{$row['category_id']}'>{$row['category_name']}</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $category_id = $_POST['category_id'];

    // File upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit();
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        
        exit();
    }

    // Allow certain file formats
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        exit();
    }

    // Upload file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert item details into database
        $sql = "INSERT INTO ITEMS (name, description, quantity, category_id, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "ssiis", $name, $description, $quantity, $category_id, $image_name);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        ?>
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Item added successfully.'
                }).then(function() {
                    window.location.href = 'manage_items.php';
                });
            };
        </script>
        <?php
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Handle search query
$search_item_id = isset($_GET['search_item_id']) ? $_GET['search_item_id'] : "";
$search_quantity = isset($_GET['search_quantity']) ? $_GET['search_quantity'] : "";
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : "";

// Fetch items from database with search functionality
$sql = "SELECT ITEMS.item_id, ITEMS.image, ITEMS.name, ITEMS.description, ITEMS.quantity, ITEMS_CATEGORY.category_name
        FROM ITEMS
        INNER JOIN ITEMS_CATEGORY ON ITEMS.category_id = ITEMS_CATEGORY.category_id
        WHERE (ITEMS.item_id LIKE ? OR ? = '')
        AND (ITEMS.name LIKE ? OR ? = '')
        AND (ITEMS.quantity LIKE ? OR ? = '')";
$stmt = mysqli_prepare($conn, $sql);
$search_param_item_id = "%" . $search_item_id . "%";
$search_param_name = "%" . $search_name . "%";
$search_param_quantity = "%" . $search_quantity . "%";
mysqli_stmt_bind_param($stmt, "ssssss", $search_param_item_id, $search_item_id, $search_param_name, $search_name, $search_param_quantity, $search_quantity);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>DASHBOARD</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .floating-error {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 9999;
        }
        .item-image {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo" style="text-align: center;">
                    <img src="SLSU.jpg" alt="Admin Logo" height="70" width="75" style="display: block; margin: 0 auto;" />
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-3">
                    <li class="menu-item">
                        <a href="staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Basic">DASHBOARD</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_accounts_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-user-account"></i>
                            <div data-i18n="Basic">ACCOUNTS</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">ITEMS</span>
                    </li>
                    <li class="menu-item active">
                        <a href="manage_items_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-package"></i>
                            <div data-i18n="Basic">ITEMS</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_item_categories_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-category-alt"></i>
                            <div data-i18n="Basic">CATEGORIES</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">TRANSACTIONS</span>
                    </li>
                    <li class="menu-item">
                        <a href="manage_request_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-plus"></i>
                            <div data-i18n="Basic">REQUEST</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_return_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                            <div data-i18n="Basic">RETURN</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_returned_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-show-alt"></i>
                            <div data-i18n="Basic">VIEW RETURNED</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_history_staff.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-pie-chart"></i>
                            <div data-i18n="Basic">MANAGE TRANSACTIONS</div>
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item lh-1 me-3">
                                <a class="btn btn-outline-primary btn-sm admin-blink"><b>STAFF</b></a>
                            </li>
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block"><?php echo $id_number ?></span>
                                                    <small class="text-muted">STAFF</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="logout.php">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                <div class="content-wrapper">
                    <div class="container">
                        <br>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">Add Item</button>

                        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                                            <div class="form-group">
                                                <label for="name" class="text-uppercase">Name:</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                                            </div>
                                            <div class="form-group">
                                                <label for="description" class="text-uppercase">Description:</label>
                                                <textarea class="form-control" id="description" name="description" placeholder="Enter description"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity" class="text-uppercase">Quantity:</label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity">
                                            </div>
                                            <div class="form-group">
                                                <label for="category_id" class="text-uppercase">Category:</label>
                                                <select class="form-control" id="category_id" name="category_id">
                                                    <option disabled selected>Select Type</option>
                                                    <?php echo $category_options; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image:</label>
                                                <input type="file" id="image" name="image" class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>

                                        <script>
                                            function validateForm() {
                                                var name = document.getElementById('name').value;
                                                var description = document.getElementById('description').value;
                                                var quantity = document.getElementById('quantity').value;
                                                var category_id = document.getElementById('category_id').value;
                                                var imageFile = document.getElementById('image').files[0];

                                                if (!name || !description || !quantity || !category_id || !imageFile || category_id == 'Select Type') {
                                                    var errorDiv = document.createElement('div');
                                                    errorDiv.className = 'floating-error';
                                                    errorDiv.innerText = 'Please fill all fields and select a valid type.';
                                                    document.body.appendChild(errorDiv);

                                                    setTimeout(function() {
                                                        errorDiv.parentNode.removeChild(errorDiv);
                                                    }, 5000);

                                                    $('#addItemModal').modal('hide');
                                                    return false;
                                                }
                                                return true;
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="form-inline mb-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_item_id" name="search_item_id" placeholder="ITEM ID" value="<?php echo $search_item_id; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_quantity" name="search_quantity" placeholder="Quantity" value="<?php echo $search_quantity; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_name" name="search_name" placeholder="Name" value="<?php echo $search_name; ?>">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-outline-success mt-3" type="submit">Search</button>
                        </form>

                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white">Item ID</th>
                                    <th class="text-white">Image</th>
                                    <th class="text-white">Name</th>
                                    <th class="text-white">Description</th>
                                    <th class="text-white">Quantity</th>
                                    <th class="text-white">Category ID</th>
                                    <th class="text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['item_id'] . "</td>";
                                    echo "<td><img src='uploads/{$row['image']}' alt='Item Image' class='item-image'></td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['description']}</td>";
                                    echo "<td>{$row['quantity']}</td>";
                                    echo "<td>{$row['category_name']}</td>";
                                    echo "<td>
                                        <a href='edit_item.php?id=" . $row['item_id'] . "' class='btn btn-primary'>Edit</a>
                                        <a href='javascript:void(0);' onclick=\"confirmDelete(" . $row['item_id'] . ")\" class='btn btn-danger'>Delete</a>
                                    </td>";
                                    echo "</tr>";
                                }
                                mysqli_stmt_close($stmt);
                                mysqli_close($conn);
                                ?>
                                <script>
                                    function confirmDelete(item_id) {
                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: 'You are about to delete this item. This action cannot be undone.',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#3085d6',
                                            confirmButtonText: 'Yes, delete it!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = 'delete_item.php?id=' + item_id;
                                            }
                                        });
                                    }
                                </script>
                            </tbody>
                        </table>
                    </div>

                    <footer class="footer" style="background-color: #e9ecee">


                    <div class="container-fluid container-p-x pt-3 pb-2">


                        <div class="row">
                        <div class="col-12 col-sm-6 col-md-4 mb-3 mb-sm-0">
                            <h4 class="mb-2"><a href="#" target="_blank" class="footer-text">SIS </a></h4>
                            <span>Online portals</span>
                            <div class="social-icon my-3">
                            <a href="https://www.facebook.com/southernleytestateu" target="_blank" class="btn btn-icon btn-sm btn-facebook me-2 text-white"><i class="bx bxl-facebook"></i></a>
                            <a href="https://youtube.com/c/SouthernLeyteStateUniversity" target="_blank" class="btn btn-icon btn-sm btn-danger me-2 text-white"><i class="bx bxl-youtube"></i></a>
                            <a href="https://www.southernleytestateu.edu.ph/index.php/en/" target="_blank" class="btn btn-icon btn-sm btn-linkedin me-2  text-white"><i class="bx bx-globe"></i></a>
                            <a href="https://gmail.com" target="_blank" class="btn btn-icon btn-sm btn-google text-white"><i class="bx bxl-google"></i></a>
                            </div>
                            <p class="pt-1">
                            <script src="https://embed.tawk.to/_s/v4/app/6625f366c87/js/twk-main.js" charset="UTF-8" crossorigin="*"></script><script src="https://embed.tawk.to/_s/v4/app/6625f366c87/js/twk-vendor.js" charset="UTF-8" crossorigin="*"></script><script src="https://embed.tawk.to/_s/v4/app/6625f366c87/js/twk-chunk-vendors.js" charset="UTF-8" crossorigin="*"></script><script src="https://embed.tawk.to/_s/v4/app/6625f366c87/js/twk-chunk-common.js" charset="UTF-8" crossorigin="*"></script><script src="https://embed.tawk.to/_s/v4/app/6625f366c87/js/twk-runtime.js" charset="UTF-8" crossorigin="*"></script><script src="https://embed.tawk.to/_s/v4/app/6625f366c87/js/twk-app.js" charset="UTF-8" crossorigin="*"></script><script async="" src="https://embed.tawk.to/632171b737898912e96907f0/1gcta8e7q" charset="UTF-8" crossorigin="*"></script><script>
                            document.write(new Date().getFullYear())
                            </script>2024 © SouthernLeyteStateU
                            </p>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4 mb-4 mb-sm-0">
                            <h5>Quicklinks</h5>
                            <ul class="list-unstyled">
                            <li><a href="https://lms.southernleytestateu.edu.ph" class="footer-link d-block pb-1">Learning Management System (LMS)</a></li>
                            <li><a href="http://sis.southernleytestateu.edu.ph" class="footer-link d-block pb-1">Student Information System (SIS)</a></li>
                            <li><a href="http://my.southernleytestateu.edu.ph" class="footer-link d-block pb-1">Online Admission System</a></li>
                            <li><a href="https://southernleytestateu.edu.ph" class="footer-link d-block pb-1">SLSU Website</a></li>
                            <li><a href="https://www.facebook.com/groups/2662041820705404" class="footer-link d-block pb-1">Facebook Official Group</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <h5>Downloads</h5>
                            <ul class="list-unstyled">
                                <li><a href="https://sis.southernleytestateu.edu.ph/download/student-manual" class="footer-link d-block pb-1">Student Manual (rev2022)</a></li>
                            </ul>
                        </div>
                        </div>
                    </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>

            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
    </div>

    <style>
        @keyframes blink {
            0% { background-color: #f8f9fa; color: #000; }
            50% { background-color: #007bff; color: #fff; }
            100% { background-color: #f8f9fa; color: #000; }
        }

        .admin-blink {
            animation: blink 2s infinite;
        }
    </style>

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/dashboards-analytics.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
