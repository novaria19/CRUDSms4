<?php
// === INDEX.PHP (MAIN FILE) ===
// File: index.php
require_once 'config.php';

$bukuManager = new BukuManager();
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
            $data = [
                'judul' => $_POST['judul'],
                'pengarang' => $_POST['pengarang'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'isbn' => $_POST['isbn'],
                'jumlah_halaman' => $_POST['jumlah_halaman'],
                'kategori' => $_POST['kategori'],
                'status' => $_POST['status']
            ];
            
            if ($bukuManager->create($data)) {
                $message = "Buku berhasil ditambahkan!";
                $messageType = "success";
            } else {
                $message = "Gagal menambahkan buku!";
                $messageType = "error";
            }
            break;
            
        case 'update':
            $id = $_POST['id'];
            $data = [
                'judul' => $_POST['judul'],
                'pengarang' => $_POST['pengarang'],
                'penerbit' => $_POST['penerbit'],
                'tahun_terbit' => $_POST['tahun_terbit'],
                'isbn' => $_POST['isbn'],
                'jumlah_halaman' => $_POST['jumlah_halaman'],
                'kategori' => $_POST['kategori'],
                'status' => $_POST['status']
            ];
            
            if ($bukuManager->update($id, $data)) {
                $message = "Buku berhasil diperbarui!";
                $messageType = "success";
            } else {
                $message = "Gagal memperbarui buku!";
                $messageType = "error";
            }
            break;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        if ($bukuManager->delete($id)) {
            $message = "Buku berhasil dihapus!";
            $messageType = "success";
        } else {
            $message = "Gagal menghapus buku!";
            $messageType = "error";
        }
    }
}

// Get data for display
$search = $_GET['search'] ?? '';
if ($search) {
    $books = $bukuManager->search($search);
} else {
    $books = $bukuManager->readAll();
}

