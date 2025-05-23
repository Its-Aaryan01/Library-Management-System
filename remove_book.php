<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: index.php");
    exit;
}
include 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $conn->query("DELETE FROM books WHERE id = $book_id");
    echo "Book removed.";
}

$books = $conn->query("SELECT * FROM books");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Remove Book</title>
    <style>
        /* Base styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h2 {
            color: #d84315;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
            animation: fadeIn 1s ease-in-out;
        }

        h2:after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            background: #d84315;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            animation: lineExpand 1.5s forwards ease-out;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 500px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: slideIn 0.6s ease-out;
            transform-origin: top center;
        }

        select {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="6"><path d="M0 0h12L6 6z" fill="%23d84315"/></svg>') no-repeat;
            background-position: calc(100% - 15px) center;
            background-color: white;
            cursor: pointer;
        }

        select:hover {
            border-color: #d84315;
        }

        select:focus {
            outline: none;
            border-color: #d84315;
            box-shadow: 0 0 0 2px rgba(216, 67, 21, 0.2);
        }

        button {
            background-color: #d84315;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background-color: #bf360c;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button:active {
            transform: translateY(0);
        }

        button::after {
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

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes lineExpand {
            from { width: 0; }
            to { width: 100px; }
        }

        @keyframes slideIn {
            from { 
                opacity: 0;
                transform: translateY(-20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            20% {
                transform: scale(25, 25);
                opacity: 0.5;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            form {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <h2>Remove a Book</h2>
    <form method="post">
        <select name="book_id">
            <?php while ($b = $books->fetch_assoc()): ?>
                <option value="<?= $b['id'] ?>"><?= $b['title'] ?> by <?= $b['author'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Remove</button>
    </form>
</body>
</html>