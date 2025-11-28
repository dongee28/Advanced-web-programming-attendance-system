<?php
require_once 'db_connect.php';

$dbLink = getConnection();
$queryResult = null;
$errorText = '';

if ($dbLink) {
    $queryResult = $dbLink->query('SELECT * FROM students');
    if (!$queryResult) {
        $errorText = 'Query error: ' . $dbLink->error;
    }
} else {
    $errorText = 'Database connection failed.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>List Students</title>
</head>
<body>
    <h1>Students (Database)</h1>

    <?php if ($errorText !== ''): ?>
        <p><?php echo htmlspecialchars($errorText); ?></p>
    <?php elseif ($queryResult && $queryResult->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Full name</th>
                <th>Matricule</th>
                <th>Group</th>
            </tr>
            <?php while ($record = $queryResult->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['id']); ?></td>
                    <td><?php echo htmlspecialchars($record['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($record['matricule']); ?></td>
                    <td><?php echo htmlspecialchars($record['group_id']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No students found.</p>
    <?php endif; ?>

    <?php if ($dbLink) $dbLink->close(); ?>
</body>
</html>
