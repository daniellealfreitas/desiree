<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'Desiree Swing Club - Curitiba' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Cropper.js CSS -->
<link href="{{ asset('css/cropper.css') }}" rel="stylesheet" />


<!-- Livewire Styles -->
@livewireStyles

<!-- Scripts personalizados -->
@auth
<!-- Script de geolocalização automática -->
<script src="{{ asset('js/auto-geolocation.js') }}"></script>
@endauth
