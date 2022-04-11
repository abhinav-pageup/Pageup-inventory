<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | Pageup Inventory</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&display=swap" rel="stylesheet">
</head>
<body>
    <div class="flex justify-center items-center flex-col h-screen w-screen">
        <form action="/login" method="POST" class="flex justify-center items-center flex-col m-auto p-24 rounded-2xl shadow-xl transition-shadow hover:shadow-2xl duration-300 gap-3">
            @csrf
            <div>
                <img src="/images/logo1.png" alt="PageupSoft" width="130">
            </div>
            <x-forms.input label="Email:" name="email" type="email" required="true" />
            <x-forms.input label="Password:" name="password" type="password" required="true" />
            <div class="mt-5 w-full flex justify-center items-center">
                <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 rounded-md text-white">Login</button>
            </div>
        </form>
    </div>
</body>
</html>