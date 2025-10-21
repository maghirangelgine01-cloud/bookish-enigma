<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $studentName = $_POST['studentName'] ?? '';
    $physics = $_POST['physics'] ?? 0;
    $chemistry = $_POST['chemistry'] ?? 0;
    $math = $_POST['math'] ?? 0;

    
    $total = $physics + $chemistry + $math;
    $average = $total / 3;

    
    if ($average >= 90) $grade = "P";
    elseif ($average >= 80) $grade = "P";
    elseif ($average >= 70) $grade = "P";
    elseif ($average >= 60) $grade = "P";
    else $grade = "F";

    
    $stmt = $conn->prepare("INSERT INTO grades (student_name, physics_score, chemistry_score, math_score, total, average, grade)
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiiids", $studentName, $physics, $chemistry, $math, $total, $average, $grade);

    if ($stmt->execute()) {
        echo "✅ Record successfully added!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "❌ Invalid request method.";
}
?>
