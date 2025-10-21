<?php
include 'connect.php';


$subject = isset($_GET['subject']) ? strtolower($_GET['subject']) : 'physics';


$valid_subjects = ['physics', 'chemistry', 'math'];
if (!in_array($subject, $valid_subjects)) {
    $subject = 'physics';
}


$subject_title = ucfirst($subject);


$sql = "SELECT student_name, physics_score, chemistry_score, math_score, average, grade 
        FROM grades ORDER BY {$subject}_score DESC";
$result = $conn->query($sql);

$rank = 1;
?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="sub_ranking.css">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $subject_title ?> Rankings | Admin</title>
  
</head>
<body>
  
  <div class="sidebar">
    <h2>Student Panel</h2>
    <a href="all.php">Dashboard</a>
    
    
  </div>

 
  <div class="main-content">
    <header>
      <div>
        <h1>📘 <?= $subject_title ?> Rankings</h1>
        <p class="teacher-info">Taught by: PROF. MAZO</p>
      </div>
      <button class="logout-btn" onclick="window.location.href='dash.html'">Back</button>
    </header>

    <div class="subject-tabs">
      <a href="sub_ranking.php" class="<?= $subject == 'physics' ? 'active' : '' ?>">Physics</a>
      <a href="chem.php" class="<?= $subject == 'chemistry' ? 'active' : '' ?>">Chemistry</a>
      <a href="math.php" class="<?= $subject == 'math' ? 'active' : '' ?>">Math</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Name</th>
          <th><?= $subject_title ?> Score</th>
          <th>Average</th>
          <th>Grade</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="rank-<?= $rank <= 3 ? $rank : '' ?>">
            <td>#<?= $rank ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= $row[$subject . '_score'] ?></td>
            <td><?= number_format($row['average'], 2) ?>%</td>
            <td><?= $row['grade'] ?></td>
          </tr>
          <?php $rank++; endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
