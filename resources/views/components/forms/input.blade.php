@props(['type', 'required' => "false", 'name', 'label', 'value' => old($name)])
<div class="flex flex-col">
    <label for="{{$name}}" class="mr-auto">{{$label}}</label>
    <input type="{{$type}}" id="{{$name}}" name="{{$name}}" class="{{$type=='date'?'date':''}} active:outline-none focus:outline-none border ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none md:w-64" {{$required=="true"?'required':''}} value="{{$value}}">
    @error($name)
        <span class="text-sm text-red-500 mr-auto">{{$message}}</span>
    @enderror
</div>