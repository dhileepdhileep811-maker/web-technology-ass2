<?php require 'config.php';
$id = (int)($_GET['id'] ?? 0);
$s = $pdo->prepare('SELECT * FROM students WHERE id=?'); $s->execute([$id]); $stu = $s->fetch();
if (!$stu) { http_response_code(404); exit('Student not found'); }
$f = $pdo->prepare('SELECT * FROM student_files WHERE student_id=? ORDER BY uploaded_at'); $f->execute([$id]);
$files = $f->fetchAll();
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title><?= h($stu['name']) ?></title>
<link rel="stylesheet" href="../frontend/style.css"><style>img.p{max-width:140px;border-radius:8px}li{margin:6px 0}</style></head><body>
<main class="card"><h1><?= h($stu['name']) ?></h1>
<p><b>Roll No:</b> <?= h($stu['roll_no']) ?><br><b>Email:</b> <?= h($stu['email']) ?><br>
<b>Course:</b> <?= h($stu['course']) ?><br><b>Registered:</b> <?= h($stu['created_at']) ?></p>
<h3>Uploaded Files</h3><ul>
<?php foreach ($files as $x): $url = 'uploads/' . rawurlencode($x['file_path']); ?>
<li><?php if (str_starts_with($x['file_type'], 'image/')): ?>
      <img class="p" src="<?= $url ?>" alt=""><br>
    <?php endif; ?>
    <?= h($x['category']) ?> — <a href="<?= $url ?>" target="_blank"><?= h($x['original_name']) ?></a>
    (<?= h($x['file_type']) ?>, <?= round($x['file_size']/1024) ?> KB, <?= h($x['uploaded_at']) ?>)</li>
<?php endforeach; if (!$files) echo '<li>No files</li>'; ?></ul>
<a href="index.php">← Back</a> | <a href="edit.php?id=<?= $id ?>">Edit</a></main></body></html>
