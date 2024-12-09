<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        <!-- Register Form -->
        <div id="registerForm" class="form-content">
            <div class="form-header">
                <h2>Register</h2>
            </div>
            <form onsubmit="register(event)">
                <input type="text" class="form-control" id="name" placeholder="Full Name" required>
                <input type="email" class="form-control" id="email" placeholder="Email" required>
                <input type="password" class="form-control" id="password" placeholder="Password" required>
                <input type="password" class="form-control" id="confirmPassword" placeholder="confirmPassword" required>
                <div id="errorMessage" style="color: red; margin-top: 10px;"></div>
                <div id="successMessage" style="color: green; margin-top: 10px;"></div>
                <button type="submit" class="btn">Register</button>
            </form>
            <div class="switch-form">
                <p>Already have an account? <a href="/">Login</a></p>
            </div>
        </div>
</div>
<script>
async function register(event) {
    event.preventDefault(); // Mencegah form melakukan reload halaman

    // Ambil nilai dari input form
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');

    // Clear pesan sebelumnya
    errorMessage.innerText = '';
    successMessage.innerText = '';

    // Validasi input
    if (!name || !email || !password || !confirmPassword) {
        errorMessage.innerText = 'All fields are required.';
        return;
    }

    if (password !== confirmPassword) {
        errorMessage.innerText = 'Passwords do not match.';
        return;
    }

    try {
        // Kirim permintaan ke API
        const response = await fetch('http://127.0.0.1:8000/api/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, email, password }),
        });

        // Tangani respon API
        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Registration failed.');
        }
        

        // Berhasil
        successMessage.innerText = 'Registration successful!';
        console.log('Registration data:', data);

        // Redirect setelah sukses (opsional)
        setTimeout(() => {
            window.location.href = "{{ url('/register') }}";
        }, 2000);
    } catch (error) {
        // Tampilkan pesan error
        errorMessage.innerText = error.message;
        console.error('Error:', error);
    }
}
</script>
</body>
</html>
