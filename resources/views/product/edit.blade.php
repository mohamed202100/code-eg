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

                    <!-- Current Images -->
                    @if($product->images->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Current Product Images</label>
                            <div class="row g-2">
                                @foreach($product->images as $index => $productImage)
                                    <div class="col-md-3 position-relative">
                                        <img src="{{ asset('storage/' . $productImage->image_path) }}" 
                                             alt="Product Image {{ $index + 1 }}"
                                             class="img-thumbnail w-100" 
                                             style="height: 150px; object-fit: cover;">
                                        @if($productImage->is_primary)
                                            <span class="badge bg-primary position-absolute top-0 start-0 m-1">Primary</span>
                                        @endif
                                        <div class="text-center mt-1">
                                            <a href="{{ route('products.image.delete', $productImage->id) }}" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this image?')">
                                                <i class="ti-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Single Image (Optional - for backward compatibility) -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Update Main Image (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        @if ($product->image && $product->images->count() == 0)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image"
                                class="img-thumbnail mt-2" width="150">
                        @endif
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Add More Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label">Add More Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">You can select multiple images to add to this product.</small>
                        @error('images')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Primary Image Selection (for new images) -->
                    <div class="mb-3" id="primary-image-selector" style="display: none;">
                        <label for="primary_image_index" class="form-label">Select Primary Image (from new images)</label>
                        <select name="primary_image_index" id="primary_image_index" class="form-select">
                            <option value="0">First New Image</option>
                        </select>
                        <small class="text-muted">The primary image will be used as the main product image.</small>
                    </div>

                    <!-- Image Preview -->
                    <div class="mb-3" id="image-preview-container"></div>

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
        // Handle multiple image selection and preview
        document.getElementById('images').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('image-preview-container');
            const primarySelector = document.getElementById('primary_image_index');
            
            previewContainer.innerHTML = '';
            primarySelector.innerHTML = '';
            
            if (files.length > 0) {
                document.getElementById('primary-image-selector').style.display = 'block';
                
                Array.from(files).forEach((file, index) => {
                    // Add option to primary selector
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = `New Image ${index + 1}`;
                    if (index === 0) option.selected = true;
                    primarySelector.appendChild(option);
                    
                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'd-inline-block m-2 text-center';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;" alt="Preview ${index + 1}">
                            <div class="small mt-1">New Image ${index + 1}</div>
                        `;
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                document.getElementById('primary-image-selector').style.display = 'none';
            }
        });
    </script>

</body>

</html>
