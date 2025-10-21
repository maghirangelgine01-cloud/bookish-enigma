<?php
include 'connect.php';


$sql = "SELECT id, student_name, chemistry_score, average, grade 
        FROM grades ORDER BY chemistry_score DESC";
$result = $conn->query($sql);

$rank = 1;
?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="chem.css">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chemistry Rankings | Admin</title>
  
</head>
<body>
  
  <div class="sidebar">
    <h2>Student Panel</h2>
    <a href="all.php">Dashboard</a>
   
  </div>

  
  <div class="main-content">
    <header>
      <h1>🧪 Chemistry Rankings</h1>
       <p class="teacher-info">Taught by: PROF. MALIGAYA</p>
      <button class="logout-btn" onclick="window.location.href='all.php'">Back</button>

      <div class="subject-tabs">
      <a href="sub_ranking.php" class="<?= $subject == 'physics' ? 'active' : '' ?>">Physics</a>
      <a href="chem.php" class="<?= $subject == 'chemistry' ? 'active' : '' ?>">Chemistry</a>
      <a href="math.php" class="<?= $subject == 'math' ? 'active' : '' ?>">Math</a>
    </div>
    </header>

    <table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Student Name</th>
          <th>Chemistry Score</th>
          <th>Average</th>
          <th>Grade</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="rank-<?= $rank <= 3 ? $rank : '' ?>">
            <td>#<?= $rank ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= $row['chemistry_score'] ?></td>
            <td><?= number_format($row['average'], 2) ?>%</td>
            <td><?= $row['grade'] ?></td>
          </tr>
          <?php $rank++; endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
