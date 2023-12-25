<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
</head>
<body>
    <h1>Oops! Page not found.</h1>
    <p>The page you requested could not be found: <strong>{{ request()->url() }}</strong></p>
    <p>Please try again, or head back to the <a href="{{ url('/') }}">homepage</a>.</p>
</body>
</html>