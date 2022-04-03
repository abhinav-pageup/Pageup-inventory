<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

if (isset($_POST['allotment'])) {
    if($_POST['returnDate']!=null){
        $sql_edit_allotment = "UPDATE alloted_products SET return_date='" . $_POST['returnDate'] . "', is_damage='" . $_POST['isDamage'] . "', returned_to='" . $_SESSION['admin'] . "' WHERE id='" . $_GET['return'] . "'";
        $result_edit_allotment = mysqli_query($conn, $sql_edit_allotment);
        if ($result_edit_allotment) {
            // Update damage and allot info in product_info table
            $sql_update_product_info = "UPDATE products_info SET is_alloted='0', is_damage='" . $_POST['isDamage'] . "' WHERE id IN (SELECT product_info_id FROM alloted_products WHERE id='" . $_GET['return'] . "')";
            $result_update_product_info = mysqli_query($conn, $sql_update_product_info);
            if ($result_update_product_info) {
                // Updating product_master allotment
                $sql_update_stock = "UPDATE product_master SET alloted=alloted-1 WHERE id IN (SELECT product_id FROM products_info WHERE id IN (SELECT product_info_id FROM alloted_products WHERE id='" . $_GET['return'] . "'))";
                $result_update_stock = mysqli_query($conn, $sql_update_stock);
                if ($result_update_stock) {
                    header("Location: /inventory/allotments.php");
                }
            }
        }
    }else{
        header("Location: /inventory/error.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Return</h1>
    </div>
    <?php
    $sql_select_one = "SELECT * FROM alloted_products WHERE id='" . $_GET['return'] . "'";
    $result_select_one = mysqli_query($conn, $sql_select_one);
    $select_one_rows = mysqli_num_rows($result_select_one);
    if ($select_one_rows > 0) {
        $row = mysqli_fetch_assoc($result_select_one);
        echo '
            <form class="mt-10 flex justify-center items-center flex-col" action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
                <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                    <label for="returnDate" class="text-slate-600 ml-full">Return Date:</label>
                    <input value="' . $row['return_date'] . '" type="date" name="returnDate" id="returnDate" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                </div>
                <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                    <label for="isDamage" class="text-slate-600 ml-full">Damage:</label>
                    <select name="isDamage" id="isDamage" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                        <option value="1" ' . (($row['is_damage'] >= 1) ? 'selected' : '') . '>True</option>
                        <option value="0" ' . (($row['is_damage'] == 0) ? 'selected' : '') . '>False</option>
                    </select>
                </div>
                <div class="flex justify-center items-center w-full mt-5">
                    <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="allotment">Submit</button>
                </div>
            </form>
            ';
    } elseif ($select_one_rows <= 0) {
        echo '
            <div class="flex justify-center items-center w-full mt-5">
                <h1 class="text-3xl text-red-600">Something Wrong...</h1>
            </div>
            ';
    }
    ?>
</body>
<script>
    function preventFutureDate(){
        const dateVar = document.getElementById('returnDate');
        const date = new Date
        const today = date.getFullYear() +`-${(date.getMonth()+1)<=9 ? '0': ''}`+ (date.getMonth()+1) +'-'+ date.getDate()
        console.log(today)

        dateVar.setAttribute('max', today.toString());
        dateVar.setAttribute('value', today.toString())
    }
    preventFutureDate();
</script>
</html>