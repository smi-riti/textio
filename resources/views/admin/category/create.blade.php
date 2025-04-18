@extends('admin.base')
@section('title', 'Dashboard')
@section('content')
<form class="row g-3 p-4 mx-4 my-4" method="POST" action="#" enctype="multipart/form-data">
    <div class="col-md-6">
      <label for="categoryTitle" class="form-label">Category Title</label>
      <input type="text" class="form-control" id="categoryTitle" name="title" required>
    </div>
  
    <div class="col-md-6">
      <label for="categorySlug" class="form-label">Slug</label>
      <input type="text" class="form-control" id="categorySlug" name="slug" placeholder="Auto-generated or manually enter" required>
    </div>
  
    <div class="col-md-6">
      <label for="parentCategory" class="form-label">Parent Category</label>
      <select class="form-select" id="parentCategory" name="parent_category_id">
        <option selected disabled value="">Choose parent category (optional)</option>
        <option value="1">Electronics</option>
        <option value="2">Books</option>
        <option value="3">Clothing</option>
      </select>
    </div>
  
    <div class="col-md-6">
      <label for="categoryImage" class="form-label">Category Image</label>
      <input type="file" class="form-control" id="categoryImage" name="image" accept="image/*">
    </div>
  
    <div class="col-md-12">
      <label for="categoryDescription" class="form-label">Description</label>
      <textarea class="form-control" id="categoryDescription" name="description" rows="4" placeholder="Enter category description..."></textarea>
    </div>
  
    <div class="col-md-6">
      <label for="metaTitle" class="form-label">Meta Title</label>
      <input type="text" class="form-control" id="metaTitle" name="meta_title" placeholder="Meta title for SEO">
    </div>
  
    <div class="col-md-6">
      <label for="metaDescription" class="form-label">Meta Description</label>
      <textarea class="form-control" id="metaDescription" name="meta_description" rows="2" placeholder="Meta description for SEO"></textarea>
    </div>
  
    <div class="col-md-6">
      <div class="form-check mt-4">
        <input class="form-check-input" type="checkbox" id="isActive" name="is_active" checked>
        <label class="form-check-label" for="isActive">
          Active
        </label>
      </div>
    </div>
  
    <div class="col-12">
      <button type="submit" class="btn btn-success">Add Category</button>
    </div>
  </form>

@endsection
  