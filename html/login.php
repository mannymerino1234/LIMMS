<?php
include('config.php');

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id_number = isset($_POST['id_number']) ? $_POST['id_number'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    // Prepare SQL statement
    $sql = "SELECT * FROM accounts WHERE id_number = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id_number);

    // Execute the statement and get result
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        // Fetch row and verify password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            if ($row['type'] == $type) {
                session_start();
                $_SESSION['account_id'] = $row['account_id'];
                $_SESSION['id_number'] = $id_number;
                $_SESSION['type'] = $type;

                // Redirect based on user type
                switch ($type) {
                    case 'student':
                    case 'employee':
                        header("Location: student_employee.php");
                        break;
                    case 'admin':
                        header("Location: admin.php");
                        break;
                    case 'staff':
                        header("Location: staff.php");
                        break;
                    default:
                        $error_message = "Invalid user type selected.";
                }
                exit();
            } else {
                $error_message = "Invalid user type selected.";
            }
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-wide customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>SLSU-BC</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>

    <style>
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card">
                <div class="card-body">
                    <div class="app-brand justify-content-center">
                        <a class="app-brand-link gap-2">
                            <img src="SLSU_L.png" alt="Logo" style="height: 100px; width: 320px;">
                        </a>
                    </div>
                    <h4 class="mb-2">Welcome to SLSU! 👋</h4>
                    <p class="mb-4">Please sign in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-3" action="login.php" method="post">
                        <div class="mb-3">
                            <label for="id_number" class="form-label">ID NUMBER</label>
                            <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Enter your ID Number" autofocus />
                        </div>

                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                                <a href="auth-forgot-password-basic.html"><small>Forgot Password?</small></a>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <select class="form-control" id="type" name="type" required>
                                <option disabled selected style="display:none;">Select Type</option>
                                <option value="admin">Admin</option>
                                <option value="staff">Staff</option>
                                <option value="employee">Employee</option>
                                <option value="student">Student</option>
                            </select><br>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-me" />
                                <label class="form-check-label" for="remember-me">Remember Me</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>

                    <?php
                    if (!empty($error_message)) {
                        echo '<p class="error-message">' . $error_message . '</p>';
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Core JS -->
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>

<!-- Main JS -->
<script src="../assets/js/main.js"></script>

<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
