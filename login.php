<?php
    session_start();
    if(isset($_SESSION['admin']) && isset($_SESSION['name'])){
        if($_SESSION['admin'] && $_SESSION['name']){
            header("location: ./index.php");
        }
    }
    $error = "";
    include 'database/dbconn.php';
    if(isset($_POST['login'])){
        if(!preg_match('/[^a-z_\-0-9]/i', $_POST['username'])){
            $sql_login = "SELECT * FROM employees WHERE username='".$_POST['username']."' AND is_admin='1' AND is_approve='1'";
            $result_login = mysqli_query($conn, $sql_login);
            $fetch_login = mysqli_fetch_assoc($result_login);
            $rows_login = mysqli_num_rows($result_login);
            if($rows_login == '1'){
                if(password_verify($_POST['password'], $fetch_login['password'])){
                    $_SESSION['admin'] = $fetch_login['id'];
                    $_SESSION['name'] = $fetch_login['name'];
                    header("Location: ./index.php");
                }else{
                    $error = "Incorrect Username or Password";
                }
            }else{
                $error = "Incorrect Username or Password";
            }
        }else{
            $error = "Alphanumeric not Supported";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="h-screen w-screen flex justify-center items-center flex-col">
        <h1 class="text-5xl text-indigo-400">Login</h1>
        <div class="flex justify-center items-center w-full mt-6">
            <h1 class="text-center text-red-600 text-2xl"><?php echo $error ?></h1>
        </div>
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" class="flex justify-center items-center flex-col">
            <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                <label for="username" class="text-slate-600 ml-full">Username:</label>
                <input type="text" name="username" id="username" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-start flex-col w-72 sm:w-96 mt-5">
                <label for="password" class="text-slate-600 ml-full">Password:</label>
                <input type="password" name="password" id="password" class="mt-2 p-2 shadow-sm rounded-lg focus:outline-0 focus:ring ring-indigo-600 border transition-all duration-300 w-full">
            </div>
            <div class="flex justify-center items-center w-full mt-5">
                <button class="pt-2 pb-3 px-4 text-white bg-indigo-600 rounded-lg focus:ring ring-offset-2 ring-indigo-600" name="login">Login Now</button>
            </div>
            <div class="flex justify-center items-center w-full mt-6">
                <a href="/inventory/signup.php" class="text-blue-500 underline">Sign Up</a>
            </div>
        </form>
    </div>
</body>
</html>