<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

// Getting all purchases from purchases table
$sql_get_purchases = "SELECT * FROM purchases WHERE is_active='1' ORDER BY created_by DESC";
$result_get_purchases = mysqli_query($conn, $sql_get_purchases);

// Deleting from purchases table for specific purchase
if (isset($_GET['delete'])) {
    $sql_get_delete_data = "SELECT * FROM purchases WHERE id='" . $_GET['delete'] . "'";
    $result_get_delete_data = mysqli_query($conn, $sql_get_delete_data);
    $fetch_get_delete_data = mysqli_fetch_assoc($result_get_delete_data);
    if ($result_get_delete_data) {
        $sql_get_update_product_master = "UPDATE product_master SET stock=stock-" . $fetch_get_delete_data['quantity'] . " WHERE id=" . $fetch_get_delete_data['product_id'] . "";
        $result_get_update_product_master = mysqli_query($conn, $sql_get_update_product_master);
        if ($result_get_update_product_master) {
            $sql_delete_alloted = "DELETE FROM alloted_products WHERE product_info_id IN (SELECT id FROM products_info WHERE purchase_id='".$_GET['delete']."')";
            $result_delete_alloted = mysqli_query($conn, $sql_delete_alloted);
            if($result_delete_alloted){
                $sql_delete_product_info = "DELETE FROM products_info WHERE purchase_id='".$_GET['delete']."'";
                $result_delete_product_info = mysqli_query($conn, $sql_delete_product_info);
                if ($result_delete_product_info) {
                    $sql_delete_purchase = "DELETE FROM purchases WHERE id='" . $_GET['delete'] . "'";
                    $result_delete_purchase = mysqli_query($conn, $sql_delete_purchase);
                    if ($result_delete_purchase) {
                        header("Location: /inventory/purchases.php");
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchases</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Purchases</h1>
    </div>
    <div class="px-5 grid grid-cols-1 md:grid-cols-2 justify-items-center gap-3 md:gap-0 mt-4">
        <a href='/inventory/addPurchases.php' class='bg-green-600 font-bold ml-4 hover:bg-green-700 text-white p-2 rounded-lg px-3 col-span-1 w-max md:mr-auto h-min'>Add Purchase</a>
        <div>
            <select name="search_type" id="search_type" class='focus:outline-none border px-6 py-3 rounded-xl whitespace-nowrap text-sm text-gray-900'>
                <option value="name">By Name</option>
                <option value="bill">By Bill</option>
                <option value="date">By Date</option>
            </select>
            <input type="text" onclick="search()" onkeyup="search()" name="searchbar" id="searchbar" class="p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 md:w-96 w-full col-span-1 md:ml-auto" placeholder="Search Here">
        </div>
    </div>
    <div class="flex flex-col overflow-hidden mt-5">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill Number</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Displaying all data
                            if ($result_get_purchases) {
                                while ($row = mysqli_fetch_assoc($result_get_purchases)) {
                                    // Checking if any product form purchases are alloted or not
                                    $sql_check_allotment = "SELECT * FROM products_info WHERE purchase_id='".$row['id']."' AND is_alloted='1'";
                                    $result_check_allotment = mysqli_query($conn, $sql_check_allotment);
                                    $fetch_check_allotment = mysqli_fetch_assoc($result_check_allotment);
                                    $num_check_allotment = mysqli_num_rows($result_check_allotment);
                                    // Retriving the product name form product master
                                    $sql_get_check = "SELECT * FROM product_master WHERE id='" . $row['product_id'] . "'";
                                    $result_get_check = mysqli_query($conn, $sql_get_check);
                                    $fetch_get_check = mysqli_fetch_assoc($result_get_check);
                                    echo "
                            <tr>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='flex items-center'>
                                    <div class='ml-4'>
                                        <div class='text-sm font-medium text-gray-900 searching_name'>" .      $fetch_get_check['name'] . "</div>
                                    </div>
                                    </div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='text-sm text-gray-900 searching_bill'>" . $row['bill_number'] . "</div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='text-sm text-gray-900'>" . $row['quantity'] . "</div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='text-sm text-gray-900 searching_date'>" . $row['date'] . "</div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='text-sm text-gray-900'>" . (($row['company']==null)?'Not Mentioned':$row['company']) . "</div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='text-sm text-gray-900'>" . $row['cost'] . "</div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap'>
                                    <div class='text-sm text-gray-900'>" . (($row['remark']==null)?'Not Mentioned':$row['remark']) . "</div>
                                </td>
                                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>".
                                ($num_check_allotment==0?"<a disable href='/inventory/purchases.php?delete=" . $row['id'] . "' class='bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg px-3'>Delete</a>":"<p class='bg-green-600 w-min text-white p-2 rounded-lg px-3'>Allot Some</p>")."
                                ".($num_check_allotment==0?"<a disable href='/inventory/editPurchase.php?edit=" . $row['id'] . "' class='bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg px-3'>Edit</a>":"")."
                                
                                </td>
                            </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function search() {
        const search = $("#searchbar").val().toLowerCase();
        if ($('#search_type').val() === 'name') {
            if ($('#searchbar').val() !== '', $('#searchbar').val() !== null) {
                console.log(search)
                $("tr").each(function(index) {
                    if (index !== 0) {
                        $(this).find('.searching_name').each(function() {
                            var id = $(this).text().toLowerCase();
                            if (id.indexOf(search) <= -1) {
                                $(this).parent().closest("tr").hide();
                            } else {
                                $(this).parent().closest("tr").show();
                            }
                        })
                    }
                })
            }
        } else if ($('#search_type').val() === 'bill') {
            if ($('#searchbar').val() !== '', $('#searchbar').val() !== null) {
                $("tr").each(function(index) {
                    if (index !== 0) {
                        $(this).find('.searching_bill').each(function() {
                            var id = $(this).text().toLowerCase();
                            if (id.indexOf(search) <= -1) {
                                $(this).parent().closest("tr").hide();
                            } else {
                                $(this).parent().closest("tr").show();
                            }
                        })
                    }
                })
            }
        } else if ($('#search_type').val() === 'date') {
            if ($('#searchbar').val() !== '', $('#searchbar').val() !== null) {
                $("tr").each(function(index) {
                    if (index !== 0) {
                        $(this).find('.searching_date').each(function() {
                            var id = $(this).text().toLowerCase();
                            if (id.indexOf(search) <= -1) {
                                $(this).parent().closest("tr").hide();
                            } else {
                                $(this).parent().closest("tr").show();
                            }
                        })
                    }
                })
            }
        }

    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</html>