<?php
include('config.php');

if(isset($_GET['id'])) {
    $itemId = $_GET['id'];
    
    // Fetch item details from the database
    $sql = "SELECT * FROM ITEMS WHERE item_id = $itemId";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $itemName = $row['name'];
        $itemDescription = $row['description'];
        $itemQuantity = $row['quantity'];
        $itemCategoryId = $row['category_id'];
        // Add any other fields you need to edit
    } else {
        echo "Item not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission here
    // Retrieve updated values from the form and update the database
    // Remember to validate and sanitize user input
    $newName = $_POST['name'];
    $newDescription = $_POST['description'];
    $newQuantity = $_POST['quantity'];
    $newCategoryId = $_POST['category_id'];
    
    // Check if a new image file is uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageFileName = basename($_FILES['image']['name']);
        $imageFilePath = "" . $imageFileName; // Define the path where the image will be stored
        
        // Move the uploaded file to the destination directory
        if(move_uploaded_file($imageTmpName, $imageFilePath)) {
            // Update the item in the database with the new image file path
            $updateSql = "UPDATE ITEMS SET name='$newName', description='$newDescription', quantity=$newQuantity, category_id=$newCategoryId, image='$imageFilePath' WHERE item_id = $itemId";
            
            if(mysqli_query($conn, $updateSql)) {
                echo "Item updated successfully.";
            } else {
                echo "Error updating item: " . mysqli_error($conn);
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        // Update the item in the database without changing the image file
        $updateSql = "UPDATE ITEMS SET name='$newName', description='$newDescription', quantity=$newQuantity, category_id=$newCategoryId WHERE item_id = $itemId";
        
        if(mysqli_query($conn, $updateSql)) {
            ?>
                <Script>
                    window.onload = function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Item UPDATE successfully.'
                        }).then(function() {
                            window.location.href = 'manage_items.php';
                        });
                    };
                </Script>
            <?php
        } else {
            echo "Error updating item: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>DASHBOARD</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />

    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
            <!-- Dashboards -->
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
            <li class="menu-item active">
              <a href="manage_items.php" class="menu-link">
              <i class="menu-icon tf-icons bx bxs-package"></i>
                <div data-i18n="Basic">ITEMS</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="admin.php" class="menu-link">
              <i class="menu-icon tf-icons bx bxs-category-alt"></i>
                <div data-i18n="Basic">CATEGORIES</div>
              </a>
            </li>
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">TRANSACTIONS</span>
            </li>
            <li class="menu-item">
              <a href="admin.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-list-plus"></i>
                <div data-i18n="Basic">REQUEST</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="admin.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="Basic">RETURN</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="admin.php" class="menu-link">
              <i class="menu-icon tf-icons bx bx-show-alt"></i>
                <div data-i18n="Basic">VIEW RETURNED</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="admin.php" class="menu-link">
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

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

              <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item lh-1 me-3">
                <a class="btn btn-outline-primary btn-sm admin-blink"><B>ADMIN</B></a>
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
                            <span class="fw-medium d-block">John Doe</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="#">
                        <span class="d-flex align-items-center align-middle">
                          <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                          <span class="flex-grow-1 align-middle ms-1">Billing</span>
                          <span class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="javascript:void(0);">
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
            <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center text-white">Edit Item</h2>
            </div>
            <div class="card-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $itemName; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control"><?php echo $itemDescription; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo $itemQuantity; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category ID:</label>
                        <input type="number" id="category_id" name="category_id" class="form-control" value="<?php echo $itemCategoryId; ?>">
                    </div>
                    
                    <!-- Add other fields as needed -->

                    <!-- To add an image, use the input type "file" -->
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </form>
            </div>
        </div>
    </div>


            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , made with ❤️ by
                  <a href="https://themeselection.com" target="_blank" class="footer-link fw-medium">ThemeSelection</a>
                </div>
                <div class="d-none d-lg-inline-block">
                  <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                  <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                  <a
                    href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/"
                    target="_blank"
                    class="footer-link me-4"
                    >Documentation</a
                  >

                  <a
                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                    target="_blank"
                    class="footer-link"
                    >Support</a
                  >
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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
