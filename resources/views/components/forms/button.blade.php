@props(['label', 'confirm' => 'Are you sure?'])
<button type="submit" onclick="return confirm('{{$confirm}}')" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 rounded-md text-white">{{$label}}</button>