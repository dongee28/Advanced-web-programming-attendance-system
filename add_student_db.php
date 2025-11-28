<?php
require_once 'db_connect.php';

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName  = trim($_POST['fullname'] ?? '');
    $matricule = trim($_POST['matricule'] ?? '');
    $groupId   = trim($_POST['group_id'] ?? '');

    if ($fullName === '' || $matricule === '' || $groupId === '') {
        $feedback = 'All fields are required.';
    } else {
        $db = getConnection();
        if ($db) {
            $stmt = $db->prepare(
                'INSERT INTO students (fullname, matricule, group_id) VALUES (?, ?, ?)'
            );
            $stmt->bind_param('sss', $fullName, $matricule, $groupId);
            if ($stmt->execute()) {
                $feedback = 'Student added to database.';
            } else {
                $feedback = 'Error: ' . $db->error;
            }
            $stmt->close();
            $db->close();
        } else {
            $feedback = 'Database connection failed.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Student (DB)</title>
</head>
<body>
    <h1>Add Student (Database)</h1>
    <p><?php echo htmlspecialchars($feedback); ?></p>

    <form method="post">
        <label>
            Full name:
            <input type="text" name="fullname">
        </label><br><br>

        <label>
            Matricule:
            <input type="text" name="matricule">
        </label><br><br>

        <label>
            Group ID:
            <input type="text" name="group_id">
        </label><br><br>

        <button type="submit">Add</button>
    </form>
</body>
</html>
