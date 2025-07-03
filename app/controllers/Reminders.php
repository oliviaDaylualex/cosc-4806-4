<?php
class Reminders extends Controller {
    public function index(): void {
        if (empty($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $reminderM = $this->model('Reminder');
        $data = ['list' => $reminderM->allForUser((int)$_SESSION['user_id'])];
        $this->view('reminders/index', $data);
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = trim($_POST['subject'] ?? '');
            if ($subject !== '') {
                $this->model('Reminder')->create((int)$_SESSION['user_id'], $subject);
            }
            $this->redirect('/reminders');
        }
        $this->view('reminders/create');
    }

    public function complete(int $id): void {
        $this->model('Reminder')->markCompleted($id);
        $this->redirect('/reminders');
    }

    public function delete(int $id): void {
        $this->model('Reminder')->delete($id);
        $this->redirect('/reminders');
    }
}
