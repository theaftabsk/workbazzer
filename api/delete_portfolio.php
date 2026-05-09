<?php
/**
 * API: Delete Portfolio
 */
require_once __DIR__ . '/../includes/app.php';
App::init();

Auth::requireRole('freelancer');
Security::verifyCsrf();

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$id   = (int)($body['portfolio_id'] ?? 0);
$userId = Auth::id();

DB::query("DELETE FROM portfolios WHERE id = ? AND user_id = ?", [$id, $userId]);

Security::jsonOk(['message' => 'Project removed.']);
