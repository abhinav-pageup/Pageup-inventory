<head>
    <title>Allotments | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 mt-12">
        <div class="mt-20 mb-10 flex justify-between items-center px-10">
            <button onclick="toggleProductModal()"
                class="px-3 py-1 rounded-lg text-white bg-green-400 hover:bg-green-500">Allot Product
        </div>
        <div class="mx-5 px-10 bg-white py-10 rounded-xl flex-nowrap responsive"
            style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
            <table id="allotment_table" class="display dt-responsive nowrap w-full" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Product</th>
                        <th>Emp</th>
                        <th>Allotment Date</th>
                        <th>Alloted By</th>
                        <th>Return Date</th>
                        <th>Return By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allotments as $allotment)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $allotment->items->purchase->product->name }}</td>
                            <td>{{ $allotment->user->name }}</td>
                            <td>{{ $allotment->alloted_date }}</td>
                            <td>{{ $allotment->alloted_by }}</td>
                            <td>{{ $allotment->returned_date==null?'Not Returned Yet':$allotment->returned_date }}</td>
                            <td>{{ $allotment->returned_by==null?'Not Returned Yet':$allotment->returned_by }}</td>
                            <td class="flex flex-row gap-3 justify-center items-center">
                                <a href="/allotments/{{ $allotment->id }}/return"
                                    class="px-3 py-1 text-white bg-blue-500 hover:bg-blue-600 rounded-lg h-min">Return</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Sno</th>
                        <th>Product</th>
                        <th>Emp</th>
                        <th>Allotment Date</th>
                        <th>Alloted By</th>
                        <th>Return Date</th>
                        <th>Return By</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
            <x-addModal name="allot_product_modal" label="Add Employee!">
                <form action="/employees" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <x-forms.select label="Product:" name="product_info_id" required="true">
                            @foreach ($items as $item)
                                <option value="{{$item->id}}">{{$item->ref_no}}</option>
                            @endforeach
                        </x-forms.select>
                        <x-forms.select label="Emp:" name="user_id" required="true">
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </x-forms.select>
                        <x-forms.input type="date" label="Allotment Date:" name="alloted_date" required="true" />
                    </div>
                    <div class="mt-5 w-full flex justify-center items-center">
                        <x-forms.button confirm="Are you sure to allot this product to this user?" label="Submit" />
                    </div>
                </form>
            </x-addModal>
            @if(isset($returnAllotment))
            <x-editModal label="Return Allotment!" name="return_attot_modal" href="/allotments">
                <form action="/allotments/{{ $returnAllotment->id }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-forms.input type="date" label="Return Date:" name="returned_at" required="true" :value="old('returned_at')" />
                        <x-forms.select label="Is Damage:" name="is_damage">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </x-forms.select>
                    </div>
                    <div class="mt-5 w-full flex justify-center items-center">
                        <x-forms.button confirm="Are You sure to Return?" label="Submit" />
                    </div>
                </form>
            </x-editModal>
            @endif
        </div>
</x-layout>
<script>
    $(document).ready(function() {
        $('#allotment_table').DataTable({
            responsive: true
        });
    });

    function toggleProductModal() {
        $('#allot_product_modal').show();
    }

    $('.close-modal').click(()=>{
        $('#allot_product_modal').hide();
    })

    @if (count($errors) > 0)
        $('#allot_product_modal').show();
    @endif
</script>