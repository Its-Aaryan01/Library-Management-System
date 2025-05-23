<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <style>
        /* Base styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            overflow-x: hidden;
        }

        h2 {
            color: #303f9f;
            margin-top: 40px;
            position: relative;
            padding-bottom: 15px;
            text-align: center;
            animation: fadeInDown 0.8s ease-out;
        }

        h2:after {
            content: '';
            position: absolute;
            width: 60px;
            height: 4px;
            background: #3f51b5;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            animation: expandLine 1s ease-out forwards;
        }

        ul {
            list-style-type: none;
            padding: 0;
            width: 80%;
            max-width: 600px;
            margin: 40px auto;
            perspective: 1000px;
        }

        li {
            margin-bottom: 15px;
            transform-style: preserve-3d;
            animation: rotateIn 0.6s ease-out forwards;
            opacity: 0;
            transform: rotateX(-90deg);
        }

        li:nth-child(1) { animation-delay: 0.2s; }
        li:nth-child(2) { animation-delay: 0.4s; }
        li:nth-child(3) { animation-delay: 0.6s; }
        li:nth-child(4) { animation-delay: 0.8s; }

        li a {
            display: block;
            text-decoration: none;
            background: white;
            color: #3f51b5;
            padding: 18px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            font-weight: 500;
            font-size: 17px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        li a:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(63, 81, 181, 0.1), transparent);
            transition: width 0.5s ease;
            z-index: -1;
        }

        li a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(63, 81, 181, 0.2);
            color: #1a237e;
        }

        li a:hover:before {
            width: 100%;
            animation: shine 1.5s infinite;
        }

        li a:after {
            content: '→';
            position: absolute;
            right: 25px;
            opacity: 0;
            transition: all 0.3s ease;
        }

        li a:hover:after {
            opacity: 1;
            right: 20px;
        }

        /* Logout link */
        .logout {
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            background-color: #f44336;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            animation: fadeIn 1.5s ease-out;
        }

        .logout:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
        }

        .logout:after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .logout:hover:after {
            left: 100%;
        }

        /* Animated background */
        body:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 30%, rgba(41, 182, 246, 0.05) 0%, transparent 20%),
                        radial-gradient(circle at 80% 70%, rgba(63, 81, 181, 0.05) 0%, transparent 20%);
            animation: backgroundShift 15s infinite alternate ease-in-out;
            z-index: -1;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes expandLine {
            from { width: 0; }
            to { width: 80px; }
        }

        @keyframes rotateIn {
            to {
                opacity: 1;
                transform: rotateX(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        @keyframes backgroundShift {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            ul {
                width: 90%;
            }
            
            li a {
                padding: 15px 20px;
                font-size: 16px;
            }
            
            h2 {
                margin-top: 30px;
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <h2>Welcome, <?= $_SESSION['user']['name'] ?></h2>
    <ul>
        <li><a href="add_book.php">Add Book</a></li>
        <li><a href="remove_book.php">Remove Book</a></li>
        <li><a href="issue_book.php">Issue Book to Student</a></li>
        <li><a href="view_students.php">View Students</a></li>
        <li><a href="issued_books_teacher.php">View issued book</a></li>
        <li><a href="manage_requests.php">Manage Request</a></li>
    </ul>
    <a href="logout.php" class="logout">Logout</a>
</body>
</html>