<?php
session_start();
include('db/config.php');

// Redirect non-students to login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: index.php");
    exit;
}

// Book request logic
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $student_id = $_SESSION['user']['id']; // Corrected session variable

    // Check for existing request
    $check = $conn->prepare("SELECT * FROM book_requests WHERE student_id = ? AND book_id = ? AND status = 'Pending'");
    $check->bind_param("ii", $student_id, $book_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO book_requests (student_id, book_id, status) VALUES (?, ?, 'Pending')");
        $stmt->bind_param("ii", $student_id, $book_id);
        $stmt->execute();
        $stmt->close();
        $message = "✅ Request sent successfully!";
    } else {
        $message = "⚠️ You already requested this book.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Books</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        .message {
            text-align: center;
            font-weight: bold;
            color: #27ae60;
            margin-bottom: 15px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e1e1e1;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        button {
            padding: 8px 15px;
            border: none;
            background-color: #3498db;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .no-books {
            text-align: center;
            color: #888;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Available Books</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <table>
        <tr>
            <th>Book Name</th>
            <th>Author</th>
            <th>Action</th>
        </tr>

        <?php
        $query = "SELECT * FROM books WHERE available = 1";
        $result = $conn->query($query);

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['author']) ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="book_id" value="<?= $row['id'] ?>">
                    <button type="submit">Request</button>
                </form>
            </td>
        </tr>
        <?php
            endwhile;
        else:
        ?>
            <tr>
                <td colspan="3" class="no-books">No books currently available.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
