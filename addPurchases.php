<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

$product_exist = 0;
$error = "";
$bill_exist = 0;
$info_array = [];
$unique_array = [];
$session = false;
// Add new purchase with updating in product_master table
if (isset($_POST['purchase'])) {
    // Checking prodct available in product_master or not
    if ($_POST['productType'] == 'electric') {
        if(empty($_POST['productElec'])){
            $error = "Invalid Product";
        }else{
            $sql_check = "SELECT (SELECT COUNT(*) FROM product_master WHERE id='" . $_POST['productElec'] . "' AND is_active='1') AS product_count, (SELECT COUNT(*) FROM purchases WHERE bill_number='".$_POST['billNo']."') AS bill_count";
            $result_check = mysqli_query($conn, $sql_check);
            $fetch_check = mysqli_fetch_assoc($result_check);
            $product_exist = $fetch_check['product_count'];
            $bill_exist = $fetch_check['bill_count'];
        }
    } elseif ($_POST['productType'] == 'household') {
        if(empty($_POST['productHouse'])){
            $error = "Invalid Product";
        }else{
            $sql_check = "SELECT (SELECT COUNT(*) FROM product_master WHERE id='" . $_POST['productHouse'] . "' AND is_active='1') AS product_count, (SELECT COUNT(*) FROM purchases WHERE bill_number='".$_POST['billNo']."') AS bill_count";
            $result_check = mysqli_query($conn, $sql_check);
            $fetch_check = mysqli_fetch_assoc($result_check);
            $product_exist = $fetch_check['product_count'];
            $bill_exist = $fetch_check['bill_count'];
        }
    }

    $check_ref_no = false;
    for ($i = 1; $i <= $_POST['quantity']; $i++) {
        if (empty($_POST['unique' . $i . ''])) {
            $check_ref_no = true;
        } else {
            $sql_check_ref_no = "SELECT COUNT(*) AS ref_count FROM products_info WHERE ref_no='" . $_POST['unique' . $i . ''] . "'";
            $result_check_ref_no = mysqli_query($conn, $sql_check_ref_no);
            $fetch_check_ref_no = mysqli_fetch_assoc($result_check_ref_no);
            if ($fetch_check_ref_no['ref_count'] >= 1) {
                $check_ref_no = true;
            }
        }
    }
    $check_input = false;
    for ($i = 1; $i <= $_POST['quantity']; $i++) {
        if ($_POST['quantity'] != 1) {
            if ($i != $_POST['quantity']) {
                if ($_POST['unique' . $i . ''] == $_POST['unique' . ($i + 1) . '']) {
                    $check_input = true;
                }
            } else {
                if ($_POST['unique1'] == $_POST['unique' . $_POST['quantity'] . '']) {
                    $check_input = true;
                }
            }
        }
    }

    // Pushing to an array when error occur
    // function backup(){
    //     if(!empty($_POST['productHouse'])){
    //         $info_array = [$_POST['productType'], $_POST['productHouse'], $_POST['date'], $_POST['billNo'], $_POST['quantity'], $_POST['company'], $_POST['cost'], $_POST['remark']];
    //     }else if(!empty($_POST['productElec'])){
    //         $info_array = [$_POST['productType'], $_POST['productElec'], $_POST['date'], $_POST['billNo'], $_POST['quantity'], $_POST['company'], $_POST['cost'], $_POST['remark']];
    //     }
    //     for ($i=1; $i < 100; $i++) { 
    //         if(!empty($_POST['unique'.$i.''])){
    //             array_push($GLOBALS['unique_array'], $_POST['unique'.$i.'']);
    //         }
    //     }
    //     $_SESSION['product_info'] = $info_array;
    //     $_SESSION['unique_info'] = $GLOBALS['unique_array'];
    // }
    // Validations
    if ($check_ref_no) {
        $error = "Some Unique Value is already in database or empty entry";
        // backup();
    } else if ($check_input) {
        $error = "Duplicate Entry";
        // backup();
    } else if (empty($_POST['productType']) || empty($_POST['billNo']) || empty($_POST['quantity']) || empty($_POST['cost']) || empty($_POST['date'])) {
        $error = "Some Entry are Blank";
        // backup();
    } else if($product_exist != 1){
        $error = "Product Already Exist or Not Exist";
        // backup();
    }else if($error == "Invalid Product"){
        $error = "Invalid Product";
        // backup();
    }else if($bill_exist != 0){
        $error = "Bill Number Already Exist";
        // backup();
    } else {
        // Inserting to purchases table
        if ($_POST['productType'] == 'electric') {
            $sql_add_product = "INSERT INTO purchases (product_id, bill_number, company, quantity, cost, date, remark, created_by) VALUES ('" . $_POST['productElec'] . "', '" . $_POST['billNo'] . "', '" . $_POST['company'] . "', '" . $_POST['quantity'] . "', '" . $_POST['cost'] . "', '" . $_POST['date'] . "', '" . $_POST['remark'] . "', '" . $_SESSION['admin'] . "')";
            $result_add_product = mysqli_query($conn, $sql_add_product);
        } elseif ($_POST['productType'] == 'household') {
            $sql_add_product = "INSERT INTO purchases (product_id, bill_number, company, quantity, cost, date, remark, created_by) VALUES ('" . $_POST['productHouse'] . "', '" . $_POST['billNo'] . "', '" . $_POST['company'] . "', '" . $_POST['quantity'] . "', '" . $_POST['cost'] . "', '" . $_POST['date'] . "', '" . $_POST['remark'] . "', '" . $_SESSION['admin'] . "')";
            $result_add_product = mysqli_query($conn, $sql_add_product);
        }

        if ($result_add_product) {
            $sql_add_product_id = "SELECT * FROM purchases WHERE bill_number='" . $_POST['billNo'] . "'";
            $result_add_product_id = mysqli_query($conn, $sql_add_product_id);
            $fetch_add_product_id = mysqli_fetch_assoc($result_add_product_id);
            // Inserting to product_info table
            for ($i = 1; $i <= $_POST['quantity']; $i++) {
                if ($_POST['productType'] == 'electric') {
                    $sql_add_product_info = "INSERT INTO products_info (product_id, purchase_id, ref_no) VALUES ('" . $_POST['productElec'] . "', '" . $fetch_add_product_id['id'] . "', '" . $_POST['unique' . $i . ''] . "')";
                    $result_add_product_info = mysqli_query($conn, $sql_add_product_info);
                } elseif ($_POST['productType'] == 'household') {
                    $sql_add_product_info = "INSERT INTO products_info (product_id, purchase_id, ref_no) VALUES ('" . $_POST['productHouse'] . "', '" . $fetch_add_product_id['id'] . "', '" . $_POST['unique' . $i . ''] . "')";
                    $result_add_product_info = mysqli_query($conn, $sql_add_product_info);
                }
            }
            if ($result_add_product_info) {
                // Inserting to the product_master table
                if ($_POST['productType'] == 'electric') {
                    $sql_update_stock = "UPDATE product_master SET stock=stock+" . $_POST['quantity'] . " WHERE id='" . $_POST['productElec'] . "'";
                    $result_update_stock = mysqli_query($conn, $sql_update_stock);
                    if ($result_update_stock) {
                        header("Location: /inventory/purchases.php");
                    }
                } elseif ($_POST['productType'] == 'household') {
                    $sql_update_stock = "UPDATE product_master SET stock=stock+" . $_POST['quantity'] . " WHERE id='" . $_POST['productHouse'] . "'";
                    $result_update_stock = mysqli_query($conn, $sql_update_stock);
                    if ($result_update_stock) {
                        header("Location: /inventory/purchases.php");
                    }
                }
            }
        }
    }
    // if(isset($_SESSION['product_info']) && isset($_SESSION['unique_info'])){
    //     $GLOBALS['session'] = true;
    //     echo var_dump($_SESSION['product_info']);
    // }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Purchase</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Add Purchase</h1>
    </div>
    <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
    <form id="formParent" class="mt-10 flex justify-center items-center flex-col md:gap-5 gap-2" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="productType" class="text-slate-600 ml-full">Product Type:</label>
                <select name="productType" id="productType" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full" onchange="product()">
                    <option value="electric">Electronic</option>
                    <option value="household">Household</option>
                </select>
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="productElec" class="text-slate-600 ml-full">Product:</label>
                <select name="productElec" id="productElec" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                    <?php
                    $sql_get_product_master = "SELECT * FROM product_master WHERE type='Electronic'";
                    $result_get_product_master = mysqli_query($conn, $sql_get_product_master);

                    while ($row = mysqli_fetch_assoc($result_get_product_master)) {
                        echo '
                            <option value="' . $row['id'] . '">' . $row['name'] . '</option>
                        ';
                    }
                    ?>
                </select>
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="productHouse" class="text-slate-600 ml-full">Product:</label>
                <select name="productHouse" id="productHouse" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                    <?php
                    $sql_get_product_master = "SELECT * FROM product_master WHERE type='Household'";
                    $result_get_product_master = mysqli_query($conn, $sql_get_product_master);

                    while ($row = mysqli_fetch_assoc($result_get_product_master)) {
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
                <label for="date" class="text-slate-600 ml-full">Date:</label>
                <input type="date" name="date" id="date" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="billNo" id="billLabel" class="text-slate-600 ml-full">Bill Number:</label>
                <input type="text" name="billNo" onkeyup="checksBill()" id="billNo" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="quantity" class="text-slate-600 ml-full">Quantity:</label>
                <input type="number" name="quantity" onkeyup="change()" id="quantity" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="company" class="text-slate-600 ml-full">Company:</label>
                <input type="text" name="company" id="company" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="cost" class="text-slate-600 ml-full">Total Cost:</label>
                <input type="number" name="cost" id="cost" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="remark" class="text-slate-600 ml-full">Remark:</label>
                <textarea name="remark" id="remark" rows='1' cols='5' class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full"></textarea>
            </div>
        </div>
        <div id="quantity-parent" class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2"></div>
        <div class="flex justify-center items-center w-full mb-4">
            <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="purchase">Submit</button>
        </div>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function checksBill(){
        let checks = $("#billNo").val();
        $.ajax({
            url: 'checkAddPurchase.php',
            type: 'post',
            data: {billNo:checks},
            success:function(data, status){
                if(data == '1'){
                    $("#billLabel").text("Bill Number: Already Exist");
                    $("#billLabel").addClass("text-red-500");
                    $("#billNo").removeClass('focus:ring ring-indigo-600');
                    $("#billNo").addClass('ring ring-red-600');
                }else{
                    $("#billLabel").text("Bill Number:");
                    $("#billLabel").removeClass("text-red-500");
                    $("#billNo").addClass('focus:ring ring-indigo-600');
                    $("#billNo").removeClass('ring ring-red-600');
                }
            }
        })
    }

    function checkUnique(id){
        let value = $(".child").serializeArray()[id-1].value;
        $.ajax({
            url: 'checkAddPurchase.php',
            type: 'post',
            data: {unique:value},
            success:function(data, status){
                if(data == '1'){
                    $(".child").removeClass('focus:ring ring-indigo-600 border-green-600');
                    $(".child").addClass('ring ring-red-600');
                }else{
                    $(".child").addClass('focus:ring ring-indigo-600 border-green-600');
                    $(".child").removeClass('ring ring-red-600');
                }
            }
        })
    }

    function zero() {
        const quantity = document.getElementById('quantity');
        if (quantity.value === '' || quantity.value === null || quantity.value === 0) {
            change();
        }
    }

    function change() {
        const quantity = document.getElementById('quantity');
        const form = document.getElementById('quantity-parent');
        const value = quantity
        form.innerHTML = '';
        if (form.childNodes.length < quantity.value) {
            for (let index = 1; index <= quantity.value; index++) {
                const html = '<div class="flex justify-center items-start flex-col w-72 sm:w-96">\
                                <label for="unique' + index + '" class="text-slate-600 ml-full">Unique ID ' + index + ':</label>\
                                <input onkeyup="checkUnique('+index+')" type="text" name="unique' + index + '" id="unique' + index + '" class="child mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full border-green-600">\
                            </div>';
                form.innerHTML += html;
            }
        } else {
            for (let index = 0; index < form.childNodes.length; index++) {
                const html = '<div class="flex justify-center items-start flex-col w-72 sm:w-96">\
                                <label for="unique' + index + '" class="text-slate-600 ml-full">Unique ID ' + index + ':</label>\
                                <input onkeyup="checkUnique('+index+')" type="text" name="unique' + index + '" id="unique' + index + '" class="child mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full border-green-600">\
                            </div>';
                form.innerHTML -= html;
                form.innerText = ''
            }
        }
        quantity.value = value.value
    }

    function product() {
        const productType = document.getElementById('productType');
        if (productType.value === 'electric') {
            document.getElementById('productHouse').parentNode.classList.add('hidden')
            document.getElementById('productElec').parentNode.classList.remove('hidden')
        } else if (productType.value === 'household') {
            document.getElementById('productHouse').parentNode.classList.remove('hidden')
            document.getElementById('productElec').parentNode.classList.add('hidden')
        }
    }
    product();

    function preventFutureDate() {
        const dateVar = document.getElementById('date');
        const date = new Date
        const today = date.getFullYear() + `-${(date.getMonth()+1)<=9 ? '0': ''}` + (date.getMonth() + 1) + '-' + date.getDate()

        dateVar.setAttribute('max', today.toString());
        dateVar.setAttribute('value', today.toString())
    }
    preventFutureDate();
</script>

</html>