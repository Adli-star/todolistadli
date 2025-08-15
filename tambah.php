<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST['task'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $desc = $_POST['description'];

    // Tentukan prioritas berdasarkan deskripsi
    switch ($desc) {
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

    $stmt = $conn->prepare("INSERT INTO todos (task, date, status, description, priority) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $task, $date, $status, $desc, $priority);
    $stmt->execute();
}
header("Location: todo.php");
exit;
?>
