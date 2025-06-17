<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Ajojing Store')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/images/logo-ajojing.png') }}" type="image">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    {{-- Memanggil komponen Navbar --}}
    @include('layouts.partials.customer-navbar')

    {{-- Konten utama dari setiap halaman akan muncul di sini --}}
    <main class="py-4">
        @yield('content')
    </main>

    {{-- Memanggil komponen Footer --}}
    @include('layouts.partials.customer-footer')

    {{-- JavaScript --}}

    {{-- Core JS (Sama seperti admin) --}}
    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

    {{-- SweetAlert2 JS (jika dibutuhkan) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- Untuk script khusus per halaman --}}
    @stack('scripts')
</body>

</html>

@if (session('success'))
    <script>
        // Cek apakah ada sinyal dari controller untuk menampilkan tombol keranjang
        @if (session('showCartButton'))

            // Jika YA, tampilkan notifikasi dengan tombol
            Swal.fire({
                toast: true,
                position: 'bottom',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: true, // Tampilkan tombol
                confirmButtonText: '<i class="ti ti-shopping-cart me-2"></i> Lihat Keranjang',
                confirmButtonColor: '#0d6efd', // Warna biru Bootstrap
                showCloseButton: true, // Tampilkan tombol close 'x'
            }).then((result) => {
                // Jika tombol 'Lihat Keranjang' diklik
                if (result.isConfirmed) {
                    // Arahkan pengguna ke halaman keranjang
                    window.location.href = '{{ route('cart.index') }}';
                }
            });
        @else

            // Jika TIDAK, tampilkan notifikasi biasa yang hilang otomatis setelah 3 detik
            const Toast = Swal.mixin({
                toast: true,
                position: 'center',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
    </script>
@endif
