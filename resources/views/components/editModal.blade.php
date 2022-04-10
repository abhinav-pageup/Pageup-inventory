@props(['name', 'href', 'label'])
<div id="{{$name}}" class="overflow-y-auto">
    <div
        class="md:ml-36 fixed top-0 left-0 bg-gray-600 bg-opacity-25 h-screen w-screen flex justify-center max-sm:items-start items-center overflow-auto">
        <div id="modal_content" class="bg-white rounded-xl p-6 overflow-auto mt-20 mb-10 relative">
            <a href="{{$href}}" class="absolute top-3 right-3 p-1 px-2 border rounded-full hover:bg-red-500 hover:text-white transition-colors duration-300 cursor-pointer">
                <i class="fas fa-times"></i>
            </a>
            <h1 class="text-center text-2xl my-5">{{$label}}</h1>
            {{$slot}}
        </div>
    </div>
</div>