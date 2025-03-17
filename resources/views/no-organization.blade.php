<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belum Punya Organisasi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h1 class="text-xl font-bold text-red-500">Anda Belum Memiliki Organisasi</h1>
        <p class="mt-2 text-gray-600">Silakan buat atau bergabung ke organisasi untuk melanjutkan.</p>
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                Logout
            </button>
        </form>
    </div>
</body>
</html>
