<head>
    <title>Add Purchase | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 pt-12 flex justify-center items-center h-screen">
        <div class="flex justify-center items-center h-full w-full">
            <form action="/purchases" method="POST" class="m-auto justify-items-center">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-forms.select label="Product:" name="product_master_id" required="true">
                        @foreach ($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    </x-forms.select>
                    <x-forms.input label="Bill No:" name="bill_no" type="text" required="true" />
                    <x-forms.input label="Company:" name="company" type="text" required="true" />
                    <div class="flex flex-col">
                        <label for="quantity" class="mr-auto">Quantity:</label>
                        <input onkeyup="change()" type="text" id="quantity" name="quantity" class="active:outline-none focus:outline-none border ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none md:w-64" required value="{{old('quantity')}}">
                        @error('quantity')
                            <span class="text-sm text-red-500 mr-auto">{{$message}}</span>
                        @enderror
                    </div>
                    <x-forms.input label="Total Cost:" name="cost" type="text" required="true" />
                    <x-forms.input label="Date of Purchase:" name="date" type="date" required="true" />
                </div>
                <div id="form-inputs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">

                </div>
                <div class="mt-5 w-full flex justify-center items-center">
                    <x-forms.button confirm="Are you sure to add Purchase?" label="Submit" />
                </div>
            </form>
        </div>
    </div>
</x-layout>
<script>
    function change() {
        const quantity = $('#quantity');
        const form = $('#form-inputs');
        if(quantity.val() == 0 || quantity.val() == ''){
            form.remove();
            console.log('if')
        } else if(quantity.val() > 0){
            for (let index = 1; index <= quantity.val(); index++) {
                console.log('elseif', quantity.val())
                const html = '<div class="flex flex-col">\
                            <label for="unique'+index+'" class="mr-auto">Unique'+index+':</label>\
                            <input type="text" id="unique'+index+'" name="unique'+index+'" class="active:outline-none border-green-300 focus:outline-none border ring-yellow-200 active:ring focus:ring px-2 py-1 rounded-xl transition-all duration-300 outline-none md:w-64" required value="{{old("unique'+index+'")}}">\
                            @error("unique'+index+'")\
                                <span class="text-sm text-red-500 mr-auto">{{$message}}</span>\
                            @enderror\
                        </div>';
                form.append(html);
            }
        }
    }
    @if(count($errors) > 0)
    change();
    @endif
</script>