<?php require 'config.php';
// READ: list all students
$rows = $pdo->query('SELECT id,name,roll_no,course,email FROM students ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Students</title>
<link rel="stylesheet" href="../frontend/style.css">
<style>body{align-items:flex-start}.card{max-width:900px}table{width:100%;border-collapse:collapse}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:left}th{background:#e3ecfa}
a.btn{margin-right:8px;color:#1f4e9c}.del{color:#c62828}</style></head><body>
<main class="card"><h1>Student Records</h1>
<p><a class="btn" href="../frontend/register.html">+ Add Student</a></p>
<table><tr><th>ID</th><th>Name</th><th>Roll No</th><th>Course</th><th>Actions</th></tr>
<?php foreach ($rows as $r): ?>
<tr><td><?= $r['id'] ?></td><td><?= h($r['name']) ?></td><td><?= h($r['roll_no']) ?></td><td><?= h($r['course']) ?></td>
<td><a class="btn" href="view.php?id=<?= $r['id'] ?>">View</a>
    <a class="btn" href="edit.php?id=<?= $r['id'] ?>">Edit</a>
    <a class="btn del" href="delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete this student?')">Delete</a></td></tr>
<?php endforeach; ?>
<?php if (!$rows): ?><tr><td colspan="5">No records yet.</td></tr><?php endif; ?>
</table></main></body></html>
