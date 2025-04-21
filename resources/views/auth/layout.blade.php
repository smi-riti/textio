<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Textio | E-Commerce HTML Admin Dashboard Template </title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="{{ asset('assets/dist/icons/bootstrap-icons-1.4.0/bootstrap-icons.min.css') }}"
        type="text/css">
    <!-- Bootstrap Docs -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/bootstrap-docs.css') }}" type="text/css">

    <!-- Slick -->
    <link rel="stylesheet" href="{{ asset('assets/libs/slick/slick.css') }}" type="text/css">
    <link href="../../css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Main style file -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/app.min.css') }}" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js')}}"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
    <![endif]-->
</head>

<body class="auth">

    <!-- begin::preloader-->
    <div class="preloader">
        <div class="preloader-icon"></div>
    </div>
    <!-- end::preloader -->

    @section('content')
        
    @show

   <!-- Bundle scripts -->
   <script src="{{ asset('assets/libs/bundle.js') }}"></script>

   <!-- Main Javascript file -->
   <script src="{{ asset('assets/dist/js/app.min.js') }}"></script>
</body>
