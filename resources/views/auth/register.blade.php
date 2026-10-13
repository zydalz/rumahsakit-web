
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pasien - PT Oasis Husada</title>
</head>
<body>
    <h1>Registrasi Pasien</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <div>
            <label for="name">Nama lengkap</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                maxlength="100"
                required
                autocomplete="name"
            >
        </div>

        <div>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                maxlength="150"
                required
                autocomplete="email"
            >
        </div>

        <div>
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                autocomplete="new-password"
            >
        </div>

        <div>
            <label for="password_confirmation">Konfirmasi password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                autocomplete="new-password"
            >
        </div>

        <button type="submit">Daftar</button>
    </form>

    <p>
        Sudah memiliki akun?
        <a href="{{ route('login') }}">Login</a>
    </p>
</body>
</html>
