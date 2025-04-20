<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Add Product Button -->
    <div class="d-flex justify-content-between mb-3 p-4">
        <button class="btn btn-primary" wire:click="openModal">Add Product</button>
    </div>

    <!-- Products Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Brand</th>               
                <th>Price</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Images</th>
                <th>Variants</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->title ?? 'N/A' }}</td>
                    <td>{{ $product->brand?->name ?? 'N/A' }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        @if ($product->images->count() > 0)
                            <div class="d-flex flex-wrap">
                                @foreach ($product->images as $image)
                                    <div class="position-relative m-1">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover;">
                                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0" wire:click="deleteImage({{ $image->id }})">×</button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            No Images
                        @endif
                        <button class="btn btn-sm btn-primary mt-2" wire:click="openImageModal({{ $product->id }})">Add Image</button>
                    </td>
                    <td>
                        @if ($product->variants->count() > 0)
                            <div class="d-flex flex-column">
                                @foreach ($product->variants as $variant)
                                    <div class="d-flex align-items-center mb-2">
                                        <span>{{ $variant->variant_type }}: {{ $variant->variant_name }} (Stock: {{ $variant->stock }})</span>
                                        @if ($variant->variant_image)
                                            <img src="{{ Storage::url($variant->variant_image) }}" alt="Variant Image" style="width: 30px; height: 30px; object-fit: cover; margin-left: 10px;">
                                        @endif
                                        <button class="btn btn-sm btn-warning mx-1" wire:click="editVariant({{ $variant->id }})">Edit</button>
                                        <button class="btn btn-sm btn-danger" wire:click="deleteVariant({{ $variant->id }})">Delete</button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            No Variants
                        @endif
                        <button class="btn btn-sm btn-primary mt-2" wire:click="openVariantModal({{ $product->id }})">Add Variant</button>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="edit({{ $product->id }})">Edit</button>
                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $product->id }})">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Product Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1" style="{{ $showModal ? 'background: rgba(0,0,0,0.5)' : '' }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isEditMode ? 'Edit Product' : 'Add Product' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"></textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" wire:model="price">
                                @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_price" class="form-label">Discount Price</label>
                                <input type="number" step="0.01" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" wire:model="discount_price">
                                @error('discount_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" wire:model="quantity">
                                @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sku" class="form-label">SKU</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" wire:model="sku">
                                @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-4 mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" wire:model="category_id">
                                    <option value="">Select Category</option>
                                    @foreach (\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-control @error('brand_id') is-invalid @enderror" id="brand_id" wire:model="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach (\App\Models\Brand::all() as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="images" wire:model="images" multiple>
                            @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="status" wire:model="status">
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="save">{{ $isEditMode ? 'Update' : 'Save' }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Upload Modal -->
    <div class="modal fade {{ $showImageModal ? 'show d-block' : '' }}" tabindex="-1" style="{{ $showImageModal ? 'background: rgba(0,0,0,0.5)' : '' }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Images</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="image_upload" class="form-label">Upload Images</label>
                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" id="image_upload" wire:model="images" multiple>
                            @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="saveImages">Save Images</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Variant Modal -->
    <div class="modal fade {{ $showVariantModal ? 'show d-block' : '' }}" tabindex="-1" style="{{ $showVariantModal ? 'background: rgba(0,0,0,0.5)' : '' }}">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $isVariantEditMode ? 'Edit Variant' : 'Add Variant' }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="variant_type" class="form-label">Variant Type (e.g., Color, Size)</label>
                                <input type="text" class="form-control @error('variant_type') is-invalid @enderror" id="variant_type" wire:model="variant_type">
                                @error('variant_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="variant_name" class="form-label">Variant Name (e.g., Red, Large)</label>
                                <input type="text" class="form-control @error('variant_name') is-invalid @enderror" id="variant_name" wire:model="variant_name">
                                @error('variant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="variant_price" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control @error('variant_price') is-invalid @enderror" id="variant_price" wire:model="variant_price">
                                @error('variant_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="variant_stock" class="form-label">Stock</label>
                                <input type="number" class="form-control @error('variant_stock') is-invalid @enderror" id="variant_stock" wire:model="variant_stock">
                                @error('variant_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="variant_sku" class="form-label">SKU</label>
                            <input type="text" class="form-control @error('variant_sku') is-invalid @enderror" id="variant_sku" wire:model="variant_sku">
                            @error('variant_sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="variant_image" class="form-label">Variant Image</label>
                            <input type="file" class="form-control @error('variant_image') is-invalid @enderror" id="variant_image" wire:model="variant_image">
                            @error('variant_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="saveVariant">{{ $isVariantEditMode ? 'Update' : 'Save' }}</button>
                </div>
            </div>
        </div>
    </div>
</div>