<?php
require_once __DIR__ . '/../database.php';

class ReminderModel {
    private $db;

    public function __construct() {
        $this->db = db_connect();
    }

    public function get_all_reminders(int $user_id): array {
        $stmt = $this->db->prepare(
            "SELECT id, subject, completed, created_at
             FROM reminders
             WHERE user_id = :u AND deleted = 0
             ORDER BY created_at DESC"
        );
        $stmt->execute(['u' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find_reminder(int $id) {
        $stmt = $this->db->prepare(
            "SELECT id, user_id, subject, completed, created_at
             FROM reminders
             WHERE id = :id AND deleted = 0"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create_reminder(int $user_id, string $subject): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO reminders (user_id, subject, completed, created_at)
             VALUES (:user_id, :subject, 0, NOW())"
        );
        return $stmt->execute([
            'user_id' => $user_id,
            'subject' => $subject
        ]);
    }

    public function mark_reminder_complete(int $id): bool {
        $stmt = $this->db->prepare(
            "UPDATE reminders SET completed = 1 WHERE id = :id AND deleted = 0"
        );
        return $stmt->execute(['id' => $id]);
    }
    public function delete_reminder(int $id): bool {
        $stmt = $this->db->prepare(
            "UPDATE reminders SET deleted = 1 WHERE id = :id"
        );
        return $stmt->execute(['id' => $id]);
    }

}
