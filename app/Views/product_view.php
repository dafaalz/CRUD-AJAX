<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX CRUD CI4 Bootstrap MWAH</title>
    <!-- butstrep 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container my-5">
    <h1 class="mb-4 text-center">Products</h1>

    <!-- Form -->
    <form id="productForm" class="mb-4">
        <input type="hidden" name="id" id="id">
        <div class="row g-2">
            <div class="col-md-5">
                <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="price" id="price" class="form-control" placeholder="Price" required step="0.01">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Save</button>
            </div>
            <div class="col-md-2">
                <button type="button" id="resetForm" class="btn btn-secondary w-100">Reset</button>
            </div>
        </div>
    </form>

    <form id="searchForm" class="mb-4">
        <div class="row g-3">
            <div class="col-md-10">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </form>

    <!-- Table -->
    <table class="table table-striped table-bordered" id="productTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    // ambil semua produk dari server
    function fetchProducts($query = null) {
        $query = $('#search').val()
        $.get('products/fetch?query=' + $query, function(data){
            let rows = '';
            try {
                let products = (typeof data === 'string') ? JSON.parse(data) : data;
                products.forEach(p => {
                    rows += `<tr>
                                <td>${p.id}</td>
                                <td>${p.name}</td>
                                <td>${p.price}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning edit me-1" data-id="${p.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger delete" data-id="${p.id}">Delete</button>
                                </td>
                            </tr>`;
                });
            } catch(e) {
                console.error("Failed to parse JSON:", e, data);
                rows = '<tr><td colspan="4" class="text-center">Error loading data</td></tr>';
            }
            $('#productTable tbody').html(rows);
        }).fail(function(xhr) {
            console.error("Failed to fetch products:", xhr.responseText);
            $('#productTable tbody').html('<tr><td colspan="4" class="text-center">Failed to load data</td></tr>');
        });
    }
    // document.ready() buat nunggu html load 
    $(document).ready(function() {
        fetchProducts();

        $('#searchForm').submit(function(e){
            e.preventDefault()
            $query = $('#search').val()
            fetchProducts($query);
        })

        // reset form
        $('#resetForm').click(function(){
            $('#productForm')[0].reset();
            $('#productForm')[0].reset();
            $('#id').val('');
        });

        // save atau update product
        $('#productForm').submit(function(e){
            e.preventDefault();
            let id = $('#id').val();
            let url = id ? `products/update/${id}` : 'products/store';
            $.post(url, $(this).serialize(), function(response){
                try {
                    let res = (typeof response === 'string') ? JSON.parse(response) : response;
                    if(res.status === 'success') {
                        $('#productForm')[0].reset();
                        $('#id').val('');
                        fetchProducts();
                    } else {
                        alert('Operation failed');
                    }
                } catch(e) {
                    console.error("Failed to parse server response:", e, response);
                    alert('Server error');
                }
            }).fail(function(xhr){
                console.error("Request failed:", xhr.responseText);
                alert('Server error');
            });
        });

        // edit product
        $(document).on('click', '.edit', function() {
            let id = $(this).data('id');
            $.get(`products/edit/${id}`, function(data){
                try {
                    let p = (typeof data === 'string') ? JSON.parse(data) : data;
                    $('#id').val(p.id);
                    $('#name').val(p.name);
                    $('#price').val(p.price);
                } catch(e) {
                    console.error("Failed to parse JSON for edit:", e, data);
                    alert('Failed to load product for editing');
                }
            }).fail(function(xhr){
                console.error("Failed to get product:", xhr.responseText);
                alert('Server error');
            });
        });

        // delete product
        $(document).on('click', '.delete', function(){
            if(!confirm('Are you sure you want to delete this product?')) return;
            let id = $(this).data('id');
            $.ajax({
                url: `products/delete/${id}`,
                type: 'DELETE',
                success: function(response){
                    try {
                        let res = (typeof response === 'string') ? JSON.parse(response) : response;
                        if(res.status === 'success') {
                            fetchProducts();
                        } else {
                            alert('Delete failed');
                        }
                    } catch(e) {
                        console.error("Failed to parse delete response:", e, response);
                        alert('Server error');
                    }
                },
                error: function(xhr){
                    console.error("Failed to delete:", xhr.responseText);
                    alert('Server error');
                }
            });
        });
    });
</script>
<!-- butstrep 5 js bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>