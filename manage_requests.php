
<?php
session_start();
include('db/config.php');

// Only allow teacher access
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: index.php");
    exit;
}

// Handle Accept/Reject Actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'], $_POST['action'])) {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    // Get student_id and book_id for this request
    $stmt = $conn->prepare("SELECT student_id, book_id FROM book_requests WHERE id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->bind_result($student_id, $book_id);
    $stmt->fetch();
    $stmt->close();

    // Update the status of the request
    if (in_array($action, ['Accepted', 'Rejected'])) {
        $stmt = $conn->prepare("UPDATE book_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $request_id);
        $stmt->execute();
        $stmt->close();

        // If accepted, issue the book and set due date
        if ($action === 'Accepted') {
            $issue_date = date('Y-m-d');
            $due_date = date('Y-m-d', strtotime('+10 days'));

            $stmt = $conn->prepare("INSERT INTO issued_books (book_id, student_id, issue_date, due_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $book_id, $student_id, $issue_date, $due_date);
            $stmt->execute();
            $stmt->close();

            // Mark book as unavailable
            $conn->query("UPDATE books SET available = FALSE WHERE id = $book_id");
        }
    }
}

// Fetch all book requests
$result = $conn->query("
    SELECT br.id, br.status, u.name AS student_name, u.email, b.title AS book_title
    FROM book_requests br
    JOIN users u ON br.student_id = u.id
    JOIN books b ON br.book_id = b.id
    ORDER BY br.status DESC, br.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Book Requests</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .actions form {
            display: flex;
            gap: 8px;
        }
        .actions button {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .accept {
            background-color: #2ecc71;
            color: white;
        }
        .reject {
            background-color: #e74c3c;
            color: white;
        }
        .status {
            font-weight: bold;
            padding: 6px 10px;
            border-radius: 5px;
        }
        .Accepted {
            background-color: #27ae60;
            color: white;
        }
        .Rejected {
            background-color: #c0392b;
            color: white;
        }
        .Pending {
            background-color: #f39c12;
            color: white;
        }
        .back-link {
            display: block;
            margin: 20px auto;
            width: fit-content;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Manage Book Requests</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Student</th>
        <th>Book</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['student_name'] ?> (<?= $row['email'] ?>)</td>
            <td><?= $row['book_title'] ?></td>
            <td><span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
            <td class="actions">
                <?php if ($row['status'] === 'Pending'): ?>
                    <form method="post">
                        <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="action" value="Accepted" class="accept">Accept</button>
                        <button type="submit" name="action" value="Rejected" class="reject">Reject</button>
                    </form>
                <?php else: ?>
                    <em>No action available</em>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a class="back-link" href="teacher_dashboard.php">← Back to Dashboard</a>

</body>
</html>
