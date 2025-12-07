<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing Page</title>
</head>
<body>
    <h1>Halaman Testing TessController</h1>
    <p>Form Top Up Saldo via Xendit</p>
    <form method="POST" action="{{ route('topup.proses') }}">
        @csrf
        <label for="nama">Nama:</label><br>
        <input type="text" id="nama" name="nama" required><br><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="jumlah">Jumlah Top Up (minimal 1000):</label><br>
        <input type="number" id="jumlah" name="jumlah" min="1000" required><br><br>
        <button type="submit">Top Up Sekarang</button>
    </form>
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
