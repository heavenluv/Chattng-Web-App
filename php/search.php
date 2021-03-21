<?php
    session_start();
    include_once "config.php";

    $outgoing_id = $_SESSION['unique_id'];
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
    $output = "";
    $query = mysqli_query($conn, "SELECT * FROM users WHERE fname LIKE '%{$searchTerm}%' OR lname LIKE '%{$searchTerm}%' ");
    if(mysqli_num_rows($query) > 0){ 
        include_once "data.php";
        //$output .= "user is found";
    }else{
        $output .= 'No user found related to your search term';
    }
    echo $output;
?>