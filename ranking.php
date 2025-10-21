<?php
include 'connect.php';


$result = $conn->query
("SELECT student_name, physics_score, chemistry_score, math_score, average, grade 
                        FROM grades ORDER BY average DESC");

$rank = 1;
?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="ranking.css">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rankings | Admin</title>
  
</head>
<body>
  
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin.php">Dashboard</a>
    <a href="rankings.php" class="active">Rankings</a>
    <a href="report.html">Reports</a>
  </div>

  
  <div class="main-content">
    <header>
      <h1>🏆 Student Rankings</h1>
      <button class="logout-btn" onclick="window.location.href='web.html'">Back</button>
    </header>

    <table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Name</th>
          <th>Physics</th>
          <th>Chemistry</th>
          <th>Math</th>
          <th>Average</th>
          <th>Grade</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr class="rank-<?= $rank <= 3 ? $rank : '' ?>">
            <td>#<?= $rank ?></td>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= $row['physics_score'] ?></td>
            <td><?= $row['chemistry_score'] ?></td>
            <td><?= $row['math_score'] ?></td>
            <td><?= number_format($row['average'], 2) ?>%</td>
            <td><?= $row['grade'] ?></td>
          </tr>
          <?php $rank++; endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
