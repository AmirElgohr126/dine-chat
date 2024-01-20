<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deletion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em 0;
        }

        .error {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }

        nav a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }

        .warning-section {
            background-color: #ffdddd;
            border-left: 6px solid #f44336;
            margin: 10px;
            padding: 10px;
        }

        .deletion-form {
            background-color: white;
            margin: 10px;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: inline-block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #d73833;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <header>
        <h1>Account Deletion</h1>
    </header>

    <main>
        <section class="warning-section">
            <p><strong>Warning:</strong> Deleting your account is irreversible. All your data will be permanently
                removed.</p>
        </section>

        <section class="deletion-form">
            <form action="{{ route('account.delete') }}" method="POST">
                @csrf {{-- CSRF Token --}}

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="user name">
                @error('username')
                    <div class="error">{{ $message }}</div>
                @enderror
                <br><br>

                <label for="reason">Reason for leaving:</label>
                <select id="reason" name="reason">
                    <option value="privacy_concerns">Privacy Concerns</option>
                    <option value="not_useful">Not Useful to Me</option>
                    <option value="prefer_another_service">Prefer Another Service</option>
                    <option value="too_expensive">Too Expensive</option>
                    <option value="poor_customer_service">Poor Customer Service</option>
                    <option value="found_better_alternative">Found a Better Alternative</option>
                    <option value="other">Other</option>
                </select>
                @error('reason')
                    <div class="error">{{ $message }}</div>
                @enderror
                <br><br>

                <label for="feedback">Additional feedback:</label>
                <textarea id="feedback" name="feedback"></textarea>
                @error('feedback')
                    <div class="error">{{ $message }}</div>
                @enderror
                <br><br>

                <label for="password">Confirm Password:</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
                <br><br>

                <input type="checkbox" id="confirm" name="confirm" required>
                <label for="confirm">I understand that this action cannot be undone.</label>
                @error('confirm')
                    <div class="error">{{ $message }}</div>
                @enderror
                <br><br>

                <button type="submit">Delete My Account</button>
            </form>
        </section>
    </main>

    <footer>
        <p>Contact us at dinechat@gmail.com</p>
        <a href="{{ url('/privacy') }}">Privacy Policy</a>
        <a href="{{ url('/terms') }}">Terms of Service</a>
    </footer>
</body>


</html>
