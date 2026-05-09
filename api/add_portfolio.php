<?php
/**
 * API: Add Portfolio
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireRole('freelancer');
Security::verifyCsrf();

$userId = Auth::id();
$title  = Security::clean($_POST['title'] ?? '');
$desc   = Security::clean($_POST['description'] ?? '');
$url    = Security::clean($_POST['project_url'] ?? '');

if (empty($title)) {
    Security::jsonError('Project title is required.');
}

$dbPath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['image'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'port_' . $userId . '_' . time() . '.' . $ext;
    $uploadDir = __DIR__ . '/../assets/uploads/portfolios/';
    
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        $dbPath = '/assets/uploads/portfolios/' . $filename;
    }
}

DB::query("INSERT INTO portfolios (user_id, title, description, image_url, project_url) VALUES (?, ?, ?, ?, ?)", 
           [$userId, $title, $desc, $dbPath, $url]);

Security::jsonOk(['message' => 'Project added to portfolio!']);
