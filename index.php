<?php
include 'koneksi.php'; // Menghubungkan file koneksi.php

// Mengambil kategori dari database
$kategori_stmt = $pdo->query("SELECT * FROM kategori");
$kategori = $kategori_stmt->fetchAll();

// Mengambil kategori yang dipilih dari URL
$id_kategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;

// Mengambil artikel berdasarkan kategori
if ($id_kategori) {
    $stmt = $pdo->prepare("
        SELECT a.* 
        FROM artikel a
        JOIN artikel_kategori ak ON a.id = ak.artikel_id
        WHERE ak.kategori_id = ?
    ");
    $stmt->execute([$id_kategori]);
} else {
    $stmt = $pdo->query("SELECT * FROM artikel");
}


// Mengambil semua hasil query dan menyimpannya dalam variabel $artikel
$artikel = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Artikel</title>
    <link rel="stylesheet" href="aka.css">
    <style>
        .artikel-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .artikel-item {
            border: 1px solid #ddd;
            padding: 15px;
            width: 300px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .artikel-item img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .artikel-item h2 {
            font-size: 1.5em;
            margin: 0 0 10px;
        }
        .artikel-item p {
            margin: 5px 0;
            color: #666;
        }
        .artikel-item a {
            color: #007BFF;
            text-decoration: none;
            margin-top: 10px;
            align-self: flex-start;
        }
        .artikel-item a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header class="site-header">
    <h1>Za Monolith</h1>
</header>

<nav>
    <div class="category-list">
        <ul class="category-navbar">
            <li><a href="index.php">Home</a> </li>
            <?php if (!empty($kategori)): ?>
                <?php foreach ($kategori as $cat): ?>
                    <li><a href="index.php?kategori=<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['nama']); ?></a></li> 
                <?php endforeach; ?>
            <?php else: ?>
                <li><em>Tidak ada kategori tersedia</em></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="artikel-container">
    <!-- Daftar artikel -->
    <?php if (!empty($artikel)): ?>
        <?php foreach ($artikel as $item): ?>
            <div class="artikel-item">
                <!-- Menampilkan gambar artikel jika ada -->
                <?php if (!empty($item['gambar'])): ?>
                    <img src="<?php echo htmlspecialchars($item['gambar']); ?>" alt="Gambar Artikel">
                <?php endif; ?>

                <!-- Menampilkan judul artikel -->
                <h2><a href="detail.php?id=<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['judul']); ?></a></h2>
                
                <!-- Menampilkan penulis artikel jika ada -->
                <?php if (!empty($item['penulis'])): ?>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($item['penulis']); ?></p>
                <?php endif; ?>

                <!-- Menampilkan tanggal artikel jika ada -->
                <?php if (!empty($item['tanggal'])): ?>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($item['tanggal']); ?></p>
                <?php endif; ?>

                <!-- Menampilkan deskripsi singkat dengan tag HTML dihapus -->
                <?php if (!empty($item['konten'])): ?>
                    <p><?php echo htmlspecialchars(substr(strip_tags($item['konten']), 0, 100)); ?>...</p>
                <?php endif; ?>

                <!-- Link ke detail artikel -->
                <a href="detail.php?id=<?php echo $item['id']; ?>">Read More</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p><em>Tidak ada artikel ditemukan</em></p>
    <?php endif; ?>
</div>

</body>
</html>
