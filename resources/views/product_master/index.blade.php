<head>
    <title>Products | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 mt-12">
        <div class="mt-20 mb-10 flex justify-between items-center px-10">
            <button onclick="toggleProductModal()"
                class="px-3 py-1 rounded-lg text-white bg-green-400 hover:bg-green-500">Add
                Product</button>
        </div>
        <div class="mx-5 px-10 bg-white py-10 rounded-xl flex-nowrap responsive"
            style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
            <table id="product_table" class="display dt-responsive nowrap w-full data_tables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Stock</th>
                        {{-- <th>Weight (For Household)</th> --}}
                        <th>No. of Attoted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->type }}</td>
                            <td>{{ $product->stock }}</td>
                            {{-- <td>{{ $product->weight?$product->weight:'Null' }}</td> --}}
                            <td>{{ $product->alloted }}</td>
                            <td class="flex flex-row gap-3 justify-center items-center">
                                <a href="/products/{{ $product->id }}/edit"
                                    class="px-3 py-1 text-white bg-blue-500 hover:bg-blue-600 rounded-lg h-min">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>    
            </table>
        </div>
    </div>
    <x-addModal name="add_product_modal" label="Add Product!">
        <form action="/products" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-forms.input type="text" label="Name:" name="name" required="true" />
                <x-forms.select label="Type:" name="type" required="true">
                    <option value="Electronic" {{old('type')=='Electronic'?'selected':''}}>Electronic</option>
                    <option value="Household" {{old('type')=='Household'?'selected':''}}>Household</option>
                </x-forms.select>
            </div>
            <div class="mt-5 w-full flex justify-center items-center">
                <x-forms.button confirm="Are you sure to add this Product?" label="Submit" />
            </div>
        </form>
    </x-addModal>
    @if (isset($editProduct))
        <x-editModal name="edit_product_modal" label="Edit Product!" href="/products">
            <form action="/products/{{$editProduct->id}}" method="POST">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-forms.input type="text" label="Name:" name="name" required="true" :value="old('name', $editProduct->name)" />
                    <x-forms.select label="Type:" name="type" required="true">
                        <option value="Electronic" {{old('type', $editProduct->type)=='Electronic'?'selected':''}}>Electronic</option>
                        <option value="Household" {{old('type', $editProduct->type)=='Household'?'selected':''}}>Household</option>
                    </x-forms.select>
                </div>
                <div class="mt-5 w-full flex justify-center items-center">
                    <x-forms.button confirm="Are you sure to add this Product?" label="Submit" />
                </div>
            </form>
        </x-editModal>
    @endif
</x-layout>
<script>
    function toggleProductModal() {
        $('#add_product_modal').show();
    }

    $('.close-modal').click(()=>{
        $('#add_product_modal').hide();
    })

    @if (count($errors) > 0)
        $('#add_product_modal').show();
    @endif
</script>
