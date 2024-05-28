<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['type']) || $_SESSION['type'] != 'admin') {
    header("Location: login.php");
    exit();
}
$id_number= $_SESSION['id_number'];
?>
<?php
    include('config.php');

    // Total borrowers
    $totalBorrowersQuery = "SELECT COUNT(DISTINCT account_id) as total_borrowers FROM TRANSACTION";
    $result = mysqli_query($conn, $totalBorrowersQuery);
    $totalBorrowers = mysqli_fetch_assoc($result)['total_borrowers'];

    // Borrowers this month
    $borrowersThisMonthQuery = "SELECT COUNT(DISTINCT account_id) as borrowers_this_month FROM TRANSACTION WHERE MONTH(borrow_date) = MONTH(CURRENT_DATE()) AND YEAR(borrow_date) = YEAR(CURRENT_DATE())";
    $result = mysqli_query($conn, $borrowersThisMonthQuery);
    $borrowersThisMonth = mysqli_fetch_assoc($result)['borrowers_this_month'];

    // Borrowers this week
    $borrowersThisWeekQuery = "SELECT COUNT(DISTINCT account_id) as borrowers_this_week FROM TRANSACTION WHERE YEARWEEK(borrow_date, 1) = YEARWEEK(CURRENT_DATE(), 1)";
    $result = mysqli_query($conn, $borrowersThisWeekQuery);
    $borrowersThisWeek = mysqli_fetch_assoc($result)['borrowers_this_week'];

    // Borrowers today
    $borrowersTodayQuery = "SELECT COUNT(DISTINCT account_id) as borrowers_today FROM TRANSACTION WHERE DATE(borrow_date) = CURRENT_DATE()";
    $result = mysqli_query($conn, $borrowersTodayQuery);
    $borrowersToday = mysqli_fetch_assoc($result)['borrowers_today'];

    // Frequently Borrowed Items
    $topItemsQuery = "SELECT I.name, COUNT(T.item_id) as borrow_count 
                      FROM TRANSACTION T 
                      JOIN ITEMS I ON T.item_id = I.item_id 
                      GROUP BY T.item_id 
                      ORDER BY borrow_count DESC 
                      LIMIT 5";
    $topItemsResult = mysqli_query($conn, $topItemsQuery);
    $topItems = [];
    while ($row = mysqli_fetch_assoc($topItemsResult)) {
        $topItems[] = $row;
    }

    // Close the database connection
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.1/css/boxicons.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e3e6f0;
        }
        .card-title h5 {
            color: #4e73df;
            font-weight: bold;
        }.card h5{
          color: #4e73df;
          font-weight: bold;
        }
        .card-body {
            padding: 1.5rem;
        }
        .btn-custom {
            background-color: #4e73df;
            color: white;
        }
        .btn-custom:hover {
            background-color: #2e59d9;
        }
        a {
          text-decoration: none;
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
            <li class="menu-item active">
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
            <li class="menu-item">
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
                          <span class="fw-medium d-block"><?php echo $id_number?></span>
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
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                  <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                      <div class="card h-100">
                          <div class="card-header d-flex align-items-center justify-content-between pb-0">
                              <div class="card-title mb-0">
                                  <h5 class="m-0 me-2">Borrower Statistics</h5>
                                  <small class="text-muted" id="totalBorrowers"></small>
                              </div>
                          </div>
                          <div class="card-body" style="margin-bottom: 50px; margin-top: 10px;">
                              <div class="d-flex justify-content-between align-items-center mb-4">
                                  <div class="d-flex justify-content-center w-100">
                                      <div class="d-flex flex-column align-items-center text-center gap-1 w-50">
                                          <h2 class="mb-2" id="totalBorrowersValue">Loading...</h2>
                                          <span>Total Borrowers</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="chart-container" style="margin-bottom: 50px; margin-top: -225px;">
                                  <canvas id="borrowersChart"></canvas>
                              </div>
                              <div class="chart-container mt-4">
                                  <canvas id="largeBorrowersChart"></canvas>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- New Card for Frequently Borrowed Items / Top Items -->
                  <div class="col-md-6 col-lg-4 order-2 mb-4">
                      <div class="card h-100">
                          <div class="card-header d-flex align-items-center justify-content-between">
                              <h5 class="card-title m-0 me-2">Frequently Borrowed Items</h5>

                          </div>
                          <br>
                          <div class="card-body">
                              <ul class="p-0 m-0" id="topItemsList">
                                  <li class="list-group-item">Loading...</li>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
              <script>
                    document.getElementById('totalBorrowers').textContent = '<?php echo $totalBorrowers; ?> Total Borrowers';
                    document.getElementById('totalBorrowersValue').textContent = '<?php echo $totalBorrowers; ?>';

                    const xValues = ["This Month", "This Week", "Today"];
                    const yValues = [<?php echo $borrowersThisMonth; ?>, <?php echo $borrowersThisWeek; ?>, <?php echo $borrowersToday; ?>];
                    const barColors = ["#7367f0", "#28c76f", "#00cfe8"];

                    new Chart("largeBorrowersChart", {
                        type: 'doughnut',
                        data: {
                            labels: xValues,
                            datasets: [{
                                backgroundColor: barColors,
                                data: yValues
                            }]
                        },
                        options: {
                            title: {
                                display: true,
                                text: 'Borrower Statistics'
                            }
                        }
                    });

                    // Populate the frequently borrowed items list
                    const topItemsList = document.getElementById('topItemsList');
                    topItemsList.innerHTML = '<?php foreach ($topItems as $item) { ?>' +
                        '<li class="d-flex mb-4 pb-1">' +
                        '<div class="avatar flex-shrink-10 ">' +
                        '<i class="menu-icon tf-icons bx bx-package"></i>' +
                        '</div>' +
                        '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">' +
                        '<div class="me-2">' +
                        '<small class="text-muted d-block mb-1">Item Name</small>' +
                        '<h6 class="mb-0"><?php echo $item["name"]; ?></h6>' +
                        '</div>' +
                        '<div class="user-progress d-flex align-items-center gap-1">' +
                        '<h6 class="mb-0"><?php echo $item["borrow_count"]; ?></h6>' +
                        '<span class="text-muted">times</span>' +
                        '</div>' +
                        '</div>' +
                        '</li>' +
                        '<?php } ?>';
                </script>
          </div>
            <!-- / Content -->

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

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

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
