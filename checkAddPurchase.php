<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "inventory";
    
    $conn = mysqli_connect($servername, $username, $password, $database);

    $check = false;
    if(isset($_POST['billNo'])){
        $sql_check_purchases = "SELECT * FROM purchases WHERE bill_number='".$_POST['billNo']."'";
        $result_check_purchases = mysqli_query($conn, $sql_check_purchases);
        $rows_check_purchases = mysqli_num_rows($result_check_purchases);
        if($rows_check_purchases == 1){
            $check = '1';
            echo $check;
        }else{
            $check = '0';
            echo $check;
        }
    }
    if(isset($_POST['unique'])){
        $sql_check_unique = "SELECT * FROM products_info WHERE ref_no='".$_POST['unique']."'";
        $result_check_unique = mysqli_query($conn, $sql_check_unique);
        $rows_check_unique = mysqli_num_rows($result_check_unique);
        if($rows_check_unique == 1){
            $check = '1';
            echo $check;
        }else{
            $check = '0';
            echo $check;
        }
    }
    if(isset($_POST['editBillNo'])){
        $sql_check_purchases = "SELECT * FROM purchases WHERE bill_number='".$_POST['editBillNo'][0]."' AND id!='".$_POST['editBillNo'][1]."'";
        $result_check_purchases = mysqli_query($conn, $sql_check_purchases);
        $rows_check_purchases = mysqli_num_rows($result_check_purchases);
        if($rows_check_purchases == 1){
            $check = '1';
            echo $check;
        }else{
            $check = '0';
            echo $check;
        }
    }
    if(isset($_POST['editUnique'])){
        $sql_check_unique = "SELECT * FROM products_info WHERE ref_no='".$_POST['editUnique'][0]."' AND purchase_id!='".$_POST['editUnique'][1]."'";
        $result_check_unique = mysqli_query($conn, $sql_check_unique);
        $rows_check_unique = mysqli_num_rows($result_check_unique);
        if($rows_check_unique == 1){
            $check = '1';
            echo $check;
        }else{
            $check = '0';
            echo $check;
        }
    }
    
?>