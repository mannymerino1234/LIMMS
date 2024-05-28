<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'staff') {
    header("Location: login.php");
    exit();
}
$id_number = $_SESSION['id_number'];
include('config.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id_number = $_POST['id_number'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement using parameterized query to prevent SQL injection
    $sql = "INSERT INTO accounts (id_number, name, password, type) VALUES (?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $id_number, $name, $hashed_password, $type);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        ?>
        <Script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'New account created successfully'
                }).then(function() {
                    window.location.href = 'manage_accounts.php';
                });
            };
        </Script>
        <?php
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
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
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
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
                    <li class="menu-item">
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
                    <li class="menu-item active">
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
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
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
                            <!-- User -->
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
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container">
                        <br>
                        <!-- Search form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="form-inline mb-3">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_transaction_id" name="search_transaction_id" placeholder="Transaction ID" value="<?php echo isset($_GET['search_transaction_id']) ? htmlspecialchars($_GET['search_transaction_id']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_item_name" name="search_item_name" placeholder="Item Name" value="<?php echo isset($_GET['search_item_name']) ? htmlspecialchars($_GET['search_item_name']) : ''; ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_account_name" name="search_account_name" placeholder="Account Name" value="<?php echo isset($_GET['search_account_name']) ? htmlspecialchars($_GET['search_account_name']) : ''; ?>">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-outline-success mt-3" type="submit">Search</button>
                        </form>

                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white">Transactions ID</th>
                                    <th class="text-white">Item Name</th>
                                    <th class="text-white">Account Name</th>
                                    <th class="text-white">Quantity</th>
                                    <th class="text-white">Borrow Date</th>
                                    <th class="text-white">Borrow Time</th>
                                    <th class="text-white">Status</th>
                                    <th class="text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                include('config.php');

                                // Fetch items from database with search and pending status
                                $search_transaction_id = isset($_GET['search_transaction_id']) ? '%' . $_GET['search_transaction_id'] . '%' : '%';
                                $search_item_name = isset($_GET['search_item_name']) ? '%' . $_GET['search_item_name'] . '%' : '%';
                                $search_account_name = isset($_GET['search_account_name']) ? '%' . $_GET['search_account_name'] . '%' : '%';

                                $sql = "SELECT t.transaction_id, i.name AS item_name, a.name AS account_name, t.quantity, t.borrow_date, t.borrow_time, t.status
                                        FROM transaction t
                                        INNER JOIN items i ON t.item_id = i.item_id
                                        INNER JOIN accounts a ON t.account_id = a.account_id
                                        WHERE t.status = 'Pending Request'
                                        AND t.transaction_id LIKE ?
                                        AND i.name LIKE ?
                                        AND a.name LIKE ?";
                                $stmt = mysqli_prepare($conn, $sql);
                                mysqli_stmt_bind_param($stmt, 'sss', $search_transaction_id, $search_item_name, $search_account_name);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['transaction_id']; ?></td>
                                            <td><?php echo $row['item_name']; ?></td>
                                            <td><?php echo $row['account_name']; ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo $row['borrow_date']; ?></td>
                                            <td><?php echo $row['borrow_time']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td>
                                                <form id="confirmForm<?php echo $row['transaction_id']; ?>" action="update_status.php" method="post" onsubmit="return confirmRequest(<?php echo $row['transaction_id']; ?>)">
                                                    <input type="hidden" name="transaction_id" value="<?php echo $row['transaction_id']; ?>">
                                                    <input type="hidden" name="status" value="Confirmed Request">
                                                    <button type="submit" class="btn btn-primary">Confirm Request</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8">No pending requests</td>
                                    </tr>
                                    <?php
                                }

                                mysqli_stmt_close($stmt);
                                mysqli_close($conn);
                            ?>
                            </tbody>
                        </table>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
                        <script>
                            function confirmRequest(transactionId) {
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "You are about to confirm the request!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Yes, confirm it!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // If confirmed, submit the form
                                        document.getElementById('confirmForm' + transactionId).submit();
                                    }
                                });
                                return false; // Prevent default form submission
                            }
                        </script>
                    </div>

                    <!-- Footer -->
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
                    <!-- / Footer -->
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

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
