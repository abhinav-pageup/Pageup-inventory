<?php
// for index.php

$sql_update_return = "UPDATE allot_return SET return_date=current_timestamp() WHERE employee_id='".$_GET['delete']."'";
            $result_update_return = mysqli_query($conn, $sql_update_return);
            if($result_update_return){
                $sql_update_product_master = "UPDATE product_master SET alloted=alloted-1 WHERE id IN (SELECT product_id FROM products_info WHERE id IN (SELECT product_info_id FROM allot_return WHERE employee_id='".$_GET['delete']."'))";
                $result_update_product_master = mysqli_query($conn, $sql_update_product_master);
                if($result_update_product_master){
                    $sql_update_products_info = "UPDATE products_info SET is_alloted='0' WHERE id IN (SELECT product_info_id FROM allot_return WHERE employee_id='".$_GET['delete']."')";
                    $result_update_product_info = mysqli_query($conn, $sql_update_products_info);
                    if($result_update_product_info){
                        header("Location: /inventory/index.php");
                    }
                }
            }

?>