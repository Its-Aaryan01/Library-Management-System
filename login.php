<?php
session_start();
include 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        if ($user['role'] === 'student') {
            header("Location: student_dashboard.php");
        } else {
            header("Location: teacher_dashboard.php");
        }
    } else {
        echo "<div class='error-message'>Invalid email or password. Please try again.</div>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Processing</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #3498db 0%, #8e44ad 100%);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-message {
            background-color: #e74c3c;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-size: 16px;
            max-width: 400px;
            width: 100%;
            position: relative;
            animation: shake 0.5s ease-in-out, fadeIn 0.5s ease;
        }

        .error-message:before {
            content: "⚠️";
            margin-right: 10px;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        .loading-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            animation: fadeIn 0.5s ease;
        }

        .loading-text {
            color: #2c3e50;
            font-size: 18px;
            margin-top: 20px;
            position: relative;
        }

        .loading-text:after {
            content: '...';
            position: absolute;
            animation: dots 1.5s infinite;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .back-link:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes dots {
            0%, 20% { content: '.'; }
            40% { content: '..'; }
            60%, 100% { content: '...'; }
        }
    </style>
</head>
<body>
    <?php if (!isset($user)): ?>
    <div class="loading-container">
        <div class="loader"></div>
        <div class="loading-text">Processing login</div>
        <a href="index.php" class="back-link">Back to Login</a>
    </div>
    <script>
        // If no form submission occurred, redirect back to login page
        if (!document.querySelector('.error-message')) {
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 3000);
        }
    </script>
    <?php endif; ?>
</body>
</html>