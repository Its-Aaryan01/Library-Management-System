<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: index.php");
    exit;
}
include 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $student_id = $_POST['student_id'];
    $conn->query("INSERT INTO issued_books (book_id, student_id) VALUES ($book_id, $student_id)");
    echo "<div class='success-message'>Book successfully issued to student!</div>";
}

$books = $conn->query("SELECT * FROM books WHERE available = TRUE");
$students = $conn->query("SELECT * FROM users WHERE role='student'");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            animation: fadeInDown 0.8s ease;
        }

        h2:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, #3498db, #2980b9);
            transform: scaleX(0);
            transform-origin: center;
            animation: scaleIn 0.8s 0.5s forwards;
        }

        .issue-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.8s 0.3s forwards;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 500;
        }

        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 16px;
            color: #2c3e50;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 12px top 50%;
            background-size: 12px auto;
            padding-right: 30px;
        }

        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        button:active {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .ripple {
            position: absolute;
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        .success-message {
            background-color: #2ecc71;
            color: white;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
            font-weight: 500;
            text-align: center;
            position: relative;
            animation: slideInDown 0.5s ease;
            width: 100%;
            max-width: 500px;
        }

        .success-message:before {
            content: "✓";
            margin-right: 8px;
            font-weight: bold;
        }

        .back-link {
            display: block;
            margin-top: 25px;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .back-link:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: #3498db;
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .back-link:hover:after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .back-link:hover {
            color: #2980b9;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            to {
                transform: scaleX(1);
            }
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <h2>Issue Book to Student</h2>
    
    <form method="post" class="issue-form">
        <div class="form-group">
            <label for="book_id">Select Book:</label>
            <select name="book_id" id="book_id" required>
                <option value="" disabled selected>-- Select a Book --</option>
                <?php while ($b = $books->fetch_assoc()): ?>
                    <option value="<?= $b['id'] ?>"><?= $b['title'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="student_id">Select Student:</label>
            <select name="student_id" id="student_id" required>
                <option value="" disabled selected>-- Select a Student --</option>
                <?php while ($s = $students->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= $s['name'] ?> (<?= $s['email'] ?>)</option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <button type="submit" id="issueBtn">Issue Book</button>
    </form>
    
    <a href="teacher_dashboard.php" class="back-link">Back to Dashboard</a>
    
    <script>
        // Add ripple effect to button
        document.getElementById('issueBtn').addEventListener('click', function(e) {
            const button = this;
            const x = e.clientX - button.getBoundingClientRect().left;
            const y = e.clientY - button.getBoundingClientRect().top;
            
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            button.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    </script>
</body>
</html>