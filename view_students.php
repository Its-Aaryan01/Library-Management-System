
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: index.php");
    exit;
}
include 'db/config.php';

// Get students
$students = $conn->query("SELECT * FROM users WHERE role='student' ORDER BY name ASC");

// For each student, get their issued books
$issued_books_stmt = $conn->prepare("SELECT b.title, b.author, ib.issue_date FROM issued_books ib JOIN books b ON ib.book_id = b.id WHERE ib.student_id = ?");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Students - Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background: linear-gradient(120deg, #f8f8fc 0%, #d8e4fa 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 1050px;
            margin: 50px auto 30px auto;
            padding: 24px 10px 60px 10px;
        }
        .header-title {
            color: #49389d;
            font-size: 2.4rem;
            font-weight: 700;
            text-align: center;
            letter-spacing: 1px;
            margin-bottom: 34px;
            position: relative;
        }
        .header-title:after {
            content: '';
            display: block;
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, #9b87f5 10%, #3498db 90%);
            border-radius: 4px;
            margin: 16px auto 0;
        }
        .student-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
            gap: 28px;
        }
        .student-card {
            background: rgba(255,255,255,.95);
            border-radius: 18px;
            box-shadow: 0 4px 16px 0 rgba(155,135,245,0.10), 0 3px 12px rgba(52,152,219,0.06);
            transition: box-shadow .25s, transform .18s;
            position: relative;
            overflow: hidden;
            padding: 0;
        }
        .student-card:hover {
            box-shadow: 0 12px 34px 0 rgba(52,152,219,0.15), 0 9px 34px rgba(155,135,245,0.11);
            transform: translateY(-3px) scale(1.01);
        }
        .student-header {
            padding: 26px 32px 20px 32px;
            background: linear-gradient(90deg, #b59cf5 0%, #8ec0fd 100%);
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .student-avatar {
            width: 54px;
            height: 54px;
            background: #f1f1f8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.1rem;
            color: #7E69AB;
            border: 2px solid #fff;
            box-shadow: 0 1px 7px #bbaae36c;
        }
        .student-details {
            flex: 1;
        }
        .student-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: #403e43;
        }
        .student-email {
            font-size: 0.99rem;
            color: #8A898C;
        }
        .student-meta {
            margin-left: auto;
            text-align: right;
            color: #9b87f5;
            min-width: 100px;
            font-size: 0.98rem;
            font-weight: 500;
            letter-spacing: .2px;
        }
        .issued-section {
            background: linear-gradient(90deg, #fbfaff 0%, #e7e8fd 100%);
            padding: 22px 30px 16px 30px;
            border-top: 1px solid #dbccfa;
        }
        .issued-title {
            font-weight: 600;
            color: #49389d;
            margin-bottom: 15px;
            font-size: 1.09rem;
            letter-spacing: .2px;
        }
        .issued-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .issued-item {
            padding: 10px 0;
            border-bottom: 1px solid #ede7fa;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }
        .issued-item:last-child {
            border-bottom: none;
        }
        .book-title {
            font-weight: 500;
            color: #7E69AB;
            font-size: 1.02rem;
        }
        .book-meta {
            color: #7c7fa7;
            font-size: 0.96rem;
            margin-left: 10px;
            opacity: 0.9;
        }
        .no-issued {
            color: #B7BAC1;
            font-style: italic;
            margin: 0;
            padding: 0 5px 12px 5px;
        }
        @media(max-width: 600px) {
            .container { padding: 16px 5vw 60px; }
            .student-header, .issued-section { padding: 13px 13px 10px 13px; }
            .student-avatar { width: 38px; height:38px; font-size: 1.11rem;}
            .student-name { font-size: 1rem;}
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-title">Students & Issued Books</div>
    <div class="student-cards">
        <?php if ($students->num_rows == 0): ?>
            <div class="student-card" style="grid-column: 1/-1; text-align:center;padding:35px 0;font-size:1.17rem; color:#a6a2c2;">
                No students found.
            </div>
        <?php endif; ?>
        <?php while ($student = $students->fetch_assoc()): ?>
            <div class="student-card">
                <div class="student-header">
                    <div class="student-avatar"><?= strtoupper(substr($student['name'],0,1)) ?></div>
                    <div class="student-details">
                        <div class="student-name"><?= htmlspecialchars($student['name']) ?></div>
                        <div class="student-email"><?= htmlspecialchars($student['email']) ?></div>
                    </div>
                    <div class="student-meta" title="Student ID">#<?= $student['id'] ?></div>
                </div>
                <div class="issued-section">
                    <div class="issued-title">Issued Books:</div>
                    <ul class="issued-list">
                        <?php
                        $issued_books_stmt->bind_param("i", $student['id']);
                        $issued_books_stmt->execute();
                        $result = $issued_books_stmt->get_result();
                        if ($result->num_rows == 0): ?>
                            <li class="no-issued">No books issued</li>
                        <?php else:
                            while ($book = $result->fetch_assoc()): ?>
                                <li class="issued-item">
                                    <span class="book-title"><?= htmlspecialchars($book['title']) ?></span>
                                    <span class="book-meta">by <?= htmlspecialchars($book['author']) ?> · Issued: <?= htmlspecialchars($book['issue_date']) ?></span>
                                </li>
                            <?php endwhile;
                        endif;
                        $result->free();
                        ?>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
