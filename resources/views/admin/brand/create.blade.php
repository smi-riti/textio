@extends('admin.base')
@section('title', 'Dashboard')
@section('content')
<form>
    <div class="mb-3">
        <label for="brandName" class="form-label">Brand Name</label>
        <input type="text" class="form-control" id="brandName" placeholder="Enter brand name" required>
    </div>
    <div class="mb-3">
        <label for="brandSlug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="brandSlug" placeholder="Enter slug" required>
    </div>
    <div class="mb-3">
        <label for="brandLogo" class="form-label">Logo</label>
        <input type="file" class="form-control" id="brandLogo">
    </div>
    <div class="mb-3">
        <label for="brandDescription" class="form-label">Description</label>
        <textarea class="form-control" id="brandDescription" rows="3" placeholder="Enter description"></textarea>
    </div>
    <div class="mb-3">
        <label for="metaTitle" class="form-label">Meta Title</label>
        <input type="text" class="form-control" id="metaTitle" placeholder="Enter meta title">
    </div>
    <div class="mb-3">
        <label for="metaDescription" class="form-label">Meta Description</label>
        <textarea class="form-control" id="metaDescription" rows="3" placeholder="Enter meta description"></textarea>
    </div>
    <div class="mb-3">
        <label for="brandStatus" class="form-label">Status</label>
        <select class="form-select" id="brandStatus">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection