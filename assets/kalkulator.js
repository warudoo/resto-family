document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-pemesanan');
    if (!form) return;

    const inputJumlah = form.querySelectorAll('input[name^="jumlah["]');
    const inputUangInput = document.getElementById('uang_input');

    const updateBill = () => {
        let totalPesanan = 0;
        let detailPesanan = [];

        inputJumlah.forEach(input => {
            const jumlah = parseInt(input.value) || 0;
            const harga = parseFloat(input.dataset.harga) || 0;
            const nama = input.dataset.nama || '';
            const kategori = input.dataset.kategori || '';

            if (jumlah > 0) {
                const subTotalItem = jumlah * harga;
                totalPesanan += subTotalItem;
                
                detailPesanan.push({
                    nama: nama,
                    jumlah: jumlah,
                    subTotal: subTotalItem
                });
            }
        });
        
        // 1. Hitung PPN (10% X Total Pesanan)
        const ppn = totalPesanan * 0.10;

        // 2. Hitung TOTAL BAYAR
        const totalBayar = totalPesanan + ppn;

        // 3. Hitung Uang Kembali
        const uangInput = parseFloat(inputUangInput.value) || 0;
        const uangKembali = uangInput - totalBayar;


        // --- TAMPILKAN HASIL DI FORM ---
        document.getElementById('total_pesanan_display').value = totalPesanan.toLocaleString('id-ID');
        document.getElementById('ppn_display').value = ppn.toLocaleString('id-ID');
        document.getElementById('total_bayar_display').value = totalBayar.toLocaleString('id-ID');
        document.getElementById('kembalian_display').value = uangKembali.toLocaleString('id-ID');


        // --- UPDATE DETAIL TABEL (Visualisasi) ---
        const tbodyPesanan = document.getElementById('tbody-pesanan');
        tbodyPesanan.innerHTML = '';
        detailPesanan.forEach(item => {
            const row = tbodyPesanan.insertRow();
            row.insertCell().textContent = item.nama;
            row.insertCell().textContent = item.jumlah;
            row.insertCell().textContent = 'Rp. ' + item.subTotal.toLocaleString('id-ID');
        });
        
        // Update hidden field untuk totalBayar (walau perhitungan final di PHP, ini untuk UX)
        document.getElementById('total_bayar_hidden').value = totalBayar;
    };

    // Listener untuk semua input jumlah dan uang input
    inputJumlah.forEach(input => input.addEventListener('input', updateBill));
    inputUangInput.addEventListener('input', updateBill);

    // Inisialisasi awal
    updateBill();
});