<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

// Getting products data from product_info table
$sql_get_products = "SELECT * FROM products_info WHERE is_active='1' ORDER BY created_at DESC";
$result_get_products = mysqli_query($conn, $sql_get_products);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Info</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Products Info</h1>
    </div>
    <div class="px-5 grid grid-cols-1 md:grid-cols-1 justify-items-end gap-3 md:gap-0 mt-4">
        <div>
            <select name="search_type" id="search_type" class='focus:outline-none border px-6 py-3 rounded-xl whitespace-nowrap text-sm text-gray-900'>
                <option value="name">By Product Name</option>
                <option value="id">By Unique ID</option>
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Is Alloted</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Is Damage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Displaying data of products
                            if ($result_get_products) {
                                while ($row = mysqli_fetch_assoc($result_get_products)) {
                                    $sql_get_check = "SELECT * FROM product_master WHERE id='" . $row['product_id'] . "'";
                                    $result_get_check = mysqli_query($conn, $sql_get_check);
                                    $fetch_get_check = mysqli_fetch_assoc($result_get_check);
                                    echo "
                                    <tr>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <div class='flex items-center'>
                                            <div class='ml-4'>
                                                <div class='text-sm font-medium text-gray-900 searching_name'>" . $fetch_get_check['name'] . "</div>
                                            </div>
                                            </div>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <div class='text-sm text-gray-900 searching_id'>" . $row['ref_no'] . "</div>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <div class='text-sm text-gray-900'>" . (($row['is_alloted'] >= 1) ? 'Yes' : 'No') . "</div>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <div class='text-sm text-gray-900'>" . (($row['is_damage'] >= 1) ? 'Yes' : 'No') . "</div>
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
        } else if ($('#search_type').val() === 'id') {
            if ($('#searchbar').val() !== '', $('#searchbar').val() !== null) {
                $("tr").each(function(index) {
                    if (index !== 0) {
                        $(this).find('.searching_id').each(function() {
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