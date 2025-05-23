<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
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
            color: white;
        }
        
        .logout-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }
        
        .logout-icon {
            font-size: 60px;
            color: #3498db;
            margin-bottom: 20px;
            animation: rotate 1s ease;
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        p {
            color: #7f8c8d;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .login-link {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(to right, #3498db, #8e44ad);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .login-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .countdown {
            margin-top: 20px;
            font-size: 14px;
            color: #95a5a6;
            font-style: italic;
        }
        
        .countdown-number {
            font-weight: bold;
            color: #3498db;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">↪</div>
        <h2>You've Been Logged Out</h2>
        <p>Thank you for using our library management system.</p>
        <a href="index.php" class="login-link">Back to Login</a>
        <div class="countdown">Redirecting to login in <span id="counter" class="countdown-number">5</span> seconds</div>
    </div>
    
    <script>
        // Auto redirect after 5 seconds
        let count = 5;
        const counter = document.getElementById('counter');
        
        const interval = setInterval(() => {
            count--;
            counter.textContent = count;
            
            if (count <= 0) {
                clearInterval(interval);
                window.location.href = 'index.php';
            }
        }, 1000);
    </script>
</body>
</html>
<?php
header("refresh:5;url=index.php");
?>