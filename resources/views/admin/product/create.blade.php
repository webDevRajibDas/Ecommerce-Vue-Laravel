@extends('admin.layouts.admin-master')

@section('title', 'Create Product')

@section('content')

    @include('admin.product.form')

@endsection

@push('styles')
    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="{{asset('admin/vendor/select2/css/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/vendor/dropzone/basic.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/vendor/dropzone/dropzone.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css')}}" />
    <link rel="stylesheet" href="{{asset('admin/vendor/pnotify/pnotify.custom.css')}}" />
@endpush

@push('scripts')
    <!-- Specific Page Vendor -->
    <script src="{{asset('admin/vendor/jquery-validation/jquery.validate.js')}}"></script>
    <script src="{{asset('admin/vendor/select2/js/select2.js')}}"></script>
    <script src="{{asset('admin/vendor/dropzone/dropzone.js')}}"></script>
    <script src="{{asset('admin/vendor/pnotify/pnotify.custom.js')}}"></script>
    <script src="{{asset('admin/js/examples/examples.ecommerce.form.js')}}"></script>

    <script>
        $(document).ready(function () {

        });

    </script>
@endpush
