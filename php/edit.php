<?php require 'config.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$s = $pdo->prepare('SELECT * FROM students WHERE id=?'); $s->execute([$id]); $stu = $s->fetch();
if (!$stu) { http_response_code(404); exit('Student not found'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {   // UPDATE
    $name = trim($_POST['name']); $email = trim($_POST['email']); $course = trim($_POST['course']);
    if (strlen($name) < 3) $errors[] = 'Invalid name';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
    if (!$errors) {
        $pdo->prepare('UPDATE students SET name=?,email=?,course=? WHERE id=?')->execute([$name,$email,$course,$id]);
        // optional new files are ADDED (old ones kept), so nothing is overwritten
        foreach (['profile','id_proof','certificate'] as $c) if ($e = saveUpload($pdo, $id, $c, $c)) $errors[] = $e;
        if (!$errors) { header("Location: view.php?id=$id"); exit; }
    }
}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Edit</title><link rel="stylesheet" href="../frontend/style.css"></head><body>
<main class="card"><h1>Edit Student</h1>
<?php foreach ($errors as $e) echo '<p class="error">' . h($e) . '</p>'; ?>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="id" value="<?= $id ?>">
<div class="field"><label>Name</label><input name="name" value="<?= h($stu['name']) ?>" required></div>
<div class="field"><label>Roll No (not editable)</label><input value="<?= h($stu['roll_no']) ?>" disabled></div>
<div class="field"><label>Email</label><input type="email" name="email" value="<?= h($stu['email']) ?>" required></div>
<div class="field"><label>Course</label><input name="course" value="<?= h($stu['course']) ?>" required></div>
<div class="field"><label>Profile image</label><input type="file" name="profile" accept="image/jpeg,image/png"></div>
<div class="field"><label>ID proof (image/PDF)</label><input type="file" name="id_proof"></div>
<div class="field"><label>Certificate (PDF)</label><input type="file" name="certificate" accept="application/pdf"></div>
<button>Save</button></form></main></body></html>
