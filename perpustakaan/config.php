<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "perpustakaan";
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->database};charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch(PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

class BukuManager {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // CREATE - Tambah buku baru
    public function create($data) {
        $sql = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, isbn, jumlah_halaman, kategori, status) 
                VALUES (:judul, :pengarang, :penerbit, :tahun_terbit, :isbn, :jumlah_halaman, :kategori, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':judul' => $data['judul'],
            ':pengarang' => $data['pengarang'],
            ':penerbit' => $data['penerbit'],
            ':tahun_terbit' => $data['tahun_terbit'],
            ':isbn' => $data['isbn'],
            ':jumlah_halaman' => $data['jumlah_halaman'],
            ':kategori' => $data['kategori'],
            ':status' => $data['status']
        ]);
    }

    // READ - Ambil semua buku
    public function readAll() {
        $sql = "SELECT * FROM buku ORDER BY tanggal_masuk DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ - Ambil satu buku berdasarkan ID
    public function readById($id) {
        $sql = "SELECT * FROM buku WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // UPDATE - Edit buku
    public function update($id, $data) {
        $sql = "UPDATE buku SET 
                judul = :judul, 
                pengarang = :pengarang, 
                penerbit = :penerbit, 
                tahun_terbit = :tahun_terbit, 
                isbn = :isbn, 
                jumlah_halaman = :jumlah_halaman, 
                kategori = :kategori, 
                status = :status 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':judul' => $data['judul'],
            ':pengarang' => $data['pengarang'],
            ':penerbit' => $data['penerbit'],
            ':tahun_terbit' => $data['tahun_terbit'],
            ':isbn' => $data['isbn'],
            ':jumlah_halaman' => $data['jumlah_halaman'],
            ':kategori' => $data['kategori'],
            ':status' => $data['status']
        ]);
    }

    // DELETE - Hapus buku
    public function delete($id) {
        $sql = "DELETE FROM buku WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // SEARCH - Cari buku
    public function search($keyword) {
        $sql = "SELECT * FROM buku WHERE 
                judul LIKE :keyword OR 
                pengarang LIKE :keyword OR 
                penerbit LIKE :keyword 
                ORDER BY tanggal_masuk DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => "%{$keyword}%"]);
        return $stmt->fetchAll();
    }
}
?>

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
