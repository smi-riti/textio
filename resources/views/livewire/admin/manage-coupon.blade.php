<div>
<div>
        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Add Button -->
        <div class="d-flex justify-content-between mb-4 p-3 flex-wrap gap-3">
            <div class="input-group w-auto">
                <input type="text" class="form-control" placeholder="Search by coupon code" wire:model.live="search">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
            </div>
            <button class="btn btn-primary" wire:click="openModal">Add Coupon</button>
        </div>

        <!-- Coupons Card Grid -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse ($coupons as $coupon)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $coupon->code }}</h5>
                            <p class="card-text">
                                <strong>Type:</strong> {{ Str::title($coupon->discount_type) }}<br>
                                @if ($coupon->discount_type !== 'freeShipping')
                                    <strong>Value:</strong> {{ $coupon->discount_value }} {{ $coupon->discount_type === 'percentage' ? '%' : '$' }}<br>
                                @endif
                                <strong>Expires:</strong> {{ $coupon->expiration_date ? $coupon->expiration_date->format('M d, Y') : 'No Expiry' }}<br>
                                <strong>Status:</strong> 
                                <span class="badge {{ $coupon->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $coupon->status ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <button class="btn btn-sm btn-warning" wire:click="edit({{ $coupon->id }})">Edit</button>
                            <button class="btn btn-sm btn-danger" wire:click="delete({{ $coupon->id }})" onclick="return confirm('Are you sure?')">Delete</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info">No coupons found.</div>
                </div>
            @endforelse
        </div>

        <!-- Coupon Modal -->
        <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1" style="{{ $showModal ? 'background: rgba(0,0,0,0.5)' : '' }}">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $isEditMode ? 'Edit Coupon' : 'Add Coupon' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="code" class="form-label">Coupon Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" wire:model.live="code">
                                @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="discount_type" class="form-label">Discount Type</label>
                                <select class="form-control @error('discount_type') is-invalid @enderror" id="discount_type" wire:model.live="discount_type">
                                    <option value="percentage">Percentage</option>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="freeShipping">Free Shipping</option>
                                </select>
                                @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            @if ($discount_type !== 'freeShipping')
                                <div class="mb-3">
                                    <label for="discount_value" class="form-label">Discount Value</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control @error('discount_value') is-invalid @enderror" id="discount_value" wire:model.live="discount_value">
                                        <span class="input-group-text">{{ $discount_type === 'percentage' ? '%' : '$' }}</span>
                                    </div>
                                    @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="expiration_date" class="form-label">Expiration Date</label>
                                <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" id="expiration_date" wire:model.live="expiration_date">
                                @error('expiration_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" wire:model.live="status">
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="save">
                            <span wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status"></span>
                            {{ $isEditMode ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>