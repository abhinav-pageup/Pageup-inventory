<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

// Getting Information from product_master table
$sql_get_products = "SELECT *, (stock-alloted) AS available FROM product_master WHERE is_active='1' ORDER BY created_at DESC";
$result_get_products = mysqli_query($conn, $sql_get_products);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Products</h1>
    </div>
    <div class="px-5 grid grid-cols-1 md:grid-cols-2 justify-items-center gap-3 md:gap-0 mt-4">
        <a href='/inventory/addStock.php' class='bg-green-600 font-bold ml-4 hover:bg-green-700 text-white p-2 rounded-lg px-3 col-span-1 w-max md:mr-auto h-min'>Add Products</a>
        <div>
            <select name="search_type" id="search_type" class='focus:outline-none border px-6 py-3 rounded-xl whitespace-nowrap text-sm text-gray-900'>
                <option value="name">By Name</option>
                <option value="type">By Type</option>
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">S.no.</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Stock</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            if ($result_get_products) {
                                $index = 1;
                                while ($row = mysqli_fetch_assoc($result_get_products)) {
                                    echo "
                                    <tr id='search-here'>
                                        <td class='px-6 py-4 whitespace-nowrap'>
                                            <div class='flex items-center'>
                                                <div class='ml-4'>
                                                    <div class='text-sm font-medium text-gray-900'>" .   $index . "</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900 searching_name'>" . $row['name'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900 searching_type'>" . $row['type'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . $row['stock'] . "</td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>
                                            " . $row['available'] . "
                                        </td>
                                        <td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>
                                            <a href='/inventory/editStock.php?edit=" . $row['id'] . "' class='bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg px-3'>Edit</a>
                                        </td>
                                    </tr>";
                                    $index++;
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
        // const name = $(".search_name");
        // const type = $(".search_type");
        // type.each(function(index, value){
        //     if($(this).text().toLowerCase().indexOf(search) > -1){
        //         $(this).parent().closest('tr').show();
        //     }else{
        //         $(this).parent().closest('tr').hide()
        //     }
        // })
        // name.each(function(index, value){
        //     if($(this).text().toLowerCase().indexOf(search) > -1){
        //         $(this).parent().closest('tr').show();
        //     }else{
        //         $(this).parent().closest('tr').hide()
        //     }
        // })


        // $("tr").each(function(index) {
        //     if (index !== 0) {
        //         $(this).find('.search_name').each(function() {
        //             var id = $(this).text().toLowerCase();
        //             if (id.indexOf(search) <= -1) {
        //                 $(this).parent().hide();
        //             } else {
        //                 $(this).parent().show();
        //             }
        //         })
        //     }
        // })

        if ($('#search_type').val() === 'name') {
            if ($('#searchbar').val() !== '', $('#searchbar').val() !== null) {
                $("tr").each(function(index) {
                    if (index !== 0) {
                        $(this).find('.searching_name').each(function() {
                            var id = $(this).text().toLowerCase();
                            if (id.indexOf(search) <= -1) {
                                $(this).parent().hide();
                            } else {
                                $(this).parent().show();
                            }
                        })
                    }
                })
            }
        } else if ($('#search_type').val() === 'type') {
            if ($('#searchbar').val() !== '', $('#searchbar').val() !== null) {
                $("tr").each(function(index) {
                    if (index !== 0) {
                        $(this).find('.searching_type').each(function() {
                            var id = $(this).text().toLowerCase();
                            if (id.indexOf(search) <= -1) {
                                $(this).parent().hide();
                            } else {
                                $(this).parent().show();
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