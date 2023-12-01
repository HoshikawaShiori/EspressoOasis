@extends('layouts.layoutAdmin')
@section('title')
    Products
@endsection
@section('content')
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
                                                <h5 class="card-title text-center">Total Number of Products</h5>
                                            </div>
                                            <div class="col-auto">
                                                <div class="stat text-primary">
                                                    <i class="align-middle" data-feather="users"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="mt-1 mb-3 text-center">{{ count($coffees) }}</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 d-flex align-items-end">
                                <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">Add Product</button>
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
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Variants</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coffees as $coffee)
                            <tr>
                                <th scope="row">{{ $coffee->id }}</th>
                                <td><img src="{{ asset($coffee->imagePath) }}" alt="{{ $coffee->title }}"
                                        class="img-thumbnail" style="height: 100px; width:100px;"></td>
                                <td>{{ $coffee->title }}</td>
                                <td>
                                    @php
                                        $sizes = $coffee->sizes;
                                    @endphp
                                    @if (!empty($sizes))
                                        <ul>
                                            @foreach ($sizes as $size)
                                                <li>
                                                    Size: {{ $size['label'] }}
                                                    Price: {{ $size['price'] }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        No sizes available
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-inline m-0">
                                        <li class="list-inline-item">
                                            <button class="btn btn-success btn-sm rounded-0" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"
                                                data-bs-toggle="modal" data-bs-target="#editModal-{{ $coffee->id }}"
                                                id="#edit-{{ $coffee->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </li>
                                        <li class="list-inline-item">
                                            <a id="del-{{ $coffee->id }}"
                                                href="{{ route('coffee.destroy', ['id' => $coffee->id]) }}"
                                                class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            {{-- edit modal --}}
                            <div class="modal fade" id="editModal-{{ $coffee->id }}" tabindex="-1"
                                aria-labelledby="editModal-{{ $coffee->id }}" aria-hidden="true">
                                <div class="modal-dialog  d-flex container-fluid">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Product</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container col">
                                                <form method="POST"
                                                    action="{{ route('editProduct', ['id' => $coffee->id]) }}"
                                                    id="editForm-{{ $coffee->id }}" name="addForm"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <img id="frame-{{ $coffee->id }}"
                                                        src="{{ asset($coffee->imagePath) }}"
                                                        class="img-fluid rounded-circle border-0"
                                                        style="height:200px; width:200px;" />
                                                    <div class="mb-2">
                                                        <label for="imagePath-{{ $coffee->id }}"
                                                            class="form-label">Product Image</label>
                                                        <input accept="image/*" class="form-control" type="file"
                                                            id="imagePath-{{ $coffee->id }}" name="EditimagePath"
                                                            onchange="preview{{ $coffee->id }}()">
                                                        <label for="Edittitle-{{ $coffee->id }}">Product
                                                            name:</label>
                                                        <input type="text" class="form-control" placeholder="Name"
                                                            id="Edittitle-{{ $coffee->id }}" name="Edittitle"
                                                            value="{{ $coffee->title }}" required>
                                                    </div>
                                                    <div id="jsonFields-{{ $coffee->id }}" class="col-md-6">
                                                        @foreach ($coffee->sizes as $index => $size)
                                                            <div class="row jsonField">
                                                                <div class="col">
                                                                    <label for="Editsize[]">Size:</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Size" maxlength="1"
                                                                        name="Editsize[]" id="Editsize[]"
                                                                        value="{{ $size['label'] }}" required>
                                                                </div>
                                                                <div class="col">
                                                                    <label for="Editprice[]">Price:</label>
                                                                    <input type="number" class="form-control"
                                                                        placeholder="Price" min="20"
                                                                        name="Editprice[]" id="Editprice[]"
                                                                        value="{{ $size['price'] }}" required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" class="btn btn-primary mt-1"
                                                        id="addJsonField{{ $coffee->id }}">Add
                                                        Field</button>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>

                                                            <input type="submit" form="editForm-{{ $coffee->id }}"
                                                                class="btn btn-primary" value="Submit">
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                function preview{{ $coffee->id }}() {
                                    var frame = document.getElementById('frame-{{ $coffee->id }}');
                                    frame.src = URL.createObjectURL(event.target.files[0]);
                                }
                    
                                function clearImage{{ $coffee->id }}() {
                                    document.getElementById('imagePath-{{ $coffee->id }}').value = null;
                                    document.getElementById('frame-{{ $coffee->id }}').src = "";
                                }
                                document.getElementById('addJsonField{{ $coffee->id }}').addEventListener('click', function() {
                                    var jsonFields = document.getElementById('jsonFields-{{ $coffee->id }}');
                                    var newField = document.createElement('div');
                                    newField.classList.add('row', 'jsonField');
                                    newField.innerHTML = `                                                                <div class="col">
                                                                                        <label for="Editsize[]">Size:</label>
                                                                                        <input type="text" class="form-control"
                                                                                            placeholder="Size" maxlength="1"
                                                                                            name="Editsize[]"
                                                                                            id="Editsize[]"
                                                                                            value="" required>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <label
                                                                                            for="Editprice[]">Price:</label>
                                                                                        <input type="number" class="form-control"
                                                                                            placeholder="Price" min="20"
                                                                                            name="Editprice[]"
                                                                                            id="Editprice[]"
                                                                                            value="20" required>
                                                                                    </div>`;
                                    jsonFields.appendChild(newField);
                                });
                            </script>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        {{-- add modal --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog  d-flex container-fluid">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container col">
                            <form method="POST" id="addForm" name="addForm" action="{{ route('saveProduct') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <img id="frame" src="" class="img-fluid rounded-circle border-0"
                                    style="height:200px; width:200px;" />
                                <button type="button" onclick="clearImage()" class="btn btn-secondary mt-2 pb-2">Clear
                                    Image</button>
                                <div class="mb-2">
                                    <label for="imagePath" class="form-label">Product Image</label>
                                    <input class="form-control" type="file" id="imagePath" name="imagePath"
                                        onchange="preview()" required>
                                    <label for="title">Product name:</label>
                                    <input type="text" class="form-control" placeholder="Name" id="title"
                                        name="title" required>
                                </div>
                                <div id="jsonFields" class="col-md-6">
                                    <div class="row jsonField">
                                        <div class="col">
                                            <label for="size[]">Size:</label>
                                            <input type="text" class="form-control" placeholder="Size" maxlength="1"
                                                name="size[]" id="size[]" required>
                                        </div>
                                        <div class="col">
                                            <label for="price[]">Price:</label>
                                            <input type="number" class="form-control" placeholder="Price"
                                                min="20" name="price[]" id="price[]" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary mt-1" id="addJsonField">Add Field</button>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <input type="submit" form="addForm" class="btn btn-primary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
@endsection
@section('Navscripts')
    <script>
        function preview() {
            var frame = document.getElementById('frame');
            frame.src = URL.createObjectURL(event.target.files[0]);
        }

        function clearImage() {
            document.getElementById('imagePath').value = null;
            document.getElementById('frame').src = "";
        }

        document.getElementById('addJsonField').addEventListener('click', function() {
            var jsonFields = document.getElementById('jsonFields');
            var newField = document.createElement('div');
            newField.classList.add('row', 'jsonField');
            newField.innerHTML = `
            <div class="col">
                <label for="size[]">Size</label>
                <input type="text" class="form-control" placeholder="Size" maxlength="1" name="size[]" required>
            </div>
            <div class="col">
                <label for="price[]">Price</label>
                <input type="number" class="form-control" placeholder="Price" min="20" name="price[]" required>
            </div>
        `;
            jsonFields.appendChild(newField);
        });
    </script>
@endsection
