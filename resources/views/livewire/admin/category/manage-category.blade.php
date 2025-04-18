<div class="container px-4 py-5">
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show border-start border-4 border-success" role="alert">
            <div class="d-flex align-items-center">
                <svg class="bi bi-check-circle-fill flex-shrink-0 me-2" width="20" height="20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="mb-0 fw-medium">{{ session('message') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Category Form Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2 class="h5 mb-0">
                        {{ $editingCategoryId ? 'Edit Category' : 'Create New Category' }}
                    </h2>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="saveCategory">
                        <!-- Category Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Category Title*</label>
                            <input type="text" id="title" wire:model.blur="title" class="form-control" required>
                            @error('title') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Parent Category -->
                        <div class="mb-3">
                            <label for="parent_category_id" class="form-label">Parent Category</label>
                            <select id="parent_category_id" wire:model="parent_category_id" class="form-select">
                                <option value="">Select Parent (Optional)</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                @endforeach
                            </select>
                            @error('parent_category_id') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <div class="input-group">
                                <label for="image" class="border border-dashed p-4 w-100 text-center rounded">
                                    <div class="d-flex flex-column align-items-center">
                                        <svg class="bi bi-image mb-2" width="40" height="40" fill="currentColor" aria-hidden="true">
                                            <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                        </svg>
                                        <span class="small text-muted">Click to upload image</span>
                                    </div>
                                    <input id="image" type="file" wire:model="image" class="d-none" accept="image/*">
                                </label>
                            </div>
                            @if ($imagePreview)
                                <div class="mt-3">
                                    <p class="small text-muted mb-1">Image Preview:</p>
                                    <img src="{{ $imagePreview }}" alt="Image Preview" class="img-thumbnail" style="max-width: 96px; max-height: 96px;">
                                </div>
                            @elseif ($image)
                                <div wire:loading wire:target="image" class="mt-3 small text-primary">
                                    Uploading...
                                </div>
                            @endif
                            @error('image') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Status Toggle -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" wire:model="is_active">
                                <label class="form-check-label" for="is_active">Active Status</label>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" wire:model="description" rows="3" class="form-control"></textarea>
                            @error('description') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- SEO Section -->
                        <div class="border-top pt-4 mb-3">
                            <h3 class="h6 mb-3">SEO Settings</h3>
                            <!-- Meta Title -->
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" id="meta_title" wire:model="meta_title" class="form-control">
                                @error('meta_title') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                            <!-- Meta Description -->
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea id="meta_description" wire:model="meta_description" rows="3" class="form-control"></textarea>
                                @error('meta_description') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 pt-3">
                            @if ($editingCategoryId)
                                <button type="button" wire:click="resetForm" class="btn btn-outline-secondary">
                                    Cancel
                                </button>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                {{ $editingCategoryId ? 'Update Category' : 'Create Category' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Category List Column -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Category Management</h2>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="showDeleted" wire:model="showDeleted">
                        <label class="form-check-label" for="showDeleted">Show Deleted</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="px-3 py-2">Image</th>
                                    <th scope="col" class="px-3 py-2">Title</th>
                                    <th scope="col" class="px-3 py-2">Parent</th>
                                    <th scope="col" class="px-3 py-2">Status</th>
                                    <th scope="col" class="px-3 py-2 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr class="{{ $category->trashed() ? 'table-danger' : ($category->is_active ? '' : 'table-warning') }}">
                                        <!-- Image -->
                                        <td class="px-3 py-2 align-middle">
                                            @if ($category->image)
                                                <img src="{{ $category->image }}" alt="{{ $category->title }}" class="img-thumbnail" style="width: 40px; height: 40px;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded" style="width: 40px; height: 40px;">
                                                    <svg class="bi bi-image" width="24" height="24" fill="currentColor" aria-hidden="true">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <!-- Title with indentation -->
                                        <td class="px-3 py-2 align-middle">
                                            <div class="d-flex align-items-center">
                                                @if($category->level > 0)
                                                    @for($i = 0; $i < $category->level; $i++)
                                                        <span class="d-inline-block" style="width: 16px;"></span>
                                                    @endfor
                                                    <svg class="bi bi-chevron-right me-1" width="16" height="16" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                                    </svg>
                                                @endif
                                                <span class="{{ $category->trashed() ? 'text-decoration-line-through text-muted' : '' }}">{{ $category->title }}</span>
                                            </div>
                                        </td>
                                        <!-- Parent -->
                                        <td class="px-3 py-2 align-middle">
                                            {{ $category->parent ? $category->parent->title : '-' }}
                                        </td>
                                        <!-- Status -->
                                        <td class="px-3 py-2 align-middle">
                                            @if($category->trashed())
                                                <span class="badge bg-danger">Deleted</span>
                                            @else
                                                <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            @endif
                                        </td>
                                        <!-- Actions -->
                                        <td class="px-3 py-2 align-middle text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                @if ($category->trashed())
                                                    <button wire:click="restoreCategory({{ $category->id }})" class="btn btn-sm btn-outline-success" title="Restore">
                                                        <svg class="bi bi-arrow-counterclockwise" width="16" height="16" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                                                            <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button wire:click="editCategory({{ $category->id }})" class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <svg class="bi bi-pencil" width="16" height="16" fill="currentColor" aria-hidden="true">
                                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                        </svg>
                                                    </button>
                                                    <button wire:click="deleteCategory({{ $category->id }})" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <svg class="bi bi-trash" width="16" height="16" fill="currentColor" aria-hidden="true">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5zm2.5 6.5a.5.5 0 0 1-.5-.5V6.5a.5.5 0 0 1 1 0v5a.5.5 0 0 1-.5.5z"/>
                                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-3 py-2 text-center text-muted">
                                            No categories found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>