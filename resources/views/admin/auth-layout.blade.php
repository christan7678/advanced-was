<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    {{-- Use admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/admin-layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/admin-auth.css') }}">
</head>

<body>

    {{-- ONLY show content (no navbar, no user UI) --}}
    @yield('content')

</body>
</html>