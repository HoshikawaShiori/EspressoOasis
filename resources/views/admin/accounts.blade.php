@extends('layouts.layoutAdmin')
@section('title')
    Accounts
@endsection
@section('content')
@include('partials.admintopNav')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <main class="content">
        <div class="container round bg-white p-2 p-0">
            <div class="row pb-2">
                <div class="col-xl-6 col-xxl-5 d-flex">
                    <div class="w-100">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col mt-0">
                                                <h5 class="card-title text-center">Total number of accounts</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="users"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3 text-center">{{ count($users) }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex align-items-end">
                                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Create Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Email</th>
                            <th scope="col">Username</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $user->id }}</th>

                                <td>{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        {{ $role->name  }}
                                    @endforeach  </td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <button class="btn btn-success btn-sm rounded-0" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#modal{{ $user->id }}"
                                                id="#editmodal{{ $user->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item">
                                            <a id="del-{{ $user->id }}"
                                                href="{{ route('user.destroy', ['id' => $user->id]) }}"
                                                class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <div class="modal fade" id="modal{{ $user->id }}" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
                                <div class="modal-dialog  d-flex container-fluid">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="modal">Edit Account</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container col">
                                                <form method="POST" action="{{ route('account.edit', ['id' => $user->id]) }}" id="editForm{{ $user->id }}" name="editForm">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label for="editemail{{ $user->id }}">Email:</label>
                                                        <input type="text" class="form-control" placeholder="editemail" id="editemail{{ $user->id }}"
                                                            name="editemail" value="{{ $user->email }}" required>
                                                        <label for="editusername{{ $user->id }}">Username:</label>
                                                        <input type="editusername" class="form-control" placeholder="username" minlength="6" id="editusername{{ $user->id }}"
                                                            name="editusername" value="{{ $user->username }}" required>
                                                        <p class="text-sm text-danger">Leave password field blank if there is no update intent</p>
                                                        <label for="editpassword{{ $user->id }}">Password:</label>
                                                        <input type="editpassword" class="form-control" placeholder="Password" minlength="8" id="editpassword{{ $user->id }}"
                                                            name="editpassword">
                                                        <label for="editconfirm_password{{ $user->id }}">Confirm Password:</label>
                                                        <input type="editpassword" class="form-control" minlength="8" placeholder="Confirm Password"
                                                            id="editconfirm_password{{ $user->id }}"  name="editconfirm_password">
                                                        <label for="editrole{{ $user->id }}">Select a role:</label>
                                                        <select class="form-select form-control mt-3" aria-label="Select a role" id="editrole{{ $user->id }}" name="editrole" required>
                                                                @foreach ($user->roles as $role)
                                                                <option value="1" {{ $role->id == 1 ? 'selected' : '' }}>admin</option>
                                                                <option value="2" {{ $role->id== 2 ? 'selected' : '' }}>user</option>
                                                                <option value="3" {{ $role->id == 3 ? 'selected' : '' }}>superadmin</option>
                                                                <option value="4" {{ $role->id == 4 ? 'selected' : '' }}>attendant</option>
                                                                @endforeach           
                                                        </select>
                    
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <input type="submit" id="submitedit{{ $user->id }}" form="editForm{{ $user->id }}" class="btn btn-primary">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <script>
                                    $(document).ready(function() {  
                                    var selectedRole = $(this).find(":selected").text(); 
                                    $('#editForm{{ $user->id }}').attr('action', `{{ route('account.create', ['role' => ':roleName']) }}`.replace(':roleName', selectedRole));
                            
                            
                                        $('#role').on('change', function() {
                                            var selectedRole = $(this).find(":selected").text();
                                            var createAccountUrl = "{{ route('account.create', ['role' => 'selectedRole']) }}";
                                            createAccountUrl = createAccountUrl.replace('selectedRole', selectedRole);
                                            $('#createForm').attr('action', createAccountUrl);
                                        });
                                    });
                                </script>

                                <script>
                                    var password = document.getElementById("editpassword{{ $user->id }}"),
                                        confirm_password = document.getElementById("editconfirm_password{{ $user->id }}");

                                    function validatePassword() {
                                        if (password.value != confirm_password.value) {
                                            confirm_password.setCustomValidity("Passwords Don't Match");
                                            console.log(password.value + "||" + confirm_password.value);
                                        } else {
                                            confirm_password.setCustomValidity('');
                                        }
                                    }

                                    password.onchange = validatePassword;
                                    confirm_password.onkeyup = validatePassword;
                                </script>
                                
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog  d-flex container-fluid">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Accout</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container col">
                            <form method="POST" action="" id="createForm" name="createForm">
                                @csrf
                                <div class="mb-2">
                                    <label for="email">Email:</label>
                                    <input type="text" class="form-control" placeholder="email" id="email"
                                        name="email" required>
                                    <label for="username">Username:</label>
                                    <input type="username" class="form-control" placeholder="username" id="username"
                                        name="username" required>
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" placeholder="Password" id="password"
                                        name="password" required>
                                    <label for="confirm_password">Confirm Password:</label>
                                    <input type="password" class="form-control" placeholder="Confirm Password"
                                        id="confirm_password" name="confirm_password" required>
                                    <label for="role">Select a role:</label>
                                    <select class="form-select form-control mt-3" aria-label="Select a role" id="role" name="role" required>
                                            <option value="1" selected>attendant</option>
                                            <option value="2">admin</option>
                                            <option value="3">superadmin</option>
                                    </select>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <input type="submit" form="createForm" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('scripts')
    <script>
        var password = document.getElementById("password"),
            confirm_password = document.getElementById("confirm_password");

        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
                console.log(password.value + "||" + confirm_password.value);
            } else {
                confirm_password.setCustomValidity('');
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>

    <script>
        $(document).ready(function() {  
        var selectedRole = $(this).find(":selected").text(); 
        $('#createForm').attr('action', `{{ route('account.create', ['role' => ':roleName']) }}`.replace(':roleName', selectedRole));


            $('#role').on('change', function() {
                var selectedRole = $(this).find(":selected").text();
                var createAccountUrl = "{{ route('account.create', ['role' => 'selectedRole']) }}";
                createAccountUrl = createAccountUrl.replace('selectedRole', selectedRole);
                $('#createForm').attr('action', createAccountUrl);
            });
        });
    </script>


@endsection
