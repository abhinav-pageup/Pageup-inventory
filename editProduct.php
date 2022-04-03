<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

// Getting selected data detail
$sql_product_one = "SELECT * FROM products_info WHERE id='" . $_GET['edit'] . "'";
$result_product_one = mysqli_query($conn, $sql_product_one);

// Updating data
if (isset($_POST['purchase'])) {
    $sql_edit_product = "UPDATE products_info SET is_alloted=" . $_POST['isAlloted'] . ", is_damage=" . $_POST['isDamage'] . ", remark='" . $_POST['remark'] . "', update_at=current_timestamp(), update_by='".$_SESSION['admin']."' WHERE id='" . $_GET['edit'] . "' ";
    $result_edit_product = mysqli_query($conn, $sql_edit_product);
    if ($result_edit_product) {
        header("Location: /inventory/products.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products Info</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Edit Products Info</h1>
    </div>
    <?php
    $rows_product_one = mysqli_num_rows($result_product_one);
    if ($rows_product_one > 0 && $rows_product_one < 2) {
        $row = mysqli_fetch_assoc($result_product_one);
        echo '
            <form class="mt-10 flex justify-center items-center flex-col" action="' . $_SERVER['REQUEST_URI'] . '" method="POST">
                <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                    <label for="isAlloted" class="text-slate-600 ml-full">Alloted:</label>
                    <select name="isAlloted" id="isAlloted" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                        <option value="1" ' . (($row['is_alloted'] >= 1) ? 'selected' : '') . '>True</option>
                        <option value="0" ' . (($row['is_alloted'] == 0) ? 'selected' : '') . '>False</option>
                    </select>
                </div>
                <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                    <label for="isDamage" class="text-slate-600 ml-full">Damage:</label>
                    <select name="isDamage" id="isDamage" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                        <option value="1" ' . (($row['is_damage'] >= 1) ? 'selected' : '') . '>True</option>
                        <option value="0" ' . (($row['is_damage'] == 0) ? 'selected' : '') . '>False</option>
                    </select>
                </div>
                <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                    <label for="remark" class="text-slate-600 ml-full">Remark:</label>
                    <textarea name="remark" id="remark" rows="5" cols="5" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">' . $row['remark'] . '</textarea>
                </div>
                <div class="flex justify-center items-center w-full mt-5 mb-4">
                    <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="purchase">Submit</button>
                </div>
            </form>
            ';
    } else {
        echo '
            <div class="flex justify-center items-center w-full mt-5">
                <h1 class="text-3xl text-red-600">Something Wrong...</h1>
            </div>
            ';
    }
    ?>

</body>

</html>