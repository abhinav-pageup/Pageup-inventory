<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
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
<body>
    <div class="w-[250px] bg-indigo-700 h-screen pt-3 transition-all duration-300 fixed top-0 left-0 max-lg:-translate-x-[250px] rounded-tr-2xl rounded-br-2xl" id="sidebar">
        <h1 class="text-white text-4xl text-center">Inventory</h1>
        <ul class="text-white leading-[50px] mt-16 text-xl">
            <a href="/inventory/index.php" class="block pl-10 m-3 <?php if($_SERVER['REQUEST_URI']=='/inventory/index.php' || $_SERVER['REQUEST_URI']=='/inventory/addEmployee.php' || $_SERVER['REQUEST_URI']=='/inventory/' || substr($_SERVER['REQUEST_URI'], 0, 27)=='/inventory/editEmployee.php'){
                echo 'bg-indigo-800 rounded-lg';
            } ?>">Employees</a>
            <a href="/inventory/purchases.php" class="block pl-10 m-3 <?php if($_SERVER['REQUEST_URI']=='/inventory/purchases.php' || $_SERVER['REQUEST_URI']=='/inventory/addPurchases.php' || substr($_SERVER['REQUEST_URI'], 0, 27)=='/inventory/editPurchase.php'){
                echo 'bg-indigo-800 rounded-lg';
            } ?>">Purchases</a>
            <a href="/inventory/stock.php" class="block pl-10 m-3 <?php if($_SERVER['REQUEST_URI']=='/inventory/stock.php' || $_SERVER['REQUEST_URI']=='/inventory/addStock.php' || substr($_SERVER['REQUEST_URI'], 0, 24)=='/inventory/editStock.php'){
                echo 'bg-indigo-800 rounded-lg';
            } ?>">Products</a>
            <a href="/inventory/products.php" class="block pl-10 m-3 <?php if($_SERVER['REQUEST_URI']=='/inventory/products.php' || $_SERVER['REQUEST_URI']=='/inventory/addProduct.php' || substr($_SERVER['REQUEST_URI'], 0, 26)=='/inventory/editProduct.php'){
                echo 'bg-indigo-800 rounded-lg';
            } ?>">Product Information</a>
            <a href="/inventory/allotments.php" class="block pl-10 m-3 <?php if($_SERVER['REQUEST_URI']=='/inventory/allotments.php' || $_SERVER['REQUEST_URI']=='/inventory/addAllotment.php' || substr($_SERVER['REQUEST_URI'], 0, 28)=='/inventory/editAllotment.php'){
                echo 'bg-indigo-800 rounded-lg';
            } ?>">Allotment</a>
        </ul>
        <div class="max-lg:fixed max-lg:top-4 max-lg:left-3 max-lg:translate-x-[250px] lg:hidden">
            <div id="toggle">
                <div class="w-[35px] h-[5px] rounded-xl bg-slate-500 mb-1"></div>
                <div class="w-[35px] h-[5px] rounded-xl bg-slate-500 mb-1"></div>
                <div class="w-[35px] h-[5px] rounded-xl bg-slate-500"></div>
            </div>
        </div>
    </div>
</body>
<script>
    const menu = document.getElementById('toggle');
    const sidebar = document.getElementById('sidebar');
    menu.addEventListener('click', ()=>{
        if(sidebar.classList.contains('max-lg:-translate-x-[250px]')){
            sidebar.classList.remove('max-lg:-translate-x-[250px]');
        }else{
            sidebar.classList.add('max-lg:-translate-x-[250px]');
        }
    })
</script>
</html>