// Get book for editing
$editBook = null;
if (isset($_GET['edit'])) {
    $editBook = $bukuManager->readById($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Buku Perpustakaan</title>
    
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Sistem Manajemen Buku</h1>
            <p>Perpustakaan Digital - Kelola koleksi buku dengan mudah</p>
        </div>

        <?php if ($message): ?>
            <div class="message <?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="main-content">
            <!-- FORM SECTION -->
            <div class="form-section">
                <h2><?= $editBook ? '✏️ Edit Buku' : '➕ Tambah Buku Baru' ?></h2>
                
                <form method="POST" action="">
                    <input type="hidden" name="action" value="<?= $editBook ? 'update' : 'create' ?>">
                    <?php if ($editBook): ?>
                        <input type="hidden" name="id" value="<?= $editBook['id'] ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="judul">Judul Buku *</label>
                        <input type="text" id="judul" name="judul" required 
                               value="<?= $editBook ? htmlspecialchars($editBook['judul']) : '' ?>"
                               placeholder="Masukkan judul buku">
                    </div>

                    <div class="form-group">
                        <label for="pengarang">Pengarang *</label>
                        <input type="text" id="pengarang" name="pengarang" required 
                               value="<?= $editBook ? htmlspecialchars($editBook['pengarang']) : '' ?>"
                               placeholder="Nama pengarang">
                    </div>

                    <div class="form-group">
                        <label for="penerbit">Penerbit</label>
                        <input type="text" id="penerbit" name="penerbit" 
                               value="<?= $editBook ? htmlspecialchars($editBook['penerbit']) : '' ?>"
                               placeholder="Nama penerbit">
                    </div>

                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" 
                               min="1800" max="<?= date('Y') ?>"
                               value="<?= $editBook ? $editBook['tahun_terbit'] : '' ?>"
                               placeholder="<?= date('Y') ?>">
                    </div>

                    <div class="form-group">
                        <label for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" 
                               value="<?= $editBook ? htmlspecialchars($editBook['isbn']) : '' ?>"
                               placeholder="978-xxx-xxx-xxx-x">
                    </div>

                    <div class="form-group">
                        <label for="jumlah_halaman">Jumlah Halaman</label>
                        <input type="number" id="jumlah_halaman" name="jumlah_halaman" 
                               min="1"
                               value="<?= $editBook ? $editBook['jumlah_halaman'] : '' ?>"
                               placeholder="Contoh: 250">
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori">
                            <option value="Fiksi" <?= ($editBook && $editBook['kategori'] == 'Fiksi') ? 'selected' : '' ?>>Fiksi</option>
                            <option value="Non-Fiksi" <?= ($editBook && $editBook['kategori'] == 'Non-Fiksi') ? 'selected' : '' ?>>Non-Fiksi</option>
                            <option value="Sains" <?= ($editBook && $editBook['kategori'] == 'Sains') ? 'selected' : '' ?>>Sains</option>
                            <option value="Teknologi" <?= ($editBook && $editBook['kategori'] == 'Teknologi') ? 'selected' : '' ?>>Teknologi</option>
                            <option value="Sejarah" <?= ($editBook && $editBook['kategori'] == 'Sejarah') ? 'selected' : '' ?>>Sejarah</option>
                            <option value="Biografi" <?= ($editBook && $editBook['kategori'] == 'Biografi') ? 'selected' : '' ?>>Biografi</option>
                            <option value="Pendidikan" <?= ($editBook && $editBook['kategori'] == 'Pendidikan') ? 'selected' : '' ?>>Pendidikan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Tersedia" <?= ($editBook && $editBook['status'] == 'Tersedia') ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Dipinjam" <?= ($editBook && $editBook['status'] == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam</option>
                            <option value="Rusak" <?= ($editBook && $editBook['status'] == 'Rusak') ? 'selected' : '' ?>>Rusak</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <?= $editBook ? '💾 Update Buku' : '➕ Tambah Buku' ?>
                    </button>
                    
                    <?php if ($editBook): ?>
                        <a href="index.php" class="btn btn-secondary">❌ Batal</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- DATA SECTION -->
            <div class="data-section">
                <div class="search-bar">
                    <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" name="search" placeholder="🔍 Cari judul, pengarang, atau penerbit..." 
                               value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <?php if ($search): ?>
                            <a href="index.php" class="btn btn-secondary">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($books)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 40px; color: #6c757d;">
                                        <?= $search ? "Tidak ada buku yang ditemukan untuk pencarian '{$search}'" : "Belum ada data buku" ?>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($books as $book): ?>
                                    <tr>
                                        <td><?= $book['id'] ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($book['judul']) ?></strong>
                                            <?php if ($book['isbn']): ?>
                                                <br><small style="color: #6c757d;">ISBN: <?= htmlspecialchars($book['isbn']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($book['pengarang']) ?></td>
                                        <td><?= htmlspecialchars($book['penerbit']) ?></td>
                                        <td><?= $book['tahun_terbit'] ?></td>
                                        <td>
                                            <span class="kategori"><?= $book['kategori'] ?></span>
                                        </td>
                                        <td>
                                            <span class="status <?= strtolower($book['status']) ?>">
                                                <?= $book['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="actions">
                                                <a href="?edit=<?= $book['id'] ?>" class="btn btn-warning">✏️</a>
                                                <a href="?delete=<?= $book['id'] ?>" class="btn btn-danger"
                                                   onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- STATISTICS -->
        <div style="padding: 0 30px 30px;">
            <div class="stats">
                <?php
                $totalBooks = count($bukuManager->readAll());
                $tersedia = count(array_filter($bukuManager->readAll(), fn($b) => $b['status'] == 'Tersedia'));
                $dipinjam = count(array_filter($bukuManager->readAll(), fn($b) => $b['status'] == 'Dipinjam'));
                $rusak = count(array_filter($bukuManager->readAll(), fn($b) => $b['status'] == 'Rusak'));
                ?>
                <div class="stat-card">
                    <div class="stat-number"><?= $totalBooks ?></div>
                    <div class="stat-label">Total Buku</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #27ae60;"><?= $tersedia ?></div>
                    <div class="stat-label">Tersedia</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #f39c12;"><?= $dipinjam ?></div>
                    <div class="stat-label">Dipinjam</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" style="color: #e74c3c;"><?= $rusak ?></div>
                    <div class="stat-label">Rusak</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide messages after 5 seconds
        const message = document.querySelector('.message');
        if (message) {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            }, 5000);
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const judul = document.getElementById('judul').value.trim();
            const pengarang = document.getElementById('pengarang').value.trim();
            
            if (!judul || !pengarang) {
                alert('Judul dan Pengarang harus diisi!');
                e.preventDefault();
                return false;
            }
        });

        // Confirm delete
        document.querySelectorAll('a[href*="delete"]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Yakin ingin menghapus buku ini? Tindakan ini tidak dapat dibatalkan.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>