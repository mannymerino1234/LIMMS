<?php
    // Create connection
    $conn = mysqli_connect('localhost','root','','limmsh');

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
