<?php
require_once __DIR__ . '/../database.php';

class Reminder {
    private $db;

    public function __construct() {
        $this->db = db_connect();
    }

    /** Fetch all non-deleted reminders for a given user */
    public function allForUser(int $userId): array {
        $stmt = $this->db->prepare(
            "SELECT id, subject, created_at, completed
               FROM reminders
              WHERE user_id = :uid
                AND deleted = 0
              ORDER BY created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Create a new reminder */
    public function create(int $userId, string $subject): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO reminders (user_id, subject)
             VALUES (:uid, :sub)"
        );
        return $stmt->execute([
            'uid' => $userId,
            'sub' => $subject
        ]);
    }

    /** Mark one as completed */
    public function markCompleted(int $id): bool {
        $stmt = $this->db->prepare(
            "UPDATE reminders
                SET completed = 1
              WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

    /** Soft-delete a reminder */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare(
            "UPDATE reminders
                SET deleted = 1
              WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }
}
