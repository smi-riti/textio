<div class="col-lg-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="h5 mb-0">
                {{ $editingBrandId ? 'Edit Brand' : 'Create New Brand' }}
            </h2>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="saveBrand">
                <!-- Brand Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Brand Name*</label>
                    <input type="text" id="name" wire:model.blur="name" class="form-control" required>
                    @error('name') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug*</label>
                    <input type="text" id="slug" wire:model.live="slug" class="form-control" readonly required>
                    @error('slug') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label for="logo" class="form-label">Brand Logo</label>
                    <div class="input-group">
                        <label for="logo" class="border border-dashed p-4 w-100 text-center rounded">
                            <div class="d-flex flex-column align-items-center">
                                <svg class="bi bi-image mb-2" width="40" height="40" fill="currentColor" aria-hidden="true">
                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zM2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                </svg>
                                <span class="small text-muted">Click to upload logo</span>
                            </div>
                            <input id="logo" type="file" wire:model="logo" class="d-none" accept="image/*">
                        </label>
                    </div>
                    @if ($imagePreview)
                        <div class="mt-3">
                            <p class="small text-muted mb-1">Logo Preview:</p>
                            <img src="{{ $imagePreview }}" alt="Logo Preview" class="img-thumbnail" style="max-width: 96px; max-height: 96px;">
                        </div>
                    @elseif ($logo)
                        <div wire:loading wire:target="logo" class="mt-3 small text-primary">
                            Uploading...
                        </div>
                    @endif
                    @error('logo') <p class="text-danger small mt-1">{{ $message }}</p> @enderror
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
                    @if ($editingBrandId)
                        <button type="button" wire:click="resetForm" class="btn btn-outline-secondary">
                            Cancel
                        </button>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        {{ $editingBrandId ? 'Update Brand' : 'Create Brand' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>