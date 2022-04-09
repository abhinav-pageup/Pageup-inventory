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
                            <td>{{ $user->emp_id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->joined_at }}</td>
                            <td class="flex flex-row gap-3">
                                <a href="/employees/{{$user->id}}/edit" onclick="toggleEdit()"
                                    class="px-3 py-1 text-white bg-blue-500 hover:bg-blue-600 rounded-lg">Edit</a>
                                <a href="#"
                                    class="px-3 py-1 text-white bg-red-500 hover:bg-red-600 rounded-lg">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
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
    <div id="add_emp_modal" class="hidden overflow-y-auto">
        <div
            class="md:ml-36 fixed top-0 left-0 bg-gray-600 bg-opacity-25 h-screen w-screen flex justify-center max-sm:items-start items-center overflow-auto">
            <div id="modal_content" class="bg-white rounded-xl p-6 overflow-auto mt-20 mb-10 relative">
                <div class="close-modal absolute top-3 right-3 p-1 px-2 border rounded-full hover:bg-red-500 hover:text-white transition-colors duration-300 cursor-pointer">
                    <i class="fas fa-times"></i>
                </div>
                <h1 class="text-center text-2xl my-5">Add Employee!</h1>
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
                        <x-forms.button label="Submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(isset($id))
    <div id="edit_emp_modal" class="overflow-y-auto">
        <div
            class="md:ml-36 fixed top-0 left-0 bg-gray-600 bg-opacity-25 h-screen w-screen flex justify-center max-sm:items-start items-center overflow-auto">
            <div id="modal_content" class="bg-white rounded-xl p-6 overflow-auto mt-20 mb-10 relative">
                <a href="/employees" class="absolute top-3 right-3 p-1 px-2 border rounded-full hover:bg-red-500 hover:text-white transition-colors duration-300 cursor-pointer">
                    <i class="fas fa-times"></i>
                </a>
                <h1 class="text-center text-2xl my-5">Edit Employee!</h1>
                <form action="/employees" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <x-forms.input type="text" label="ID:" name="emp_id" required="true" :value="old('emp_id', $user->emp_id)" />
                        <x-forms.input type="text" label="Name:" name="name" required="true" :value="old('name', $user->name)" />
                        <x-forms.input type="email" label="Email:" name="email" required="true" :value="old('email', $user->email)" />
                        <x-forms.input type="text" label="Phone:" name="phone" required="true" :value="old('phone', $user->phone)" />
                        <x-forms.input type="date" label="Joined At:" name="joined_at" required="true" :value="old('joined_at', $user->joined_at)" />
                    </div>
                    <div class="mt-5 w-full flex justify-center items-center">
                        <x-forms.button label="Submit" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-layout>
<script>
    $(document).ready(function() {
        $('#user_table').DataTable({
            responsive: true
        });
    });

    $('.close-modal').click(()=>{
        $('#add_emp_modal').hide();
        $('#edit_emp_modal').hide();
    })

    function toggleAddEmp() {
        $('#add_emp_modal').show();
    }

    function toggleEdit(){
        $('#edit_emp_modal').show();
    }

    @if (count($errors) > 0)
        $('#add_emp_modal').show();
    @endif
</script>
