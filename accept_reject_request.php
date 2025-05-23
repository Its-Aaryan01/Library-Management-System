<?php
session_start();
include('db/config.php');

// Ensure only teachers can access this page
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Handle Accept or Reject actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        $update_query = "UPDATE book_requests SET status='Accepted' WHERE id=?";
    } elseif ($action == 'reject') {
        $update_query = "UPDATE book_requests SET status='Rejected' WHERE id=?";
    }

    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all pending requests
$query = "SELECT br.id, br.student_id, br.book_id, b.name AS book_name, s.name AS student_name
          FROM book_requests br
          JOIN books b ON br.book_id = b.id
          JOIN students s ON br.student_id = s.id
          WHERE br.status = 'Pending'";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Accept/Reject Book Requests</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h2>Pending Book Requests</h2>
<table>
    <tr>
        <th>Student</th>
        <th>Book</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['student_name']; ?></td>
        <td><?php echo $row['book_name']; ?></td>
        <td>
            <form method="POST">
                <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="action" value="accept">Accept</button>
                <button type="submit" name="action" value="reject">Reject</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
</body>
</html>