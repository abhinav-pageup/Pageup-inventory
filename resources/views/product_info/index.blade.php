<head>
    <title>Product Info | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 mt-12">
        <div class="mx-5 px-10 bg-white py-10 rounded-xl flex-nowrap responsive" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
            <table id="product_info_table" class="display dt-responsive nowrap w-full" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Product</th>
                        <th>Bill No.</th>
                        <th>Ref No.</th>
                        <th>Is Alloted</th>
                        <th>Is Damage</th>
                        <th>Company</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $product->purchase->product->name }}</td>
                        <td>{{ $product->purchase->bill_no }}</td>
                        <td>{{ $product->ref_no }}</td>
                        <td>{{ $product->is_alloted==1?'Yes':'No' }}</td>
                        <td>{{ $product->is_damage==1?'Yes':'No' }}</td>
                        <td>{{ $product->purchase->company }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Sno</th>
                        <th>Product</th>
                        <th>Bill No.</th>
                        <th>Ref No.</th>
                        <th>Is Alloted</th>
                        <th>Is Damage</th>
                        <th>Company</th>
                    </tr>
                </tfoot>
            </table>
        </div>
</x-layout>
<script>
    $(document).ready(function() {
        $('#product_info_table').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return '<h1 class="text-xl text-slate-600 mb-6">Details for '  + data[1] + '</h1>';
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll()
                }
            }
        });
    });
</script>