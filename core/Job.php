<?php
/**
 * WorkBazar — Job Management Logic
 */

class Job {
    /**
     * Post a new job lead
     */
    public static function create(array $data): int {
        $clientId   = Auth::id();
        $title      = Security::clean($data['title']);
        $desc       = Security::clean($data['description']);
        $category   = Security::clean($data['category']);
        $budgetType = $data['budget_type'] ?? 'fixed';
        $budgetMin  = (float)($data['budget_min'] ?? 0);
        $budgetMax  = (float)($data['budget_max'] ?? 0);

        $sql = "INSERT INTO jobs (client_id, title, description, category, budget_type, budget_min, budget_max, status, is_approved, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'open', 0, NOW())";
        
        DB::query($sql, [
            $clientId, $title, $desc, $category, $budgetType, $budgetMin, $budgetMax
        ]);

        $jobId = (int) DB::lastId();

        // Ensure profile exists and increment job count
        DB::query("INSERT IGNORE INTO client_profiles (user_id) VALUES (?)", [$clientId]);
        DB::query("UPDATE client_profiles SET total_jobs = total_jobs + 1 WHERE user_id = ?", [$clientId]);

        // Log Activity
        Logger::info("New job posted by client ID {$clientId}: {$title}");

        return $jobId;
    }

    /**
     * Get jobs for the public marketplace
     */
    public static function getMarketplace(array $filters = []): array {
        try {
            $sql = "SELECT j.*, u.fullname as client_name, u.avatar as client_avatar,
                           (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as proposal_count
                    FROM jobs j 
                    JOIN users u ON j.client_id = u.id 
                    WHERE j.status = 'open'";
            
            // Safety check: only add is_approved if it exists (handled by repair script usually)
            $sql .= " AND j.is_approved = 1";

            $params = [];
            if (!empty($filters['category'])) {
                $sql .= " AND j.category = ?";
                $params[] = $filters['category'];
            }

            if (!empty($filters['q'])) {
                $sql .= " AND (j.title LIKE ? OR j.description LIKE ?)";
                $params[] = '%' . $filters['q'] . '%';
                $params[] = '%' . $filters['q'] . '%';
            }

            if (!empty($filters['work_type'])) {
                $types = (array)$filters['work_type'];
                $placeholders = implode(',', array_fill(0, count($types), '?'));
                $sql .= " AND j.work_type IN ($placeholders)";
                foreach($types as $t) $params[] = $t;
            }

            if (!empty($filters['min_budget'])) {
                $sql .= " AND j.budget_min >= ?";
                $params[] = (float)$filters['min_budget'];
            }

            if (!empty($filters['max_budget'])) {
                $sql .= " AND j.budget_max <= ?";
                $params[] = (float)$filters['max_budget'];
            }

            $sql .= " ORDER BY j.created_at DESC LIMIT 50";
            return DB::all($sql, $params);
        } catch (Exception $e) {
            Logger::error("Marketplace Fetch Failed: " . $e->getMessage());
            return []; // Return empty instead of 500 error
        }
    }
}
