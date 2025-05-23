<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

require 'db/config.php';

$student_id = $_SESSION['user']['id'];

$sql = "SELECT b.id, b.title, b.author, i.issue_date, i.due_date
        FROM issued_books i
        JOIN books b ON i.book_id = b.id
        WHERE i.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($book_id, $book_name, $author_name, $issue_date, $due_date);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Books</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f9ff;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            margin-top: 40px;
            color: #00796b;
            animation: fadeInDown 0.8s ease-out;
        }

        table {
            border-collapse: collapse;
            margin-top: 30px;
            width: 90%;
            max-width: 800px;
            background-color: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            animation: fadeIn 1s ease;
        }

        th, td {
            padding: 14px 20px;
            text-align: left;
        }

        th {
            background-color: #00796b;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #e0f2f1;
        }

        a.back {
            margin-top: 20px;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #00796b;
            color: white;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        a.back:hover {
            background-color: #004d40;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <h2>Your Issued Books</h2>
    <table>
        <tr>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>Author</th>
            <th>Issue Date</th>
            <th>Due Date</th>
        </tr>
        <?php
        if ($stmt->num_rows > 0) {
            while ($stmt->fetch()) {
                echo "<tr>
                        <td>$book_id</td>
                        <td>$book_name</td>
                        <td>$author_name</td>
                        <td>$issue_date</td>
                        <td>$due_date</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>No books issued.</td></tr>";
        }
        ?>
    </table>
    <a href="student_dashboard.php" class="back">Back to Dashboard</a>
</body>
</html>
