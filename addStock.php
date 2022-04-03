<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

$error = "";
if (isset($_POST['product'])) {
    // Checking the product already available in product_master or not
    $sql_check_available = "SELECT *, COUNT(*) AS product_available FROM product_master WHERE name='" . $_POST['productName'] . "'";
    $result_check_available = mysqli_query($conn, $sql_check_available);
    $fetch_check_available = mysqli_fetch_assoc($result_check_available);

    // Validations
    if (empty($_POST['productName']) || empty($_POST['productType'])) {
        $error = "Empty Field";
    } else if ($fetch_check_available['product_available'] != 0) {
        $error = "Already Exist";
    } else {
        // Inserting into product_master
        $sql_add_product = "INSERT INTO product_master (name, type, stock, is_active, created_at,  created_by, update_at) VALUES ('" . $_POST['productName'] . "', '" . $_POST['productType'] . "', '0', '1', current_timestamp(), '" . $_SESSION['admin'] . "', null)";
        $result_add_product = mysqli_query($conn, $sql_add_product);
        if ($result_add_product) {
            header("Location: /inventory/stock.php");
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
    <title>Add Product</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Add Product</h1>
    </div>
    <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
    <form class="mt-10 flex justify-center items-center flex-col" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
            <label for="productName" class="text-slate-600 ml-full">Product Name:</label>
            <input type="text" name="productName" id="productName" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
        </div>
        <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
            <label for="productType" class="text-slate-600 ml-full">Product Type:</label>
            <select name="productType" id="productType" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                <option value="Electronic" selected>Electronic</option>
                <option value="Household">Household</option>
            </select>
        </div>
        <div class="flex justify-center items-center w-full mt-5">
            <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="product">Submit</button>
        </div>
    </form>
</body>

</html>