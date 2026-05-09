<?php
require_once __DIR__ . '/includes/app.php';
App::init();
try {
    $user = Auth::user();
    $activeJobs = DB::all("SELECT p.*, j.title as job_title, j.budget_min, j.budget_max, 
                               u.fullname as client_name, u.email as client_email, u.phone as client_phone
                        FROM proposals p 
                        JOIN jobs j ON p.job_id = j.id 
                        JOIN users u ON j.client_id = u.id
                        WHERE p.freelancer_id = ? AND p.status = 'accepted' AND j.status = 'in_progress'
                        ORDER BY p.updated_at DESC", [1]);
    print_r($activeJobs);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
