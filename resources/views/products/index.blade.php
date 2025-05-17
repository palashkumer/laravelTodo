<x-app-layout>

    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 d-flex justify-content-end ">
                <a href="{{ route('products.create') }}" class="btn btn-dark">Create Product</a>
            </div>
        </div>


        @if (Session('success'))
            <div class="col-md-10 mt-4">
                <div class="alert alert-success" id="success-alert">
                    {{ Session('success') }}
                </div>
            </div>
        @endif

        <div class="col-md-10">
            <div class="card border-0 shadow-lg my-4">
                <div class="card-header bg-dark d-flex justify-content-center p-3">
                    <h3 class="text-white text-xl">Products</h3>
                </div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID</th>
                            <th></th>
                            <th>Name</th>
                            <th>Sku</th>
                            <th>Price</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>

                        @if ($products->isNotEmpty())
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if ($product->image != '')
                                            <img width="60"
                                                src="{{ asset('/uploads/products/' . $product->image) }}"
                                                alt="">
                                        @else
                                            No image
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->sku }}</td>
                                    <td>${{ $product->price }}</td>

                                    <td>{{ \Carbon\Carbon::parse($product->created_at)->format('d M, Y') }}</td>


                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-dark">Edit</a>
                                        <a href="#" onclick="deleteProduct({{ $product->id }});"
                                            class="btn btn-danger">Delete</a>
                                        <form id="delete-product-form-{{ $product->id }}"
                                            action="{{ route('products.destroy', $product->id) }}" method="post">
                                            @csrf
                                            @method ('delete')
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </table>

                </div>


            </div>
        </div>
    </div>
    </div>


    <script>
        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.getElementById('delete-product-form-' + id).submit();
            }
        }
    </script>
</x-app-layout>
