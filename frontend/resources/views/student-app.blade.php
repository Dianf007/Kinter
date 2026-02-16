<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Kinter</title>
    @vite(['resources/css/app.css', 'resources/js/student-app.js'])
    <script>
        window.API_BASE_URL = '{{ env('APP_URL') }}';
    </script>
</head>
<body>
    <div id="app"></div>
</body>
</html>
