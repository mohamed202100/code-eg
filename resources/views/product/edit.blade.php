<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Webmin - Bootstrap 4 & Angular 5 Admin Dashboard Template" />
    <meta name="author" content="potenzaglobalsolutions.com" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    @include('layouts.head')
</head>

<body>

    <div class="wrapper">

        <!--=================================
 preloader -->

        <div id="pre-loader">
            <img src="assets/images/pre-loader/loader-01.svg" alt="">
        </div>

        <!--=================================
 preloader -->

        @include('layouts.main-header')

        @include('layouts.main-sidebar')

        <!--=================================
 Main content -->
        <!-- main-content -->
        <div class="content-wrapper">

            <!-- Delete Image Forms (outside main form to prevent conflicts) -->
            @if ($product->images->count() > 0)
                @foreach ($product->images as $productImage)
                    <form id="delete-form-{{ $productImage->id }}" action="{{ route('products.image.delete', $productImage->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <fieldset>
                    <legend>Edit Product</legend>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Product Title</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $product->title) }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price"
                            value="{{ old('price', $product->price) }}">
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="text" class="form-control" id="stock" name="stock"
                            value="{{ old('stock', $product->stock) }}">
                        @error('stock')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active
                            </option>
                            <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Unified Image Management -->
                    <div class="card mb-4 border">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Product Images</h5>
                        </div>
                        <div class="card-body">
                            <!-- Existing Images -->
                            @if ($product->images->count() > 0)
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Current Images</label>
                                    <div class="row g-3">
                                        @foreach ($product->images as $productImage)
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <div class="card h-100 {{ $productImage->is_primary ? 'border-primary' : 'border-secondary' }}" id="card-existing-{{ $productImage->id }}">
                                                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                                                        <img src="{{ asset('storage/' . $productImage->image_path) }}"
                                                            class="w-100 h-100" style="object-fit: cover;">
                                                        @if ($productImage->is_primary)
                                                            <span class="badge bg-primary position-absolute top-0 start-0 m-2" id="badge-existing-{{ $productImage->id }}">Main Image</span>
                                                        @endif
                                                    </div>
                                                    <div class="card-body text-center p-2">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input existing-radio" type="radio"
                                                                name="existing_primary_image"
                                                                id="existing_primary_{{ $productImage->id }}"
                                                                value="{{ $productImage->id }}"
                                                                {{ $productImage->is_primary ? 'checked' : '' }}
                                                                onchange="handlePrimarySelection('existing', {{ $productImage->id }})">
                                                            <label class="form-check-label" for="existing_primary_{{ $productImage->id }}">Set as Main</label>
                                                        </div>
                                                        <hr class="my-2">
                                                        <button type="button" class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="deleteImage({{ $productImage->id }})">
                                                            <i class="ti-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Upload New Images -->
                            <div class="mb-3">
                                <label for="images" class="form-label fw-bold">Upload New Images</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                                </div>
                                <small class="text-muted">You can select multiple files. Creating previews...</small>
                            </div>

                            <!-- New Images Preview Grid -->
                            <div id="image-preview-container" class="row g-3"></div>

                            <!-- Hidden input for new image primary selection -->
                            <input type="hidden" name="primary_image_index" id="primary_image_index" value="-1">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </fieldset>
            </form>

            <br>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!--=================================
 wrapper -->

            <!--=================================
 footer -->

        </div><!-- main content wrapper end-->
    </div>
    </div>
    </div>

    <!--=================================
 footer -->

    @include('layouts.footer-scripts')

    <script>
        // Function to delete image
        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                document.getElementById('delete-form-' + imageId).submit();
            }
        }

        // Function to handle primary image selection (mutual exclusion)
        function handlePrimarySelection(type, value) {
            if (type === 'existing') {
                // If existing image selected, reset new image selection
                document.getElementById('primary_image_index').value = '-1';
                // Uncheck all new radios dummy inputs
                document.querySelectorAll('.new-radio').forEach(el => el.checked = false);
            } else if (type === 'new') {
                // If new image selected, update hidden input
                document.getElementById('primary_image_index').value = value;
                // Uncheck all existing radios
                document.querySelectorAll('.existing-radio').forEach(el => el.checked = false);
            }
            updateVisuals();
        }

        function updateVisuals() {
            // Visual feedback loop can be added here if needed (e.g. bold borders)
            // For now the radio buttons provide enough feedback
        }

        // Handle multiple image selection and preview
        document.getElementById('images').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('image-preview-container');
            const existingImagesCount = {{ $product->images->count() }};

            previewContainer.innerHTML = '';

            if (files.length > 0) {
                Array.from(files).forEach((file, index) => {
                    // Global index logic matches Controller expectation: existingCount + index
                    const globalIndex = existingImagesCount + index;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'col-xl-3 col-lg-4 col-md-6';
                        div.innerHTML = `
                            <div class="card h-100 border-secondary">
                                <div class="position-relative" style="height: 180px; overflow: hidden;">
                                    <img src="${e.target.result}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="card-body text-center p-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input new-radio" type="radio"
                                            name="new_primary_selection_dummy"
                                            id="new_primary_${globalIndex}"
                                            value="${globalIndex}"
                                            onchange="handlePrimarySelection('new', ${globalIndex})">
                                        <label class="form-check-label" for="new_primary_${globalIndex}">Set as Main</label>
                                    </div>
                                    <div class="small mt-1 text-muted">New Image ${index + 1}</div>
                                </div>
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });
    </script>

</body>

</html>
