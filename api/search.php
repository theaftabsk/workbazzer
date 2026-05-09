<?php
/**
 * WorkBazar Search API
 * GET /api/search.php?q=...&category=...&min_rate=...&max_rate=...&rating=...&page=...
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../includes/app.php';
App::init();

$q        = trim($_GET['q']        ?? '');
$category = trim($_GET['category'] ?? '');
$min_rate = (int)($_GET['min_rate'] ?? 0);
$max_rate = (int)($_GET['max_rate'] ?? 9999);
$rating   = (float)($_GET['rating'] ?? 0);
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = 12;
$offset   = ($page - 1) * $per_page;

try {
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
        DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $where  = ["u.role = 'freelancer'"];
    $params = [];

    if ($q !== '') {
        $where[]  = "(u.fullname LIKE ? OR u.bio LIKE ? OR fs.skill_name LIKE ?)";
        $like     = "%$q%";
        $params[] = $like; $params[] = $like; $params[] = $like;
    }
    if ($category !== '') {
        $where[]  = "fs.category = ?";
        $params[] = $category;
    }
    if ($min_rate > 0) { $where[] = "u.hourly_rate >= ?"; $params[] = $min_rate; }
    if ($max_rate < 9999) { $where[] = "u.hourly_rate <= ?"; $params[] = $max_rate; }
    if ($rating > 0) { $where[] = "u.rating >= ?"; $params[] = $rating; }

    $whereSql = implode(' AND ', $where);

    // Count total
    $countSql = "SELECT COUNT(DISTINCT u.id) FROM users u 
                 LEFT JOIN freelancer_skills fs ON fs.user_id = u.id
                 WHERE $whereSql";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // Fetch results
    $sql = "SELECT DISTINCT u.id, u.fullname, u.avatar, u.bio, u.hourly_rate,
                   u.country, u.rating, u.reviews, u.success_rate, 
                   u.verified, u.available, u.response_time,
                   GROUP_CONCAT(DISTINCT fs.skill_name ORDER BY fs.skill_name SEPARATOR ',') AS skills
            FROM users u
            LEFT JOIN freelancer_skills fs ON fs.user_id = u.id
            WHERE $whereSql
            GROUP BY u.id
            ORDER BY u.rating DESC, u.reviews DESC
            LIMIT ? OFFSET ?";

    $params[] = $per_page;
    $params[] = $offset;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format
    foreach ($results as &$r) {
        $r['skills']       = $r['skills'] ? explode(',', $r['skills']) : [];
        $r['rating']       = (float)$r['rating'];
        $r['hourly_rate']  = (int)$r['hourly_rate'];
        $r['verified']     = (bool)$r['verified'];
        $r['available']    = (bool)$r['available'];
    }

    echo json_encode([
        'success' => true,
        'query'   => $q,
        'total'   => $total,
        'page'    => $page,
        'pages'   => ceil($total / $per_page),
        'results' => $results
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success'=>false, 'error'=>'Search unavailable. Please try again.']);
}
