
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
