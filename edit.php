<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    switch ($description) {
        case 'PENTING DAN MENDESAK':
            $priority = 'TINGGI';
            break;
        case 'PENTING TIDAK MENDESAK':
            $priority = 'SEDANG';
            break;
        case 'TIDAK PENTING TIDAK MENDESAK':
            $priority = 'RENDAH';
            break;
        default:
            $priority = 'TIDAK DIKETAHUI';
    }

    $stmt = $conn->prepare("UPDATE todos SET task=?, date=?, status=?, description=?, priority=? WHERE id=?");
    $stmt->bind_param("sssssi", $task, $date, $status, $description, $priority, $id);
    $stmt->execute();

    header("Location: todo.php");
    exit;
}
?>
