<?php
$statusText = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid   = trim($_POST['student_id'] ?? '');
    $sname = trim($_POST['name'] ?? '');
    $sgroup = trim($_POST['group'] ?? '');

    if ($sid === '' || $sname === '' || $sgroup === '') {
        $statusText = 'All fields are required.';
    } elseif (!ctype_digit($sid)) {
        $statusText = 'Student ID must be numbers only.';
    } else {
        $list = [];
        if (file_exists('students.json')) {
            $raw = file_get_contents('students.json');
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $list = $decoded;
            }
        }

        $list[] = [
            'student_id' => $sid,
            'name'       => $sname,
            'group'      => $sgroup
        ];

        file_put_contents('students.json', json_encode($list, JSON_PRETTY_PRINT));
        $statusText = 'Student added successfully to students.json.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Student (JSON)</title>
</head>
<body>
    <h1>Add Student (JSON)</h1>

    <p><?php echo htmlspecialchars($statusText); ?></p>

    <form method="post">
        <label>
            Student ID:
            <input type="text" name="student_id">
        </label><br><br>

        <label>
            Name:
            <input type="text" name="name">
        </label><br><br>

        <label>
            Group:
            <input type="text" name="group">
        </label><br><br>

        <button type="submit">Add</button>
    </form>
</body>
</html>
