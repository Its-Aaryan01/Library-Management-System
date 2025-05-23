<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Library System</title>
    <link rel="stylesheet" href="css/style.css">
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
            overflow: hidden;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1s ease;
        }

        .login-container:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #3498db, #8e44ad);
            animation: borderSlide 3s infinite;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }

        h2:after {
            content: '';
            position: absolute;
            width: 50%;
            height: 3px;
            background: linear-gradient(to right, #3498db, #8e44ad);
            bottom: -10px;
            left: 25%;
            border-radius: 2px;
            animation: pulse 2s infinite;
        }

        input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            box-sizing: border-box;
        }

        input:focus {
            border-left: 3px solid #3498db;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            outline: none;
        }

        input::placeholder {
            color: #bdc3c7;
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #3498db, #8e44ad);
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        button:active {
            transform: translateY(0);
        }

        button:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        button:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            position: relative;
        }

        a:hover {
            color: #8e44ad;
        }

        a:after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #8e44ad;
            transform-origin: bottom right;
            transition: transform 0.3s ease-out;
        }

        a:hover:after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes borderSlide {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 1;
            }
            20% {
                transform: scale(25, 25);
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }

        @keyframes float {
            0% { transform: translateY(0) translateX(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-1000px) translateX(200px) rotate(720deg); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="particles">
        <?php for($i = 0; $i < 20; $i++): 
            $size = rand(5, 15);
            $left = rand(0, 100);
            $top = rand(0, 100);
            $duration = rand(10, 30);
            $delay = rand(0, 10);
        ?>
        <div class="particle" style="width: <?= $size ?>px; height: <?= $size ?>px; left: <?= $left ?>%; top: <?= $top ?>%; animation-duration: <?= $duration ?>s; animation-delay: <?= $delay ?>s;"></div>
        <?php endfor; ?>
    </div>
    
    <div class="login-container">
        <h2>Library Login</h2>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="register.php">Create New Account</a>
    </div>
    
    <script>
        // Add ripple effect to button
        document.querySelector('button').addEventListener('click', function(e) {
            var x = e.clientX - e.target.offsetLeft;
            var y = e.clientY - e.target.offsetTop;
            
            var ripples = document.createElement('span');
            ripples.style.left = x + 'px';
            ripples.style.top = y + 'px';
            this.appendChild(ripples);
            
            setTimeout(() => {
                ripples.remove();
            }, 1000);
        });
    </script>
</body>
</html>