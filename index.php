<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Kartu Pelajar</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { color: #007bff; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .filter-section { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        #loading { text-align: center; color: #6c757d; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Absensi Siswa Harian 📅</h1>
        
        <div class="filter-section">
            <label for="tanggal_filter">Pilih Tanggal:</label>
            <input type="date" id="tanggal_filter" value="<?php echo date('Y-m-d'); ?>">
            <button onclick="fetchAbsensi()">Lihat Laporan</button>
        </div>

        <div id="loading">Memuat data...</div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Waktu Masuk</th>
                    <th>Keterangan</th>
                    <th>Waktu Pulang</th>
                </tr>
            </thead>
            <tbody id="data_laporan">
                </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set tanggal default ke hari ini
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal_filter').value = today;
            
            // Panggil fungsi saat halaman dimuat
            fetchAbsensi(); 
        });

        async function fetchAbsensi() {
            const tanggal = document.getElementById('tanggal_filter').value;
            const tableBody = document.getElementById('data_laporan');
            const loading = document.getElementById('loading');
            
            tableBody.innerHTML = ''; // Kosongkan tabel
            loading.style.display = 'block'; // Tampilkan status memuat

            try {
                // Panggil file PHP untuk mengambil data
                const response = await fetch(`get_laporan.php?tanggal=${tanggal}`);
                const result = await response.json();

                loading.style.display = 'none'; // Sembunyikan status memuat

                if (result.data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="7" style="text-align:center;">Tidak ada data absensi untuk tanggal ini.</td></tr>`;
                    return;
                }

                let html = '';
                result.data.forEach((siswa, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${siswa.nis}</td>
                            <td>${siswa.nama}</td>
                            <td>${siswa.kelas}</td>
                            <td>${siswa.waktu_masuk || '-'}</td>
                            <td>${siswa.ket_masuk || '-'}</td>
                            <td>${siswa.waktu_pulang || '-'}</td>
                        </tr>
                    `;
                });
                
                tableBody.innerHTML = html;

            } catch (error) {
                loading.style.display = 'none';
                tableBody.innerHTML = `<tr><td colspan="7" style="color: red; text-align:center;">Gagal memuat data. Cek koneksi server Anda.</td></tr>`;
                console.error('Error fetching data:', error);
            }
        }
    </script>
</body>
</html>
