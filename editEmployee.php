<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';
$error = "";

// Getting selected data detail
$sql_select_one = "SELECT * FROM employees WHERE id='" . $_GET['edit'] . "'";
$result_select_one = mysqli_query($conn, $sql_select_one);
$select_one_rows = mysqli_num_rows($result_select_one);
if ($select_one_rows == 1) {
    $fetch_select_one = mysqli_fetch_assoc($result_select_one);
} else {
    $error = "Duplicate Entries";
}

// Updating Employees detail
if (isset($_POST['employee'])) {
    // Checking Employee exist or not
    $sql_check_employee = "SELECT *, COUNT(*) AS emp_count FROM employees WHERE id='" . $_GET['edit'] . "'";
    $result_check_employee = mysqli_query($conn, $sql_check_employee);
    $fetch_check_employee = mysqli_fetch_assoc($result_check_employee);

    // Validations
    if ($fetch_check_employee['emp_count'] != '1') {
        $error = "Employee Already Exist or Not";
    } else if (empty($_POST['id']) || empty($_POST['fullname']) || empty($_POST['phone']) || empty($_POST['email']) || empty($_POST['joined'])) {
        $error = "Empty Field";
    } else if (strlen($_POST['phone']) != 10) {
        $error = "Incorrect Phone";
    } else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $_POST['email'])) {
        $error = "Incorrect Email";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $_POST['fullname'])) {
        $error = "Incorrect Name";
    } else {
        // Updatind Detail in employees table
        $sql_edit_employee = "UPDATE employees SET emp_id='" . $_POST['id'] . "', name='" . $_POST['fullname'] . "', phone='" . $_POST['phone'] . "', email='" . $_POST['email'] . "', update_at=current_timestamp(), update_by='" . $_SESSION['admin'] . "', joined_at='" . $_POST['joined'] . "' WHERE id='" . $_GET['edit'] . "'";
        $result_edit_employee = mysqli_query($conn, $sql_edit_employee);
        if ($result_edit_employee) {
            header("Location: /inventory/index.php");
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
    <title>Edit Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Edit Employees</h1>
    </div>
    <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
    <form class="mt-10 flex justify-center items-center flex-col md:gap-5 gap-2" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 justify-items-center">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="id" class="text-slate-600 ml-full">Employee's ID:</label>
                <input required value="<?php echo $fetch_select_one['emp_id'] ?>" type="number" name="id" id="id" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="fullname" class="text-slate-600 ml-full">Employee's Full Name:</label>
                <input required value="<?php echo $fetch_select_one['name'] ?>" type="text" name="fullname" id="fullname" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="phone" class="text-slate-600 ml-full">Employee's Mobile:</label>
                <input required value="<?php echo $fetch_select_one['phone'] ?>" type="number" name="phone" id="phone" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="email" class="text-slate-600 ml-full">Employee's Email:</label>
                <input required value="<?php echo $fetch_select_one['email'] ?>" type="email" name="email" id="email" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96 col-span-2">
                <label for="joined" class="text-slate-600 ml-full">Date:</label>
                <input type="date" value="<?php echo $fetch_select_one['joined_at'] ?>" name="joined" id="joined" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
        </div>
        <div class="flex justify-center items-center w-full">
            <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="employee">Submit</button>
        </div>
    </form>
</body>
<script>
    function preventFutureDate() {
        const dateVar = document.getElementById('joined');
        const date = new Date
        const today = date.getFullYear() + `-${(date.getMonth()+1)<=9 ? '0': ''}` + (date.getMonth() + 1) + '-' + date.getDate()

        dateVar.setAttribute('max', today.toString());
    }
    preventFutureDate();
</script>

</html>