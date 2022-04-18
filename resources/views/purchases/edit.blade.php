<head>
    <title>Edit Purchase | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 pt-12 flex justify-center items-center h-screen">
        <div class="flex justify-center items-center h-full w-full">
            <form action="/purchases/{{$purchase->id}}" method="POST" class="m-auto justify-items-center">
                <h1 class="mb-4 text-2xl ml-auto text-center">Edit Purchase!</h1>
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-forms.select label="Product:" name="product_master_id" required="true">
                        @foreach ($products as $product)
                        <option value="{{$product->id}}" {{old('product_master_id', $purchase->product_master_id) == $product->id?'selected':''}}>{{$product->name}}</option>
                        @endforeach
                    </x-forms.select>
                    <x-forms.input label="Bill No:" name="bill_no" type="text" required="true" :value="old('bill_no', $purchase->bill_no)" />
                    <x-forms.input label="Company:" name="company" type="text" :value="old('company', $purchase->company)" />
                    <input type="hidden" id="quantity" name="quantity" value="{{old('quantity', $purchase->quantity)}}">
                    <x-forms.input label="Total Cost:" name="cost" type="text" required="true" :value="old('cost', $purchase->cost)" />
                    <x-forms.input label="Date of Purchase:" name="date" type="date" :value="old('date', $purchase->date)" required="true" />
                </div>
                <div id="form-inputs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                @if(old('quantity') !== null)
                @for ($i = 0; $i < old('quantity'); $i++)
                <div class="flex flex-col" id="unique{{$i}}">
                        <label for="unique[]" class="mr-auto">Unique{{$i+1}}:</label>
                        <div class="flex flex-row gap-4">
                            <input type="text" id="unique[]" name="unique[]" class="active:outline-none border-green-300 focus:outline-none border ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none md:w-64" required value="{{old('unique.'.$i)}}">
                            <button type="button" onclick="remove('unique{{$i}}')"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="flex flex-row gap-1">
                            <label for="damage[unique{{$i}}]" class="mr-auto">Is Damage:</label>
                            <input type="checkbox"  {{old('damage.unique'.$i)?'checked':''}} id="damage[unique{{$i}}]" name="damage[unique{{$i}}]" class="mr-40 mt-1">
                        </div>
                        @error("unique.".$i)
                        <span class="text-sm text-red-500 mr-auto">{{$message}}</span>
                        @enderror
                </div>
                @endfor
                @else
                @foreach($productInfo as $info)
                <div class="flex flex-col" id="unique{{$loop->index}}">
                        <label for="unique[]" class="mr-auto">Unique{{$loop->index + 1}}:</label>
                        <div class="flex flex-row gap-4">
                            <input type="text" id="unique[]" name="unique[]" class="active:outline-none border-green-300 focus:outline-none border ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none md:w-64" required value="{{$info->ref_no}}">
                            <button type="button" onclick="remove('unique{{$loop->index}}')"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="flex flex-row gap-1">
                            <label for="damage[unique{{$loop->index}}]" class="mr-auto">Is Damage:</label>
                            <input type="checkbox" {{$info->is_damage===1?'checked':''}} id="damage[unique{{$loop->index}}]" name="damage[unique{{$loop->index}}]" class="mr-40 mt-1">
                        </div>
                </div>
                @endforeach
                @endif
        </div>
        <x-forms.textarea name="remark" label="Remark" required="true" rows="3" cols="3">{{old('remark', $purchase->remark)}}</x-forms.textarea>
        <div class="mt-5 w-full flex justify-center items-center flex-row gap-3">
            <x-forms.button confirm="Are you sure to Edit this Purchase?" label="Submit" />
            <h1 onclick="uniqueField()" class="text-white bg-green-500 hover:bg-green-600 rounded-md px-2 py-1 cursor-pointer select-none">Add Unique Field</h1>
        </div>
        </form>
    </div>
    </div>
</x-layout>
<script>
    const form = $('#form-inputs');
    const quantity = $('#quantity');
    function uniqueField(){
        const childs = form.children().length;
        var html = `<div class="flex flex-col" id="unique${childs}">
                        <label for="unique[]" class="mr-auto">Unique${childs+1}:</label>
                        <div class="flex flex-row gap-4">
                            <input type="text" id="unique[]" name="unique[]" class="active:outline-none border-green-300 focus:outline-none border ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none md:w-64" required">
                            <button type="button" onclick="remove('unique${childs}')"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="flex flex-row gap-1">
                            <label for="damage[unique${childs}]" class="mr-auto">Is Damage:</label>
                            <input type="checkbox" id="damage[unique${childs}]" name="damage[unique${childs}]" class="mr-40 mt-1">
                        </div>
                </div>`;
        quantity.val(+quantity.val()+1)
        form.append(html);
    }

    function remove(id){
        if(form.children().length > 1){
            $('#'+id).remove();
            quantity.val(+quantity.val()-1)
        }
    }
</script>