<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <style>
        body {
            text-align: center;
            padding: 50px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e5a1a1;
            color: #333;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .welcome-message {
            font-size: 28px;
            color: #333;
            margin-top: 20px;
            text-shadow: 1px 1px 2px #ab9999;
        }

        .content {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .content:hover {
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="content">
        <img src="{{ asset('storage/Dafaults/Logo/logo.png') }}" alt="Logo" class="logo">
        <div class="welcome-message">
            Welcome to Our Services!
        </div>
    </div>
</body>
</html>
