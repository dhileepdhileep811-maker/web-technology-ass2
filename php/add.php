<?php require 'config.php';
// CREATE: receives the form from frontend/register.html
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../frontend/register.html'); exit; }
$name = trim($_POST['name'] ?? ''); $roll = trim($_POST['roll_no'] ?? '');
$email = trim($_POST['email'] ?? ''); $course = trim($_POST['course'] ?? '');
$errors = [];
// server-side validation (client-side JS can be bypassed)
if (strlen($name) < 3) $errors[] = 'Invalid name';
if (!preg_match('/^[0-9]{2}[A-Za-z]{2,4}[0-9]{2,3}$/', $roll)) $errors[] = 'Invalid roll number';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if ($course === '') $errors[] = 'Course required';
if (!$errors) {
    try {
        $pdo->prepare('INSERT INTO students (name,roll_no,email,course) VALUES (?,?,?,?)')
            ->execute([$name, $roll, $email, $course]);   // prepared statement => no SQL injection
        $id = (int)$pdo->lastInsertId();
        foreach (['profile' => 'profile', 'id_proof' => 'id_proof', 'certificate' => 'certificate'] as $field => $cat) {
            if ($e = saveUpload($pdo, $id, $field, $cat)) $errors[] = $e;
        }
    } catch (PDOException $ex) {
        $errors[] = $ex->getCode() == 23000 ? 'Roll number already exists' : 'Database error';
    }
}
if ($errors) { echo '<h3>Errors</h3><ul>'; foreach ($errors as $e) echo '<li>' . h($e) . '</li>'; echo '</ul><a href="javascript:history.back()">Back</a>'; exit; }
header('Location: view.php?id=' . $id);
