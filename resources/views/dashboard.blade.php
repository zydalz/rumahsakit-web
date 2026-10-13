
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PT Oasis Husada</title>
</head>
<body>
    <h1>Dashboard PT Oasis Husada</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <p>
        Selamat datang, {{ auth()->user()->name }}.
    </p>

    <p>
        Role: {{ auth()->user()->role }}
    </p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>