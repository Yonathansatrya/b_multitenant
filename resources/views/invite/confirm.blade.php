<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Undangan - {{ $invitation->organization->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md text-center">
        <h2 class="text-2xl font-bold text-gray-800">Undangan Bergabung ke Team & organisasi {{ $invitation->organization->name }}</h2>

        @guest
            <p class="mt-2 text-gray-600">Anda belum memiliki akun. Silakan
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">daftar</a> terlebih dahulu.</p>
        @else
            <p class="mt-4 text-gray-600">Halo, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>! Anda diundang untuk bergabung ke organisasi ini.</p>
            <div class="">
                <form action="{{ route('invite.confirm.post', $invitation->invite_code) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg shadow-md text-lg font-semibold hover:bg-green-700 transition">Konfirmasi Bergabung</button>
                </form>
                <form action="{{ route('logout') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg shadow-md text-lg font-semibold hover:bg-red-700 transition">Batalkan</button>
                </form>
            </div>
        @endguest
    </div>
</body>
</html>
