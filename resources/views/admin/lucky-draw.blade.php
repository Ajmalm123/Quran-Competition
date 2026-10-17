<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#3D1F14" />
    <meta name="csrf-token" content="{{ $csrfToken }}" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
    <title>TIBIYAN — Winner Selection</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Noto+Sans:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="{{ asset('lucky-draw-assets/lucky-draw.css') }}?v={{ file_exists(public_path('lucky-draw-assets/lucky-draw.css')) ? filemtime(public_path('lucky-draw-assets/lucky-draw.css')) : time() }}"
    />
    <script>
      window.__CSRF_TOKEN__ = "{{ $csrfToken }}";
      window.__INITIAL_DATA__ = @json($initialData);
    </script>
  </head>
  <body>
    <div id="root"></div>
    <script
      type="module"
      src="{{ asset('lucky-draw-assets/lucky-draw.js') }}?v={{ file_exists(public_path('lucky-draw-assets/lucky-draw.js')) ? filemtime(public_path('lucky-draw-assets/lucky-draw.js')) : time() }}"
    ></script>
  </body>
</html>
