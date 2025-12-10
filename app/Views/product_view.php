<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX CRUD CI4 MWAh</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<style>
    :root {
        --main: #D6F4ED;    /* 60% */
        --secondary: #87BAC3; /* 30% */
        --accent: #53629E;   /* 10% */
        --deep: #473472;
    }

    body {
        background-color: var(--main);
        margin: 0;
        font-family: 'Arial', sans-serif;
        color: #222;
    }

    .app-container {
        width: 92%;
        max-width: 900px;
        margin: 45px auto;
        background: var(--secondary);
        padding: 28px;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(0,0,0,0.18);
    }

    h1 {
        text-align: center;
        color: var(--deep);
        font-size: 26px;
        margin-bottom: 30px;
        font-weight: 700;
    }

    /* --- FORM LAYOUT --- */
    .form-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .form-col {
        flex: 1;
        min-width: 150px;
        display: flex;
        flex-direction: column;
    }

    input.form-control {
        background: var(--main);
        border: 1px solid var(--accent);
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 15px;
    }

    button {
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 15px;
        cursor: pointer;
        border: none;
        transition: 0.2s;
    }

    .btn-custom {
        background: var(--accent);
        color: #fff;
    }
    .btn-custom:hover { opacity: 0.85; }

    .btn-warning-custom {
        background: var(--deep);
        color: #fff;
    }
    .btn-warning-custom:hover { opacity: .85; }

    .btn-danger-custom {
        background: var(--accent);
        color: #fff;
    }

    /* --- TABLE --- */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        border-radius: 10px;
        overflow: hidden;
        background: var(--secondary);
    }

    table th {
        background: var(--accent);
        color: #fff;
        padding: 12px;
        font-size: 15px;
        text-align: left;
    }

    table td {
        color: #fff;
        text-shadow: #222;
        padding: 12px;
        border-top: 1px solid rgba(0,0,0,0.1);
    }

    .actions {
        text-align: center;
    }

    .small-btn {
        padding: 6px 10px;
        border-radius: 6px;
        margin: 0 2px;
    }
</style>
</head>
<body>
<div class="app-container">
    <h1 class="mb-4 text-center">Products</h1>

    <!-- Form -->
    <form id="productForm" class="mb-4">
        <input type="hidden" name="id" id="id">
        <div class="form-row">
            <div class="form-col">
                <input type="text" name="name" id="name" class="form-control" placeholder="Product Name" required>
            </div>
            <div class="form-col">
                <input type="number" name="price" id="price" class="form-control" placeholder="Price" required step="0.01">
            </div>
            <div class="form-col">
                <button type="submit" class="btn-custom">Save</button>
            </div>
            <div class="form-col">
                <button type="button" id="resetForm" class="btn-warning-custom">Reset</button>
            </div>
        </div>
    </form>

    <form id="searchForm" class="mb-4">
        <div class="form-row">
            <div class="form-col">
                <input type="text" name="search" id="search" class="form-control" placeholder="Search">
            </div>
            <div class="form-col">
                <button type="submit" class="btn-custom">Search</button>
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
                <th class="actions">Actions</th>
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
                                <td class="actions">
                                    <button class="small-btn btn-warning-custom edit" data-id="${p.id}">Edit</button>
                                    <button class="small-btn btn-danger-custom delete" data-id="${p.id}">Delete</button>
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
</body>
</html>