<head>
    <title>Dashboard | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 mt-12 mb-10">
        <div class="flex justify-center items-center w-full flex-col mt-14">
            <h1 class="text-2xl text-slate-600">Hello!</h1>
            <h1 class="text-2xl text-slate-600">{{auth()->user()->name}}</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 px-8 md:px-24 mt-16 gap-8">
            <a href="/employees" class="flex justify-center items-center rounded-xl shadow-sm hover:shadow-xl transition-shadow duration-300 border flex-col py-10 gap-7">
                <h1 class="text-3xl">Employees</h1>
                <h1 class="text-2xl">{{$employees}}</h1>
            </a>
            <a href="/products" class="flex justify-center items-center rounded-xl shadow-sm hover:shadow-xl transition-shadow duration-300 border flex-col py-10 gap-7">
                <h1 class="text-3xl">Products</h1>
                <h1 class="text-2xl">{{$products}}</h1>
            </a>
            <a href="/product_info" class="flex justify-center items-center rounded-xl shadow-sm hover:shadow-xl transition-shadow duration-300 border flex-col py-10 gap-7">
                <h1 class="text-3xl">Items</h1>
                <h1 class="text-2xl">{{$items}}</h1>
            </a>
        </div>
    </div>
</x-layout>