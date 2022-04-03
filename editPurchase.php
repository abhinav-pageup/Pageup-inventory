<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';
include 'chromephp/ChromePhp.php';
if (isset($_GET['edit'])) {
    // Getting information of purchase
    $sql_get_purchase = "SELECT * FROM purchases WHERE id='" . $_GET['edit'] . "'";
    $result_get_purchase = mysqli_query($conn, $sql_get_purchase);
    $fetch_get_purchase = mysqli_fetch_assoc($result_get_purchase);

    // Getting information from products_info where purchase_id is this
    $sql_get_products_info = "SELECT * FROM products_info WHERE purchase_id='" . $_GET['edit'] . "'";
    $result_get_products_info = mysqli_query($conn, $sql_get_products_info);
    $rows_get_products = mysqli_num_rows($result_get_products_info);
}


$error = '';
$array = [];
if (isset($_POST['purchase'])) {
    $child = 0;
    for ($i=1; $i < 100; $i++) { 
        if(!empty($_POST['unique'.$i.''])){
            $child++;
        }
    }

    // Checking bill_no already exist in database or not
    $sql_check_bill = "SELECT *, COUNT(*) AS bill_count FROM purchases WHERE id!='" . $_GET['edit'] . "' AND bill_number='" . $_POST['billNo'] . "'";
    $result_check_bill = mysqli_query($conn, $sql_check_bill);
    $fetch_check_bill = mysqli_fetch_assoc($result_check_bill);

    // Checking ref_no already in database or not
    $check_ref_no = false;
    for ($i = 1; $i <= 100; $i++) {
        if (!empty($_POST['unique' . $i . ''])) {
            array_push($array, $_POST['unique' . $i . '']);
            $sql_check_ref_no = "SELECT COUNT(*) AS ref_count FROM products_info WHERE ref_no='" . $_POST['unique' . $i . ''] . "' AND purchase_id!='" . $_GET['edit'] . "'";
            $result_check_ref_no = mysqli_query($conn, $sql_check_ref_no);
            $fetch_check_ref_no = mysqli_fetch_assoc($result_check_ref_no);
            if ($fetch_check_ref_no['ref_count'] >= 1) {
                $check_ref_no = true;
            }
        }
    }

    // Checking user entring duplicate ref_no
    $check_input = false;
    for ($i = 1; $i <= 100; $i++) {
        if (!empty($_POST['unique' . $i . ''])) {
            if ($_POST['quantity'] != 1) {
                if ($i != $_POST['quantity'] && !empty($_POST['unique' . ($i + 1) . ''])) {
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
    }
    if ($check_input) {
        $error = 'Duplicate Entry';
    } else if ($check_ref_no) {
        $error = 'Already In Database';
    } else if ($fetch_check_bill['bill_count']) {
        $error = "Bill Number Already Exist";
    } else {
        echo $child;
        $sql_delete_products = "DELETE FROM products_info WHERE purchase_id='" . $_GET['edit'] . "'";
        $result_delete_products = mysqli_query($conn, $sql_delete_products);
        if ($result_delete_products) {
            $sql_update_product_master_1 = "UPDATE product_master SET stock=stock-" . $rows_get_products . " WHERE id='" . $fetch_get_purchase['product_id'] . "'";
            $result_update_product_master_1 = mysqli_query($conn, $sql_update_product_master_1);
            if ($result_update_product_master_1) {
                for ($i = 1; $i < 100; $i++) {
                    if (!empty($_POST['unique' . $i . ''])) {
                        $sql_insert_products = "INSERT INTO products_info (product_id, purchase_id, ref_no, created_at, is_damage) VALUES ('" . $fetch_get_purchase['product_id'] . "', '" . $_GET['edit'] . "', '" . $_POST['unique' . $i . ''] . "', '" . $fetch_get_purchase['created_at'] . "', " . (empty($_POST['damage' . $i . '']) ? '0' : '1') . ")";
                        $result_insert_products = mysqli_query($conn, $sql_insert_products);
                    }
                }
                $sql_update_product_master_2 = "UPDATE product_master SET stock=stock+" . $child . " WHERE id='" . $fetch_get_purchase['product_id'] . "'";
                $result_update_product_master_2 = mysqli_query($conn, $sql_update_product_master_2);
                if ($result_update_product_master_2) {
                    $sql_update_purchase = "UPDATE purchases SET date='" . $_POST['date'] . "', bill_number='" . $_POST['billNo'] . "', company='" . $_POST['company'] . "', cost='" . $_POST['cost'] . "', remark='" . $_POST['remark'] . "', quantity='" . $child . "' WHERE id='" . $_GET['edit'] . "'";
                    $result_update_purchase = mysqli_query($conn, $sql_update_purchase);
                    if ($result_update_purchase) {
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
    <title>Edit Purchase</title>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Edit Purchase</h1>
    </div>
    <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
    <form id="formParent" class="mt-10 flex justify-center items-center flex-col md:gap-5 gap-2" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="date" class="text-slate-600 ml-full">Date:</label>
                <input type="date" value="<?php echo $fetch_get_purchase['date'] ?>" name="date" id="date" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="billNo" id="billLabel" class="text-slate-600 ml-full">Bill Number:</label>
                <input type="text" onkeyup="checksBill()" value="<?php echo $fetch_get_purchase['bill_number'] ?>" name="billNo" id="billNo" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="company" class="text-slate-600 ml-full">Company:</label>
                <input type="text" value="<?php echo $fetch_get_purchase['company'] ?>" name="company" id="company" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="cost" class="text-slate-600 ml-full">Total Cost:</label>
                <input type="number" value="<?php echo $fetch_get_purchase['cost'] ?>" name="cost" id="cost" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="quantity" class="text-slate-600 ml-full">Quantity:</label>
                <input type="number" value="<?php echo $fetch_get_purchase['quantity'] ?>" name="quantity" id="quantity" onkeyup="change()" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96 w-full">
                <label for="remark" class="text-slate-600 ml-full">Remark:</label>
                <textarea name="remark" id="remark" rows='1' cols='1' class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full"><?php echo $fetch_get_purchase['remark'] ?></textarea>
            </div>
        </div>
        <div id="quantity-parent" class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">
            <?php
            $index = 1;
            while ($row = mysqli_fetch_assoc($result_get_products_info)) {
                echo '
                    <div class="flex flex-col w-72 sm:w-96" id="' . $index . '">
                        <label for="unique' . $index . '" class="text-slate-600 ml-full">Unique ' . $index . ':</label>
                        <div class="flex justify-center items-center flex-row">
                            <input type="text" value="' . $row['ref_no'] . '" name="unique' . $index . '" id="unique' . $index . '" class="child mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full border-green-600 w-full" onkeyup="checkUnique('.$index.')">
                            <input type="checkbox" name="damage' . $index . '" class="ml-4 mt-1" id="damage' . $index . '" ' . (($row['is_damage'] == '1') ? 'checked' : '') . '>
                            <a onclick="removeInput(' . $index . ')" class="pt-1 ml-4 cursor-pointer">&#10060;</a>
                        </div>
                    </div>';
                $index++;
            }
            ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 md:gap-5 gap-2">

        </div>
        <div class="flex justify-center items-center w-full mb-4 gap-3">
            <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="purchase" id="purchase">Submit</button>
            <a class="pt-2 pb-3 px-4 text-white bg-green-600 rounded-lg cursor-pointer hover:bg-green-700" id="add" onclick="addInput()" name="add">Add Unique Field</a>
        </div>
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function checksBill(){
        let checks = $("#billNo").val();
        let edit = window.location.href.split('=')[1];
        $.ajax({
            url: 'checkAddPurchase.php',
            type: 'post',
            data: {editBillNo:[checks, edit]},
            success:function(data, status){
                console.log(data)
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
        let edit = window.location.href.split('=')[1];
        $.ajax({
            url: 'checkAddPurchase.php',
            type: 'post',
            data: {editUnique:[value, edit]},
            success:function(data, status){
                console.log(data)
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

    function checkUnique2(id){
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


    const button = document.getElementById('add');
    const parent = document.getElementById('quantity-parent');
    const child = parent.childElementCount;
    let input = child;
    let quantity = document.getElementById('quantity');

    function addInput() {
        input++;
        html = `<div class="flex flex-col w-72 sm:w-96" id='${input}'>\
                    <label for="unique${input}" class="text-slate-600 ml-full">Unique ${input}:</label>\
                    <div class="flex justify-center items-center flex-row">\
                        <input type="text" onkeyup="checkUnique2(${input})" name="unique${input}" id="unique${input}" class="child mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full border-green-600 w-full">\
                        <a onclick="removeInput(${input})" class="pt-1 ml-4 cursor-pointer">&#10060;</a>\
                    </div>\
                </div>`;
        $("#quantity-parent").append(html)
        quantity.value++;
    }

    function removeInput(id) {
        const ele = document.getElementById(id);
        parent.removeChild(ele);
        quantity.value--;
    }

    function preventFutureDate() {
        const dateVar = document.getElementById('date');
        const date = new Date
        const today = date.getFullYear() + `-${(date.getMonth()+1)<=9 ? '0': ''}` + (date.getMonth() + 1) + '-' + date.getDate()

        dateVar.setAttribute('max', today.toString());
    }
    preventFutureDate();
</script>

</html>