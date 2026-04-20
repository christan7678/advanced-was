<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Ticket Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/user/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/events.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/bookings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/event-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user/footer.css') }}">

    @yield('styles')
</head>


<body>

    @include('partials.navbar')

    <div class="container py-4">
        @include('partials.flash')
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const profileWrap = document.getElementById('profileWrap');
            const profileBtn = document.getElementById('profileBtn');
            const profileMega = document.getElementById('profileMega');
            const hamburger = document.getElementById('hamburgerBtn');
            const menuPop = document.getElementById('menuPopup');

            function closeProfile() {
                profileMega.classList.remove('open');
            }

            function closeMenu() {
                menuPop.classList.remove('open');
            }

            function closeAll() {
                closeProfile();
                closeMenu();
            }

            profileBtn.addEventListener('click', e => {
                e.stopPropagation();
                closeMenu();
                profileMega.classList.toggle('open');
                profileBtn.classList.toggle('active');
            });

            hamburger.addEventListener('click', e => {
                e.stopPropagation();
                closeProfile();
                menuPop.classList.toggle('open');
                hamburger.classList.toggle('active');
            });

            document.addEventListener('click', e => {
                if (!profileWrap.contains(e.target)) {
                    closeProfile();
                }

                if (!hamburger.closest('.hamburger-wrap')?.contains(e.target)) {
                    closeMenu();
                }
            });
        })();
    </script>
    @yield('scripts')

    @include('partials.footer')

</body>
</html>