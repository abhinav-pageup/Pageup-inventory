<?php
if(!isset($_SESSION['admin'])){
    header("Location: /inventory/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend:{
                    screens:{
                        'max-2xl': {'max': '1535px'},

                        'max-xl': {'max': '1279px'},

                        'max-lg': {'max': '1023px'},

                        'max-md': {'max': '767px'},

                        'max-sm': {'max': '639px'},
                    }
                }
            }
        }
    </script>
</head>
<body class="lg:ml-[250px]">
    <nav class="h-14 shadow-md relative">
        <div class="absolute h-full top-0 right-5 flex justify-center items-center flex-row">
            <h1 class="text-xl text-slate-600 mr-8">Hello, <?php echo $_SESSION['name']; ?></h1>
            <a href="/inventory/logout.php" class="bg-red-600 hover:bg-red-700 text-white text-xl px-2 rounded-lg py-1">Logout</a>
        </div>
    </nav>
</body>
</html>