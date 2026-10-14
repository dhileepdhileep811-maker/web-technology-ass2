<?php require 'config.php';
$id = (int)($_GET['id'] ?? 0);
// remove physical files first, then the row (file metadata rows go via ON DELETE CASCADE)
$q = $pdo->prepare('SELECT file_path FROM student_files WHERE student_id=?'); $q->execute([$id]);
foreach ($q->fetchAll(PDO::FETCH_COLUMN) as $p) @unlink(UPLOAD_DIR . basename($p));
$pdo->prepare('DELETE FROM students WHERE id=?')->execute([$id]);
header('Location: index.php');
