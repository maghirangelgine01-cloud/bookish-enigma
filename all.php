<?php
session_start();
include 'connect.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'student') {
  header("Location: web.php");
  exit();
}

$query = "SELECT id, student_name, physics_score, chemistry_score, math_score, average, grade 
          FROM grades 
          ORDER BY average DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="all.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f6fa;
    }
    .logout-btn {
      background: #e74c3c;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      float: right;
    }
    .logout-btn:hover {
      background: #c0392b;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>Student Panel</h2>
    <a href="#" class="active">Dashboard</a>
    <a href="sub_ranking.php">Subject Rankings</a>
  </div>

  <div class="main-content">
    <header>
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
      <form action="web.php" method="POST" style="display:inline;">
        <button type="submit" class="logout-btn" onclick="window.location.href='web.php'">Logout</button>
      </form>
    </header>

    <h2 class="section-title">📊 Grade Rankings</h2>
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
        <?php
        if ($result->num_rows > 0) {
          $rank = 1;
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>#{$rank}</td>";
            echo "<td>{$row['student_name']}</td>";
            echo "<td>{$row['physics_score']}</td>";
            echo "<td>{$row['chemistry_score']}</td>";
            echo "<td>{$row['math_score']}</td>";
            echo "<td>" . number_format($row['average'], 2) . "%</td>";
            echo "<td>{$row['grade']}</td>";
            echo "</tr>";
            $rank++;
          }
        } else {
          echo "<tr><td colspan='8' style='text-align:center;'>No records found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</body>
</html>

<?php $conn->close(); ?>
<script>
  const rows = document.querySelectorAll('tbody tr');
  rows.forEach(row => {
    row.addEventListener('click', () => {
      rows.forEach(r => r.classList.remove('selected'));
      row.classList.add('selected');
    });
  });
</script>
