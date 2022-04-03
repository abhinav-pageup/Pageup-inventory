<?php
    session_start();
    include 'database/dbconn.php';
    include "chromephp/ChromePhp.php";
    $sql_employees = "SELECT * FROM employees WHERE is_active='1'";
    $result_employees = mysqli_query($conn, $sql_employees);

    $error = "";
    if(isset($_POST['login'])){
        if(!empty($_POST['username']) || !empty($_POST['password']) || !empty($_POST['emp'])){
            if(!preg_match('/[^a-z_\-0-9]/i', $_POST['username'])){
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $sql_signup = "UPDATE employees SET username='".$_POST['username']."', password='".$password."', is_admin='1', is_approve='0' WHERE id='".$_POST['emp']."'";
                $result_signup = mysqli_query($conn, $sql_signup);
                if($result_signup){
                    header("Location: /inventory/login.php");
                }else{
                    $error = "Internal Error";
                }
            }else{
                $error = "Only Alphanumeric are allowed!!";
            }
        }else{
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
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="h-screen w-screen flex justify-center items-center flex-col">
        <h1 class="text-5xl text-indigo-400">Signup</h1>
        <div class="flex justify-center items-center w-full mt-6">
            <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
        </div>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5 justify-items-center">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="emp" class="text-slate-600 ml-full">Employees:</label>
                <select name="emp" id="emp" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
                    <?php
                    while ($row =  mysqli_fetch_assoc($result_employees)) {
                        echo '
                            <option value="' . $row['id'] . '">' . $row['name'] . '</option>
                            ';
                    }
                    ?>
                </select>
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96">
                <label for="username" class="text-slate-600 ml-full">Username:</label>
                <input type="text" name="username" id="username" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96 md:col-span-2">
                <label for="password" class="text-slate-600 ml-full">Password:</label>
                <input type="password" name="password" id="password" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-center w-full md:col-span-2">
                <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="login">Signup Now</button>
            </div>
        </form>
    </div>
</body>
</html>