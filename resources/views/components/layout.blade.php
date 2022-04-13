<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pageup Inventory</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&family=Ubuntu&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.jqueryui.min.css">
</head>
<style>
    * {
        font-family: 'Ubuntu', sans-serif;
    }
</style>

<body>
    <nav class="fixed top-0 md:left-64 h-12 w-full bg-white shadow-md z-50">
        <div class="flex justify-center items-start gap-3 w-full h-full relative">
            <div class="flex h-full justify-center items-center absolute top-0 left-5">
                @switch($view)
                @case('/')
                <a href="/" class="text-2xl font-bold">Dashboard</a>
                @break

                @case(Str::startsWith($view, 'employees'))
                <a href="/employees" class="text-2xl font-bold">Employees</a>
                @break

                @case(Str::startsWith($view, 'purchases'))
                <a href="/purchases" class="text-2xl font-bold">Purchases</a>
                @break

                @case(Str::startsWith($view, 'products'))
                <a href="/products" class="text-2xl font-bold">Products</a>
                @break

                @case(Str::startsWith($view, 'product_info'))
                <a href="product_info" class="text-2xl font-bold">Product Info</a>
                @break

                @case(Str::startsWith($view, 'allotments'))
                <a href="/allotments" class="text-2xl font-bold">Allotments</a>
                @break
                @endswitch
            </div>
            <div class="flex justify-center items-center flex-row gap-5 absolute h-full top-0 right-4 md:right-[20rem]">
                <div id="profile" onclick="toggleLogout()" class="bg-slate-200 pr-3 rounded-full flex flex-row gap-2 relative cursor-pointer h-min justify-center items-center">
                    <img src="/images/user.png" alt="Admin" width="20" class="rounded-full">
                    <h5>Hello, {{auth()->user()->name}}</h5>
                    <h1 class="text-md md:hidden"><i class="fa fa-angle-down"></i></h1>
                    <form id="res-logout" action="/logout" method="POST" class="hidden absolute top-8 left-10 bg-slate-100 px-3 py-1 rounded-xl hover:bg-red-600 hover:text-white">
                        @csrf
                        <button type="submit" class="rounded-xl">Logout</button>
                    </form>
                </div>
                <form method="POST" action="/logout" class="flex justify-center items-center flex-row max-md:hidden border-none m-auto">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white rounded-full px-2">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <nav id="sidebar" class="h-screen w-64 bg-white rounded-br-xl shadow-xl fixed top-0 left-0 max-md:-translate-x-full transition-transform duration-200 z-50">
        <div class="flex w-full justify-center items-center mt-2">
            <img src="/images/logo1.png" alt="PageupSoft" width="100">
        </div>
        <div class="flex justify-center items-center flex-col gap-3 mt-12 px-9">

            <a href="/" class="links flex justify-start items-center w-full pl-9 rounded-xl py-2 flex-row gap-3 {{ $view == '/' ? 'active-link' : 'inactive-link' }}">
                <i class="fa fa-chart-line"></i>
                <h1>Dashboard</h1>
            </a>

            <a href="/employees" class="links flex justify-start items-center w-full pl-9 rounded-xl py-2 flex-row gap-3 {{ Str::startsWith($view, 'employees') ? 'active-link' : 'inactive-link' }}">
                <i class="fa fa-users"></i>
                <h1>Employees</h1>
            </a>

            <a href="/products" class="links flex justify-start items-center w-full pl-9 rounded-xl py-2 flex-row gap-3 {{ Str::startsWith($view, 'products') ? 'active-link' : 'inactive-link' }}">
                <i class="fas fa-database"></i>
                <h1>Products</h1>
            </a>

            <a href="/purchases" class="links flex justify-start items-center w-full pl-9 rounded-xl py-2 flex-row gap-3 {{ Str::startsWith($view, 'purchases') ? 'active-link' : 'inactive-link' }}">
                <i class="fas fa-shopping-bag"></i>
                <h1>Purchases</h1>
            </a>

            <a href="/product_info" class="links flex justify-start items-center w-full pl-9 rounded-xl py-2 flex-row gap-3 {{ Str::startsWith($view, 'product_info') ? 'active-link' : 'inactive-link' }}">
                <i class="fas fa-mouse"></i>
                <h1>Products Info</h1>
            </a>

            <a href="/allotments" class="links flex justify-start items-center w-full pl-9 rounded-xl py-2 flex-row gap-3 {{ Str::startsWith($view, 'allotments') ? 'active-link' : 'inactive-link' }}">
                <i class="fa fa-user"></i>
                <h1>Allotment</h1>
            </a>

        </div>

        <div id="trigger" onclick="toggleNav()" class="absolute top-1/2 -right-[1.69rem] bg-slate-200 cursor-pointer md:hidden text-4xl px-1 rounded-r-md">
            <i class="fa fa-angle-right"></i>
        </div>

    </nav>


    {{ $slot }}

    @if(session()->has('success'))
    <div id="success_toast" class="fixed top-14 right-6">
        <div class="bg-green-500 rounded-lg animate-pulse px-3 py-2 flex justify-center items-center text-white">
            <h1>{{session('success')}}</h1>
        </div>
    </div>
    @endif
</body>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.jqueryui.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.jqueryui.min.js"></script>
<script>
    function toggleNav() {
        $('#sidebar').toggleClass('max-md:-translate-x-full');
    }

    function toggleLogout() {
        if ($(window).width() < 776) {
            $('#res-logout').toggleClass('hidden');
        }
    }

    function preventFutureDate() {
        const dateVar = $('.date')
        const date = new Date
        const today = date.getFullYear() + `-${(date.getMonth()+1)<=9 ? '0': ''}` + (date.getMonth() + 1) + '-' + date.getDate()

        dateVar.attr('max', today.toString());
    }
    preventFutureDate();

    @if(session()->has('success'))
    $('#success_toast').click(() => {
        $('#success_toast').hide();
    })
    setTimeout(() => {
        $('#success_toast').hide();
    }, 5000);
    @endif
</script>

</html>