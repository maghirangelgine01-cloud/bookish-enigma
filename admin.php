<?php
session_start();
include 'connect.php';


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: web.php");
    exit();
}

$message = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $id = intval($_POST['id']);
        $student_name = trim($_POST['student_name']);
        $average = floatval($_POST['average']);
        $grade = trim($_POST['grade']);

        if (empty($student_name) || $average < 0 || $average > 100 || !in_array($grade, ['P', 'F'])) {
            $message = '<p style="color:red;">Invalid input. Please check the fields.</p>';
        } else {
            $stmt = $conn->prepare("UPDATE grades SET student_name = ?, average = ?, grade = ? WHERE id = ?");
            $stmt->bind_param("sdsi", $student_name, $average, $grade, $id);
            if ($stmt->execute()) {
                $message = '<p style="color:green;">Record updated successfully.</p>';
            } else {
                $message = '<p style="color:red;">Error updating record: ' . $conn->error . '</p>';
            }
            $stmt->close();
        }
    } elseif (isset($_POST['delete'])) {
        $id = intval($_POST['delete_id']);
        $stmt = $conn->prepare("DELETE FROM grades WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $message = '<p style="color:green;">Record deleted successfully.</p>';
        } else {
            $message = '<p style="color:red;">Error deleting record: ' . $conn->error . '</p>';
        }
        $stmt->close();
    }
}

$sql = "SELECT id, student_name, average, grade FROM grades ORDER BY id ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin.php" class="active">Dashboard</a>
    <a href="ranking.php">Rankings</a>
    <a href="report.html">Reports</a>
  </div>

  <div class="main-content">
    <header>
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
      <form action="web.php" method="POST" style="display:inline;">
        <button type="submit" class="logout-btn" onclick="window.location.href='web.php'">Logout</button>
      </form>
    </header>

    <div class="message"><?= $message ?></div>

    <h2 style="color:#b30000; margin-bottom:10px;">Student Records</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Average</th>
          <th>Grade</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['student_name']) ?></td>
              <td><?= htmlspecialchars(number_format($row['average'], 2)) ?>%</td>
              <td><?= htmlspecialchars($row['grade']) ?></td>
              <td>
                <button class="action-btn" onclick="openEditModal(<?= $row['id'] ?>, '<?= htmlspecialchars($row['student_name'], ENT_QUOTES) ?>', <?= $row['average'] ?>, '<?= $row['grade'] ?>')">Edit</button>
                <form method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                  <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                  <button type="submit" name="delete" class="action-btn delete-btn">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" style="text-align:center;">No student records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3>Edit Student Record</h3>
      <form method="POST">
        <input type="hidden" name="id" id="editId">
        <label>Name:</label>
        <input type="text" name="student_name" id="editName" required>
        <label>Average (%):</label>
        <input type="number" name="average" id="editAverage" min="0" max="100" step="0.01" required>
        <label>Grade:</label>
        <select name="grade" id="editGrade" required>
          <option value="P">P</option>
          <option value="F">F</option>
        </select>
        <button type="submit" name="update">Save</button>
        <button type="button" onclick="closeModal()">Cancel</button>
      </form>
    </div>
  </div>

  <script>
    function openEditModal(id, name, average, grade) {
      document.getElementById('editId').value = id;
      document.getElementById('editName').value = name;
      document.getElementById('editAverage').value = average;
      document.getElementById('editGrade').value = grade;
      document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    function confirmDelete() {
      return confirm('Are you sure you want to delete this record?');
    }

    window.onclick = function(event) {
      if (event.target == document.getElementById('editModal')) {
        closeModal();
      }
    }
  </script>
</body>
</html>

<?php $conn->close(); ?>
