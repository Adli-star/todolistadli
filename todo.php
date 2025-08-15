<?php
include 'db.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM todos WHERE id=$id");
    header("Location: todo.php");
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = $search ? "WHERE task LIKE '%$search%'" : '';
$todos = $conn->query("SELECT * FROM todos $where ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Todo List UKK 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="p-3 text-center text-white" style="background: linear-gradient(to right, #e96443, #904e95);">
            <h3>APLIKASI TO DO LIST</h3>
        </div>
        <h5 class="mt-3">Selamat Datang DI APLIKASI TO DO LIST</h5>
        <form method="GET" class="d-flex mb-3 mt-3">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari Tugas" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">+ Tambah Tugas</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA TUGAS</th>
                    <th>TANGGAL</th>
                    <th>STATUS</th>
                    <th>DESKRIPSI</th>
                    <th>PRIORITAS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while ($row = $todos->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['task']) ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['description'] ?></td>
                    <td><?= $row['priority'] ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus tugas ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (isset($_GET['edit'])):
                    $edit_id = $_GET['edit'];
                    $edit_data = $conn->query("SELECT * FROM todos WHERE id=$edit_id")->fetch_assoc();
                ?>
                <script>
                window.onload = () => {
                    const modal = new bootstrap.Modal(document.getElementById('editModal'));
                    modal.show();
                }
                </script>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="tambah.php" class="modal-content" onsubmit="return validateDate(this)">
                <div class="modal-header"><h5 class="modal-title">TAMBAHKAN TUGAS</h5></div>
                <div class="modal-body">
                    <input type="text" name="task" class="form-control mb-2" placeholder="masukan nama tugas anda" required>
                    <input type="date" name="date" class="form-control mb-2" min="<?= date('Y-m-d'); ?>" required>
                    <select name="status" class="form-control mb-2" required>
                        <option value="">-Pilih-</option>
                        <option value="SELESAI">SELESAI</option>
                        <option value="BELUM SELESAI">BELUM SELESAI</option>
                    </select>
                    <select name="description" class="form-control" required>
                        <option value="">-Pilih Deskripsi-</option>
                        <option value="PENTING DAN MENDESAK">PENTING DAN MENDESAK</option>
                        <option value="PENTING TIDAK MENDESAK">PENTING TIDAK MENDESAK</option>
                        <option value="TIDAK PENTING TIDAK MENDESAK">TIDAK PENTING TIDAK MENDESAK</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Tambah Tugas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <?php if (isset($edit_data)): ?>
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="edit.php" class="modal-content" onsubmit="return validateDate(this)">
                <div class="modal-header"><h5 class="modal-title">EDIT TUGAS</h5></div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                    <input type="text" name="task" class="form-control mb-2" value="<?= $edit_data['task'] ?>" required>
                    <input type="date" name="date" class="form-control mb-2" 
                           min="<?= date('Y-m-d'); ?>" value="<?= $edit_data['date'] ?>" required>
                    <select name="status" class="form-control mb-2" required>
                        <option value="">-Pilih-</option>
                        <option value="SELESAI" <?= $edit_data['status'] === 'SELESAI' ? 'selected' : '' ?>>SELESAI</option>
                        <option value="BELUM SELESAI" <?= $edit_data['status'] === 'BELUM SELESAI' ? 'selected' : '' ?>>BELUM SELESAI</option>
                    </select>
                    <select name="description" class="form-control" required>
                        <option value="">-Pilih Deskripsi-</option>
                        <option value="PENTING DAN MENDESAK" <?= $edit_data['description'] === 'PENTING DAN MENDESAK' ? 'selected' : '' ?>>PENTING DAN MENDESAK</option>
                        <option value="PENTING TIDAK MENDESAK" <?= $edit_data['description'] === 'PENTING TIDAK MENDESAK' ? 'selected' : '' ?>>PENTING TIDAK MENDESAK</option>
                        <option value="TIDAK PENTING TIDAK MENDESAK" <?= $edit_data['description'] === 'TIDAK PENTING TIDAK MENDESAK' ? 'selected' : '' ?>>TIDAK PENTING TIDAK MENDESAK</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script>
    function validateDate(form) {
        let selectedDate = new Date(form.date.value);
        let today = new Date();
        today.setHours(0,0,0,0);

        if (selectedDate < today) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal yang sudah lewat tidak bisa dipilih!'
            });
            return false;
        }
        return true;
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
