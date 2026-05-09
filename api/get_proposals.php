<?php
/** API: Get Proposals for a Job (Client) */
require_once __DIR__ . '/../includes/security.php';
require_once __DIR__ . '/../includes/auth.php';
Security::startSession(); Security::setHeaders();
Auth::requireLogin();

$jobId = Security::int($_GET['job_id'] ?? 0);
$user  = Auth::user();
if ($user['role'] !== 'client') Security::jsonError('Unauthorized.', 403);

$job = DB::row("SELECT id FROM jobs WHERE id=? AND client_id=?", [$jobId, $user['id']]);
if (!$job) Security::jsonError('Job not found.', 404);

$proposals = DB::all(
    "SELECT p.id, p.bid_amount, p.cover_letter, p.status, p.created_at,
            u.name AS freelancer_name, u.phone AS freelancer_phone
     FROM proposals p JOIN users u ON u.id=p.freelancer_id
     WHERE p.job_id=? ORDER BY p.created_at ASC",
    [$jobId]
);

$out = array_map(function($p) {
    return [
        'id'           => $p['id'],
        'freelancer'   => $p['freelancer_name'],
        'bid_amount'   => $p['bid_amount'],
        'cover_letter' => $p['cover_letter'],
        'status'       => $p['status'],
        'date'         => date('d M Y', strtotime($p['created_at'])),
        // Only reveal phone if accepted
        'phone'        => $p['status'] === 'accepted' ? $p['freelancer_phone'] : null,
    ];
}, $proposals);

Security::jsonOk(['data' => $out, 'count' => count($out)]);
