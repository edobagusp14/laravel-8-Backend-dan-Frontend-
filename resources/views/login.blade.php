<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe);
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            position: relative;
            transition: transform 0.5s ease-in-out;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-header h2 {
            color: #333;
        }

        .form-control {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 8px rgba(79, 172, 254, 0.5);
        }

        .btn {
            background: #4facfe;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .btn:hover {
            background: #00f2fe;
        }

        .switch-form {
            margin-top: 1rem;
            text-align: center;
        }

        .switch-form a {
            color: #4facfe;
            text-decoration: none;
            font-weight: bold;
        }

        .switch-form a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container" id="formContainer">
        <!-- Login Form -->
        <div id="loginForm" class="form-content">
            <div class="form-header">
                <h2>Login</h2>
            </div>
            <form onsubmit="login(event)">
                @csrf
                <input type="email" class="form-control" id="email" placeholder="Email" required>
                <input type="password" class="form-control" id="password" placeholder="Password" required>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="switch-form">
                <p>Don't have an account? <a href="/register">Register</a></p>
            </div>
        </div>


    </div>

    <script>
        async function login(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('http://127.0.0.1:8000/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password }),
                });

                if (!response.ok) {
                    throw new Error('Login failed. Please check your credentials.');
                }
                
                const data = await response.json();
                localStorage.setItem('access_token', data.access_token);

                // Redirect to index page
                window.location.href = "{{ url('/index') }}";
            } catch (error) {
                alert(error.message);
            }
        }

    </script>
</body>
</html>
