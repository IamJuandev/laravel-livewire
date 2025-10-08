<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="data:image/svg+xml,<svg width='100' height='100' viewBox='0 0 100 100' fill='none' xmlns='http://www.w3.org/2000/svg'><defs><linearGradient id='akanis_gradient' x1='50' y1='5' x2='50' y2='95' gradientUnits='userSpaceOnUse'><stop stop-color='%231E40AF' /><stop offset='1' stop-color='%2306B6D4' /></linearGradient></defs><path d='M50 5L75 50L95 50L50 95L5 50L25 50L50 5ZM50 25L30 50H70L50 25Z' fill='url(%23akanis_gradient)' /></svg>">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@fluxAppearance
