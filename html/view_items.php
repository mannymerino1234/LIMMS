<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .card-img-top {
            width: 100%;
            height: 350px;
        }
        .card {
            height: 100%; 
        }
        .slick-prev,
        .slick-next {
            font-size: 20px;
            color: #007bff;
            z-index: 9999; 
            padding-left: 1px; 
            margin-right: 60px;
        }
        .slick-prev:hover,
        .slick-next:hover {
            color: #0056b3;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 24px;
            color: #007bff;
        }
        .back-button:hover {
            color: #0056b3;
            text-decoration: none;
        }
        .search-container {
            display: flex;
            justify-content: right;
            align-items: right;
            margin-right:  75px;
            flex-wrap: wrap;
        }
        .search-container .form-control {
            width: 200px;
            margin: 5px;
        }
        .search-container .btn {
            white-space: nowrap;
        }
    </style>
    <title>Document</title>
</head>
<body>
    <a href="student_employee.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
    <div class="container">
        <img src="SLSU_L.png" alt="Logo" class="img-fluid my-4">

        <div class="search-container">
            <form class="form-inline" onsubmit="event.preventDefault(); search();">
                <div class="form-group mx-sm-3 mb-2">
                    <input type="text" class="form-control" placeholder="Search categories" id="categorySearchInput">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <input type="text" class="form-control" placeholder="Search items" id="itemSearchInput">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Search</button>
            </form>
        </div>

        <?php
        include('config.php');

        // Fetch search terms
        $categorySearchTerm = isset($_POST['categorySearchTerm']) ? $_POST['categorySearchTerm'] : '';
        $itemSearchTerm = isset($_POST['itemSearchTerm']) ? $_POST['itemSearchTerm'] : '';

        // Fetch distinct categories
        if ($categorySearchTerm != '') {
            $categoryQuery = "SELECT * FROM items_category WHERE category_name LIKE '%$categorySearchTerm%'";
        } else {
            $categoryQuery = "SELECT * FROM items_category";
        }
        $categoryResult = mysqli_query($conn, $categoryQuery);

        if (mysqli_num_rows($categoryResult) > 0) {
            while($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                echo '<div>';
                echo '<h2 style="text-transform: uppercase;">' . $categoryRow['category_name'] . '</h2>';
                echo '<div class="slider"  id="' . str_replace(' ', '_', $categoryRow['category_name']) . '">';

                // Fetch items for this category
                $categoryId = $categoryRow['category_id'];
                if ($itemSearchTerm != '') {
                    $sql = "SELECT * FROM items WHERE category_id = $categoryId AND name LIKE '%$itemSearchTerm%'";
                } else {
                    $sql = "SELECT * FROM items WHERE category_id = $categoryId";
                }
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $imagePath = 'uploads/' . $row["image"];
                        echo '<div>';
                        echo '<div class="col-md-10 col-lg-10 mb-10">';
                        echo '<div class="card">';
                        echo '<img class="card-img-top"  src="' . $imagePath . '" alt="' . $row["name"] . '" />';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title" style="text-transform: uppercase;">' . $row["name"] . '</h5>';
                        echo '</div>';
                        echo '<a href="borrow.php?id=' . $row["item_id"] . '" class="btn btn-outline-primary">BORROW</a>'; // Updated link
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "No items found for this category";
                }

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No categories found";
        }
        mysqli_close($conn);
        ?>
        

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.slider').each(function(){
                $(this).slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000,
                    dots: true,
                    arrows: true,
                    infinite: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            });
        });

        function search() {
            const categorySearchInput = document.getElementById('categorySearchInput').value;
            const itemSearchInput = document.getElementById('itemSearchInput').value;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const categoryInput = document.createElement('input');
            categoryInput.type = 'hidden';
            categoryInput.name = 'categorySearchTerm';
            categoryInput.value = categorySearchInput;

            const itemInput = document.createElement('input');
            itemInput.type = 'hidden';
            itemInput.name = 'itemSearchTerm';
            itemInput.value = itemSearchInput;

            form.appendChild(categoryInput);
            form.appendChild(itemInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    
</body>
</html>
