<?php
$currentDate = date('Y-m-d');
$attendanceFile = 'attendance_' . $currentDate . '.json';
$statusMessage = '';

$students = [];
if (file_exists('students.json')) {
    $data = file_get_contents('students.json');
    $decoded = json_decode($data, true);
    if (is_array($decoded)) {
        $students = $decoded;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($attendanceFile)) {
        $statusMessage = 'Attendance for today has already been taken.';
    } else {
        $records = [];
        foreach ($students as $studentItem) {
            $sid = $studentItem['student_id'];
            $choice = isset($_POST['status_' . $sid]) ? $_POST['status_' . $sid] : 'absent';
            $records[] = [
                'student_id' => $sid,
                'status'     => $choice
            ];
        }
        file_put_contents($attendanceFile, json_encode($records, JSON_PRETTY_PRINT));
        $statusMessage = 'Attendance saved for ' . $currentDate . '.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Take Attendance (JSON)</title>
</head>
<body>
    <h1>Take Attendance (JSON)</h1>
    <p>Date: <?php echo htmlspecialchars($currentDate); ?></p>
    <p><?php echo htmlspecialchars($statusMessage); ?></p>

    <?php if (empty($students)): ?>
        <p>No students found. Please add students first (add_student.php).</p>
    <?php else: ?>
        <form method="post">
            <table border="1" cellpadding="5">
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Present</th>
                    <th>Absent</th>
                </tr>
                <?php foreach ($students as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td><?php echo htmlspecialchars($item['group']); ?></td>
                        <td>
                            <input
                                type="radio"
                                name="status_<?php echo $item['student_id']; ?>"
                                value="present"
                                checked
                            >
                        </td>
                        <td>
                            <input
                                type="radio"
                                name="status_<?php echo $item['student_id']; ?>"
                                value="absent"
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button type="submit">Save Attendance</button>
        </form>
    <?php endif; ?>
</body>
</html>
