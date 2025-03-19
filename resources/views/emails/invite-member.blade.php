<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Bergabung - Orion Tech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md text-center">
        <h2 class="text-2xl font-bold text-gray-800">Halo!</h2>
        <p class="mt-2 text-gray-600">Anda diundang untuk bergabung dengan <span class="font-semibold text-blue-600">Orion Tech</span>.
        Klik tombol di bawah untuk menerima undangan Anda:</p>

        <a href="{{ $invitationLink }}"
           class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md text-lg font-semibold hover:bg-blue-700 transition">
            Bergabung Sekarang
        </a>

        <p class="mt-4 text-sm text-gray-500">Jika Anda tidak mengenali email ini, silakan abaikan.</p>
    </div>
</body>
</html>
