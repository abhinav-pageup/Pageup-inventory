@props(['required' => 'false', 'name', 'label', 'rows', 'cols'])
<div class="flex flex-col mt-4">
    <label for="{{ $name }}" class="mr-auto">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{$rows}}" cols="{{$cols}}" class="active:outline-none focus:outline-none border resize-none ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none w-full" {{ $required == 'true' ? 'required' : '' }}>{{ $slot }}</textarea>
    @error($name)
        <span class="text-sm text-red-500 mr-auto">{{ $message }}</span>
    @enderror
</div>
