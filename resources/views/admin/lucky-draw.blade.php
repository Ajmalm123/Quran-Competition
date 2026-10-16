<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#05070f" />
    <meta name="csrf-token" content="{{ $csrfToken }}" />
    <title>Winner Selection · Lucky Draw</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Montserrat:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('lucky-draw-assets/lucky-draw.css') }}" />
    <script>
      window.__CSRF_TOKEN__ = "{{ $csrfToken }}";
      window.__INITIAL_DATA__ = @json($initialData);
    </script>
  </head>
  <body>
    <div id="root"></div>
    <script type="module" src="{{ asset('lucky-draw-assets/lucky-draw.js') }}"></script>
  </body>
</html>
