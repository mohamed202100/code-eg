<x-app-layout>
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

                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>


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

    </body>

    </html>
</x-app-layout>
