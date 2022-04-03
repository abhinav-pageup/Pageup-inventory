<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

// Fetching Employees for Form
$sql_employees = "SELECT * FROM employees WHERE is_active='1'";
$result_employees = mysqli_query($conn, $sql_employees);

// Adding new allotment and update stock in product_master table
$error = '';
if (isset($_POST['allotment'])) {
    if (!empty($_POST['productId']) && !empty($_POST['allotTo']) && !empty($_POST['allotDate'])) {
        // Checking the item is alloted or not
        $sql_check_available = "SELECT * FROM products_info WHERE ref_no='" . $_POST['productId'] . "'";
        $result_check_available = mysqli_query($conn, $sql_check_available);
        $fetch_check_available = mysqli_fetch_assoc($result_check_available);
        if ($fetch_check_available['is_alloted'] == '0') {
            // Checking the input employee and product is exist or not
            $sql_check = "SELECT (SELECT COUNT(*) FROM employees WHERE id='" . $_POST['allotTo'] . "') AS employee_count, (SELECT id FROM products_info WHERE ref_no='" . $_POST['productId'] . "') AS product_info_id, (SELECT COUNT(*) FROM products_info WHERE ref_no='" . $_POST['productId'] . "') AS product_count, (SELECT COUNT(*) FROM products_info WHERE is_alloted='1' AND ref_no='" . $_POST['productId'] . "') AS allot_check";
            $result_check = mysqli_query($conn, $sql_check);
            $fetch_check = mysqli_fetch_assoc($result_check);
            if ($fetch_check['employee_count'] == 1 && $fetch_check['product_count'] == 1) {
                // Inserting row in allot_return table
                $sql_add_employee = "INSERT INTO alloted_products (product_info_id, employee_id, alloted_date, alloted_by, remark) VALUES ('" . $fetch_check['product_info_id'] . "', '" . $_POST['allotTo'] . "', '".$_POST['allotDate']."', '".$_SESSION['admin']."', '".$_POST['remark']."')";
                $result_add_employee = mysqli_query($conn, $sql_add_employee);
                if ($result_add_employee) {
                    // Updating product_master allotment
                    $sql_update_stock = "UPDATE product_master SET alloted=alloted+1 WHERE id IN (SELECT product_id FROM products_info WHERE ref_no='" . $_POST['productId'] . "')";
                    $result_update_stock = mysqli_query($conn, $sql_update_stock);
                    if ($result_update_stock) {
                        $sql_update_products_info = "UPDATE products_info SET is_alloted='1' WHERE ref_no='" . $_POST['productId'] . "'";
                        $result_update_product_info = mysqli_query($conn, $sql_update_products_info);
                        if ($result_update_product_info) {
                            header("Location: /inventory/allotments.php");
                        }
                    }
                }
            } else {
                $error = "Product or Employee doesn't Exist";
            }
        } else {
            $error = "Already Alloted to Someone";
        }
    } else {
        $error = "Empty Field";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Allotment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">New Allotment</h1>
    </div>
    <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
    <form class="mt-10 flex justify-center items-center flex-col md:gap-5 gap-2" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="productId" class="text-slate-600 ml-full">Product Unique ID:</label>
                <input type="text" name="productId" id="productId" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="allotTo" class="text-slate-600 ml-full">Alloted To:</label>
                <select name="allotTo" id="allotTo" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                    <?php
                    while ($row =  mysqli_fetch_assoc($result_employees)) {
                        echo '
                            <option value="' . $row['id'] . '">' . $row['name'] . '</option>
                            ';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="allotDate" class="text-slate-600 ml-full">Allotment Date:</label>
                <input type="date" name="allotDate" id="allotDate" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="remark" class="text-slate-600 ml-full">Remark:</label>
                <textarea name="remark" rows="1" id="remark" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full"></textarea>
            </div>
        </div>
        <div class="flex justify-center items-center w-full">
            <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="allotment">Submit</button>
        </div>
    </form>

</body>
<script>
    function preventFutureDate(){
        const dateVar = document.getElementById('allotDate');
        const date = new Date
        const today = date.getFullYear() +`-${(date.getMonth()+1)<=9 ? '0': ''}`+ (date.getMonth()+1) +'-'+ date.getDate()
        console.log(today)

        dateVar.setAttribute('max', today.toString());
        dateVar.setAttribute('value', today.toString())
    }
    preventFutureDate();
</script>
</html>