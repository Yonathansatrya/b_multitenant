<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Bergabung</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(to right, #ebf8ff, #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 16px;
        }
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .card h2 {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
        }

        .card p {
            margin-top: 16px;
            color: #4a5568;
            line-height: 1.6;
        }

        .card .highlight {
            font-weight: bold;
            color: #2563eb;
        }

        .btn {
            display: inline-block;
            margin-top: 24px;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, transform 0.2s;
        }

        .btn:hover {
            background-color: #1e40af;
            transform: scale(1.05);
        }

        .card .note {
            margin-top: 20px;
            font-size: 14px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Halo!</h2>
        <p>
            Anda diundang untuk bergabung dengan salah satu
            <span class="highlight">Organization</span>.
        </p>
        <p>Klik tombol di bawah untuk menerima undangan Anda:</p>

        <a href="{{ $invitationLink }}" class="btn">
            Bergabung Sekarang
        </a>

        <p class="note">Jika Anda tidak mengenali email ini, silakan abaikan.</p>
    </div>
</body>
</html>
