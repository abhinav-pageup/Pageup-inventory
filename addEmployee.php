<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: /inventory/login.php");
}
include 'database/dbconn.php';

// Adding new Employee
$error = "";
if (isset($_POST['employee'])) {

    // Checking Employee exist or not
    $sql_check_same_id = "SELECT *,COUNT(*) AS emp_check FROM employees WHERE emp_id='" . $_POST['id'] . "'";
    $result_check_same_id = mysqli_query($conn, $sql_check_same_id);
    $fetch_check_same_id = mysqli_fetch_assoc($result_check_same_id);

    // Validations
    if ($fetch_check_same_id['emp_check'] != '0') {
        $error = "Employee Id Already Exist";
    } else if (empty($_POST['id']) || empty($_POST['fullname']) || empty($_POST['phone']) || empty($_POST['email']) || empty($_POST['joined'])) {
        $error = "Empty Field";
    } else if (strlen($_POST['phone']) != 10) {
        $error = "Incorrect Phone";
    } else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $_POST['email'])) {
        $error = "Incorrect Email";
    } else if (!preg_match("/^[a-zA-Z ]*$/", $_POST['fullname'])) {
        $error = "Incorrect Name";
    } else {
        // Inserting Detail in employees table
        $sql_add_employee = "INSERT INTO employees (emp_id, name, phone, email, created_at, created_by, joined_at) VALUES ('" . $_POST['id'] . "', '" . $_POST['fullname'] . "', '" . $_POST['phone'] . "', '" . $_POST['email'] . "', current_timestamp(), '" . $_SESSION['admin'] . "', '" . $_POST['joined'] . "')";
        $result_add_employee = mysqli_query($conn, $sql_add_employee);
        if ($result_add_employee) {
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
    <title>Add Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="lg:ml-[250px]">
    <?php include 'navbar.php' ?>
    <?php include 'sidebar.php' ?>
    <div class="flex justify-center items-center w-full mt-5">
        <h1 class="text-3xl">Add Employees</h1>
    </div>
    <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
    <form class="mt-10 flex justify-center items-center flex-col md:gap-5 gap-2" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 justify-items-center">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="id" class="text-slate-600 ml-full">Employee's ID:</label>
                <input required type="number" name="id" id="id" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="fullname" class="text-slate-600 ml-full">Employee's Full Name:</label>
                <input required type="text" name="fullname" id="fullname" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="phone" class="text-slate-600 ml-full">Employee's Mobile:</label>
                <input required type="number" name="phone" id="phone" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="email" class="text-slate-600 ml-full">Employee's Email:</label>
                <input required type="email" name="email" id="email" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96 col-span-2">
                <label for="joined" class="text-slate-600 ml-full">Date:</label>
                <input type="date" name="joined" id="joined" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
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
        dateVar.setAttribute('value', today)
    }
    preventFutureDate();
</script>

</html>