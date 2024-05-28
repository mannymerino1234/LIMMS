<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'admin') {
    header("Location: login.php");
    exit();
}
$id_number = $_SESSION['id_number'];
include('config.php');

$category_name = '';
$category_name_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = trim($_POST["category_name"]);

    if (empty($category_name)) {
        ?>
        <script>
            window.onload = function() {
                var errorDiv = document.createElement('div');
                errorDiv.className = 'floating-error';
                errorDiv.innerText = 'Please fill all fields and select a valid type.';
                document.body.appendChild(errorDiv);

                // Set a timeout to remove the error message after 5 seconds
                setTimeout(function() {
                    errorDiv.parentNode.removeChild(errorDiv);
                }, 5000);

                // Close the modal
                $('#addCategoryModal').modal('hide');
                return false;
            };
        </script>
        <?php
    } else {
        $sql = "INSERT INTO ITEMS_CATEGORY (category_name) VALUES (?)";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $category_name);
            if (mysqli_stmt_execute($stmt)) {
                ?>
                <script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Category Added Successfully.'
                        }).then(function() {
                            window.location.href = 'manage_item_categories.php';
                        });
                    };
                </script>
                <?php
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}

// Handle search query
$search_category_id = isset($_GET['search_category_id']) ? $_GET['search_category_id'] : "";
$search_category_name = isset($_GET['search_category_name']) ? $_GET['search_category_name'] : "";

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
                        <a href="admin.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Basic">DASHBOARD</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_accounts.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-user-account"></i>
                            <div data-i18n="Basic">ACCOUNTS</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">ITEMS</span>
                    </li>
                    <li class="menu-item">
                        <a href="manage_items.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-package"></i>
                            <div data-i18n="Basic">ITEMS</div>
                        </a>
                    </li>
                    <li class="menu-item active">
                        <a href="manage_item_categories.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bxs-category-alt"></i>
                            <div data-i18n="Basic">CATEGORIES</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">TRANSACTIONS</span>
                    </li>
                    <li class="menu-item">
                        <a href="manage_request.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-list-plus"></i>
                            <div data-i18n="Basic">REQUEST</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_return.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                            <div data-i18n="Basic">RETURN</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_returned.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-show-alt"></i>
                            <div data-i18n="Basic">VIEW RETURNED</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="manage_history.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-pie-chart"></i>
                            <div data-i18n="Basic">MANAGE TRANSACTIONS</div>
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class=" layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item lh-1 me-3">
                                <a class="btn btn-outline-primary btn-sm admin-blink"><b>ADMIN</b></a>
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
                                                    <small class="text-muted">Admin</small>
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
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">Add Category</button>

                        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="categoryForm">
                                            <div class="form-group">
                                                <label for="category_name">Category Name:</label>
                                                <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_name); ?>">
                                                <span class="text-danger"><?php echo htmlspecialchars($category_name_err); ?></span>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search form -->
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="form-inline mb-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_category_id" name="search_category_id" placeholder="Category ID" value="<?php echo htmlspecialchars($search_category_id); ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_category_name" name="search_category_name" placeholder="Category Name" value="<?php echo htmlspecialchars($search_category_name); ?>">
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-outline-success mt-3" type="submit">Search</button>
                        </form>

                        <!-- Display existing categories -->
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-white">Category ID</th>
                                    <th class="text-white">Category Name</th>
                                    <th class="text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('config.php');

                                // Fetch categories from database with search functionality
                                $sql = "SELECT * FROM items_category WHERE (category_id LIKE ? OR ? = '') AND (category_name LIKE ? OR ? = '')";
                                $stmt = mysqli_prepare($conn, $sql);
                                $search_param_category_id = "%" . $search_category_id . "%";
                                $search_param_category_name = "%" . $search_category_name . "%";
                                mysqli_stmt_bind_param($stmt, "ssss", $search_param_category_id, $search_category_id, $search_param_category_name, $search_category_name);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . $row['category_id'] . "</td>";
                                        echo "<td>" . $row['category_name'] . "</td>";
                                        echo "<td>
                                            <a href='edit_category.php?id=" . $row['category_id'] . "' class='btn btn-primary'>Edit</a>
                                            <a href='javascript:void(0);' onclick=\"confirmDelete(" . $row['category_id'] . ")\" class='btn btn-danger'>Delete</a>
                                        </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>No categories found</td></tr>";
                                }

                                mysqli_stmt_close($stmt);
                                mysqli_close($conn);
                                ?>
                                <script>
                                    function confirmDelete(categoryId) {
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
                                                window.location.href = 'delete_category.php?id=' + categoryId;
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
