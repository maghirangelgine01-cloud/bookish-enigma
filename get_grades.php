<?php
include 'connect.php';


$result = $conn->query("SELECT id, student_name, physics_score, chemistry_score, math_score, total, average, grade FROM grades ORDER BY average DESC");

$grades = [];
while ($row = $result->fetch_assoc()) {
    $grades[] = [
        'id' => $row['id'],
        'student_name' => $row['student_name'],
        'physics' => $row['physics_score'],
        'chemistry' => $row['chemistry_score'],
        'math' => $row['math_score'],
        'total' => $row['total'],
        'average' => $row['average'],
        'grade' => $row['grade']
    ];
}

header('Content-Type: application/json');
echo json_encode($grades);

$conn->close();
?>
