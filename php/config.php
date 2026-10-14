<?php
// Database connection (PDO with exceptions) + shared helpers
$pdo = new PDO('mysql:host=localhost;dbname=student_portal;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_SIZE', 2 * 1024 * 1024);
// allowed MIME types per category
define('ALLOWED', [
    'profile'     => ['image/jpeg', 'image/png'],
    'id_proof'    => ['image/jpeg', 'image/png', 'application/pdf'],
    'certificate' => ['application/pdf'],
]);
function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); } // prevent XSS

/**
 * Validate and save one uploaded file, then record its metadata.
 * Returns an error string, or '' on success (or when no file was sent).
 */
function saveUpload(PDO $pdo, int $studentId, string $field, string $category): string {
    if (empty($_FILES[$field]['name'])) return '';
    $f = $_FILES[$field];
    if ($f['error'] !== UPLOAD_ERR_OK) return "$field: upload failed (code {$f['error']})";
    if ($f['size'] > MAX_SIZE) return "$field: file exceeds 2 MB";
    // detect real MIME type from content, not from the (spoofable) browser value
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    if (!in_array($mime, ALLOWED[$category], true)) return "$field: type $mime not allowed";
    // unique name => an existing file is never overwritten
    $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
    $stored = $studentId . '_' . $category . '_' . bin2hex(random_bytes(6)) . '.' . strtolower($ext);
    if (!move_uploaded_file($f['tmp_name'], UPLOAD_DIR . $stored)) return "$field: could not save file";
    $pdo->prepare('INSERT INTO student_files (student_id,category,original_name,file_path,file_type,file_size)
                   VALUES (?,?,?,?,?,?)')
        ->execute([$studentId, $category, $f['name'], $stored, $mime, $f['size']]);
    return '';
}
