<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX CRUD CI4 MWAh</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('css/products.css') ?>">
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

<script src="<?= base_url('js/products.js') ?>"></script>
</body>
</html>