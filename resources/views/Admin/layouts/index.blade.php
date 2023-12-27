@include('Admin.layouts.sidebar')
@include('Admin.layouts.header')
{{-- @include('Admin.layouts_baru.content') --}}

<main>
    @yield('container') <!-- Ini adalah tempat untuk konten yang akan digantikan -->
</main>
@include('Admin.layouts.footer')