<head>
    <title>Employees | Pageup Inventory</title>
</head>
<x-layout>
    <div class="md:ml-64 mt-12">
        <div class="mt-20 mb-10 flex justify-between items-center px-10">
            <button onclick="toggleAddEmp()" class="px-3 py-1 rounded-lg text-white bg-green-400 hover:bg-green-500">Add
                Employee</button>
        </div>
        <div class="mx-5 px-10 bg-white py-10 rounded-xl flex-nowrap responsive"
            style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
            <table id="user_table" class="display dt-responsive nowrap w-full" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $user->emp_id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->joined_at }}</td>
                            <td class="flex flex-row gap-3 justify-center items-center">
                                <a href="/employees/{{ $user->id }}/edit" onclick="toggleEdit()"
                                    class="px-3 py-1 text-white bg-blue-500 hover:bg-blue-600 rounded-lg h-min">Edit</a>
                                <form method="POST" action="/employees/{{ $user->id }}"
                                    class="h-full flex justify-center items-center m-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure to Delete?')"
                                        class="px-3 py-1 text-white bg-red-500 hover:bg-red-600 rounded-lg">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Sno</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Joined At</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <x-addModal name="add_emp_modal" label="Add Employee!">
        <form action="/employees" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <x-forms.input type="text" label="ID:" name="emp_id" required="true" />
                <x-forms.input type="text" label="Name:" name="name" required="true" />
                <x-forms.input type="text" label="Email:" name="email" required="true" />
                <x-forms.input type="text" label="Phone:" name="phone" required="true" />
                <x-forms.input type="date" label="Joined At:" name="joined_at" required="true" />
            </div>
            <div class="mt-5 w-full flex justify-center items-center">
                <x-forms.button confirm="Are you sure to add user?" label="Submit" />
            </div>
        </form>
    </x-addModal>
    @if (isset($editUser))
        <x-editModal name="edit_emp_modal" label="Edit Employee!" href="/employees">
            <form action="/employees/{{ $editUser->id }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <x-forms.input type="text" label="ID:" name="emp_id" required="true" :value="old('emp_id', $editUser->emp_id)" />
                    <x-forms.input type="text" label="Name:" name="name" required="true" :value="old('name', $editUser->name)" />
                    <x-forms.input type="email" label="Email:" name="email" required="true" :value="old('email', $editUser->email)" />
                    <x-forms.input type="text" label="Phone:" name="phone" required="true" :value="old('phone', $editUser->phone)" />
                    <x-forms.input type="date" label="Joined At:" name="joined_at" required="true" :value="old('joined_at', $editUser->joined_at)" />
                </div>
                <div class="mt-5 w-full flex justify-center items-center">
                    <x-forms.button confirm="Are You sure to update this user?" label="Submit" />
                </div>
            </form>
        </x-editModal>
    @endif
</x-layout>
<script>
    $(document).ready(function() {
        $('#user_table').DataTable({
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details for ' + data[2];
                        }
                    }),
                    renderer: $.fn.dataTable.Responsive.renderer.tableAll()
                }
            }
        });
    });

    

    $('.close-modal').click(() => {
        $('#add_emp_modal').hide();
        $('#edit_emp_modal').hide();
    })

    function toggleAddEmp() {
        $('#add_emp_modal').show();
    }

    function toggleEdit() {
        $('#edit_emp_modal').show();
    }

    @if (count($errors) > 0)
        $('#add_emp_modal').show();
    @endif
</script>
