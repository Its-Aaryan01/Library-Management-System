<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: index.php");
    exit;
}

require 'db/config.php'; // Your DB connection file

// Query to get issued books with book and student details
$sql = "SELECT i.id, b.title, b.author, u.name AS name, i.issue_date, i.due_date
        FROM issued_books i
        JOIN books b ON i.book_id = b.id
        JOIN users u ON i.student_id = u.id
        WHERE u.role = 'student'
        ORDER BY i.issue_date DESC";


$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Books - Teacher View</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }
        h2 {
            color: #3f51b5;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 90%;
            max-width: 900px;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px 20px;
            text-align: left;
        }
        th {
            background-color: #3f51b5;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f4f7fc;
        }
        tr:hover {
            background-color: #e8ebf9;
        }
        .no-record {
            text-align: center;
            margin-top: 40px;
            color: #666;
            font-size: 18px;
        }
        a.logout {
            display: block;
            width: 120px;
            margin: 30px auto 0;
            text-align: center;
            background: #f44336;
            color: white;
            padding: 12px 0;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        a.logout:hover {
            background: #d32f2f;
        }
    </style>
</head>
<body>
    <h2>Issued Books List</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Issued To</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['author']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['issue_date']) ?></td>
                        <td><?= htmlspecialchars($row['due_date']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-record">No books have been issued yet.</p>
    <?php endif; ?>

    <a href="teacher_dashboard.php" class="logout">Back to Dashboard</a>
</body>
</html>
