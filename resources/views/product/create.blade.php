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

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <fieldset>
                    <legend>Add Product</legend>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            value="{{ old('title') }}">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" class="form-control" id="price" name="price"
                            value="{{ old('price') }}">
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Stock -->
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="text" class="form-control" id="stock" name="stock"
                            value="{{ old('stock') }}">
                        @error('stock')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Single Image (Optional - for backward compatibility) -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image (Optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Or use multiple images below</small>
                        @error('image')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Multiple Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label">Product Images (Multiple)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">You can select multiple images at once. The first image will be set as primary.</small>
                        @error('images')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Primary Image Selection -->
                    <div class="mb-3" id="primary-image-selector" style="display: none;">
                        <label for="primary_image_index" class="form-label">Select Primary Image</label>
                        <select name="primary_image_index" id="primary_image_index" class="form-select">
                            <option value="0">First Image</option>
                        </select>
                        <small class="text-muted">The primary image will be used as the main product image.</small>
                    </div>

                    <!-- Image Preview -->
                    <div class="mb-3" id="image-preview-container"></div>

                    <button type="submit" class="btn btn-primary">Submit</button>
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
                    option.textContent = `Image ${index + 1}`;
                    if (index === 0) option.selected = true;
                    primarySelector.appendChild(option);
                    
                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'd-inline-block m-2 text-center';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;" alt="Preview ${index + 1}">
                            <div class="small mt-1">Image ${index + 1}</div>
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
