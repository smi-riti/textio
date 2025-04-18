@extends('admin.base')
@section('title', 'Dashboard')
@section('content')
<form class="row g-3 needs-validation p-12 mx-4 my-4" method="POST" action="#" enctype="multipart/form-data">
    <div class="col-md-6">
      <label for="productName" class="form-label">Product Name</label>
      <input type="text" class="form-control" id="productName" name="name" required>
      <div class="invalid-feedback">Please provide a product name.</div>
    </div>
  
    <div class="col-md-6">
      <label for="productSlug" class="form-label">Slug</label>
      <input class="form-control" type="text" id="productSlug" name="slug" placeholder="slug here..." readonly>
      <div class="invalid-feedback">Please provide a unique slug.</div>
    </div>
  
    <div class="col-md-12 mb-4">
      <label for="productDescription" class="form-label">Description</label>
      <div class="card">
        <div class="card-body">
          <textarea class="form-control bg-light-subtle" id="description" name="description" rows="7" placeholder="Type description"></textarea>
        </div>
      </div>
    </div>
  
    <div class="col-md-4">
      <label for="productPrice" class="form-label">Price</label>
      <input type="number" class="form-control" id="productPrice" name="price" step="0.01" required>
      <div class="invalid-feedback">Please provide a valid price.</div>
    </div>
  
    <div class="col-md-4">
      <label for="productDiscountPrice" class="form-label">Discount Price</label>
      <input type="number" class="form-control" id="productDiscountPrice" name="discount_price" step="0.01">
    </div>
  
    <div class="col-md-4">
      <label for="productQuantity" class="form-label">Quantity</label>
      <input type="number" class="form-control" id="productQuantity" name="quantity" required>
      <div class="invalid-feedback">Please provide the quantity.</div>
    </div>
  
    <div class="col-md-6">
      <label for="productSku" class="form-label">SKU</label>
      <input type="text" class="form-control" id="productSku" name="sku">
    </div>
  
    <div class="col-md-6">
      <label for="productCategory" class="form-label">Category</label>
      <select class="form-select" id="productCategory" name="category_id" required>
        <option selected disabled value="">Choose a category</option>
        <option value="1">Electronics</option>
        <option value="2">Books</option>
        <option value="3">Clothing</option>
      </select>
      <div class="invalid-feedback">Please select a valid category.</div>
    </div>
  
    <div class="col-md-6">
      <label for="productBrand" class="form-label">Brand</label>
      <select class="form-select" id="productBrand" name="brand_id" required>
        <option selected disabled value="">Choose a brand</option>
        <option value="1">Brand A</option>
        <option value="2">Brand B</option>
      </select>
      <div class="invalid-feedback">Please select a valid brand.</div>
    </div>
  
    <div class="col-md-6">
      <label for="productImages" class="form-label">Product Images</label>
      <input type="file" class="form-control" id="productImages" name="images[]" multiple accept="image/*">
    </div>
  
    <div class="col-12">
      <button class="btn btn-primary" type="submit">Submit form</button>
    </div>
  </form>
  
  @endsection