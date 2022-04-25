<head>
    <title>Products | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 mt-12">
        <div class="mt-20 mb-10 flex justify-between items-center px-10">
            <a href="/purchases/purchase/create"
                class="px-3 py-1 rounded-lg text-white bg-green-400 hover:bg-green-500">Add
                Purchase</a>
        </div>
        <div class="mx-5 px-10 bg-white py-10 rounded-xl flex-nowrap responsive"
            style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
            <table id="purchase_table" class="display dt-responsive nowrap w-full data_tables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Product</th>
                        <th>Bill No.</th>
                        <th>Company</th>
                        <th>Quantity</th>
                        <th>Date of Purchase</th>
                        <th>Cost</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $purchase->product->name }}</td>
                            <td>{{ $purchase->bill_no }}</td>
                            <td>{{ $purchase->company==null?'Not Mentioned':$purchase->company }}</td>
                            <td>{{ $purchase->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                            <td>{{ $purchase->cost }}</td>
                            <td>{{ $purchase->remark?$purchase->remark:'Not Mentioned' }}</td>
                            <td class="flex flex-row gap-3 justify-center items-center">
                                @if($purchase->items->where('is_alloted', 1)->count() == 0)
                                <a href="/purchases/{{ $purchase->id }}/edit"
                                    class="px-3 py-1 text-white bg-blue-500 hover:bg-blue-600 rounded-lg h-min">Edit</a>
                                <form method="POST" action="/purchases/{{ $purchase->id }}"
                                    class="h-full flex justify-center items-center m-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure to Delete?')"
                                        class="px-3 py-1 text-white bg-red-500 hover:bg-red-600 rounded-lg">Delete</button>
                                </form>
                                @else
                                    <button class="text-white bg-red-400 rounded-md px-2 py-1">Alloted to someone</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</x-layout>
