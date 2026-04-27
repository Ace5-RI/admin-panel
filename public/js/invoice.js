// ==================== INVOICE FUNCTIONS ====================

function formatDateInvoice(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
}

function openInvoicePopup(data) {
    const oldPopup = document.getElementById('popupInvoice');
    if (oldPopup) oldPopup.remove();
    
    const today = new Date();
    const endDate = new Date(data.akhir);
    const daysLeft = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
    
    let statusText = '';
    let statusClass = '';
    if (daysLeft <= 0) {
        statusText = 'EXPIRED';
        statusClass = 'status-expired';
    } else if (daysLeft <= 7) {
        statusText = `BERAKHIR DALAM ${daysLeft} HARI`;
        statusClass = 'status-warning';
    } else {
        statusText = 'AKTIF';
        statusClass = 'status-active';
    }
    
    const nomorInvoice = `INV/${new Date().getFullYear()}/${String(data.id).padStart(4, '0')}`;
    
    const popupHtml = `
    <div class="add open" id="popupInvoice" style="opacity:1; pointer-events:auto; z-index:10001;">
        <div class="invoice-container">
            <div class="invoice-header">
                <div class="invoice-logo">
                    <img src="/img/logos.png" alt="Logo">
                    <div class="invoice-title">
                        <h2>INVOICE</h2>
                        <p>${nomorInvoice}</p>
                    </div>
                </div>
                <button class="close-invoice">&times;</button>
            </div>
            
            <div class="invoice-body">
                <div class="invoice-to">
                    <div class="to-label">KEPADA YTH:</div>
                    <div class="to-name">${escapeHtml(data.nama)}</div>
                    <div class="to-detail">${escapeHtml(data.perusahaan || '-')}</div>
                    <div class="to-detail">📧 ${escapeHtml(data.email)}</div>
                    <div class="to-detail">📞 ${data.phone || '-'}</div>
                </div>
                
                <div class="invoice-info-grid">
                    <div class="info-card">
                        <div class="info-label">TANGGAL INVOICE</div>
                        <div class="info-value">${formatDateInvoice(today)}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">STATUS</div>
                        <div class="info-value ${statusClass}">${statusText}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">MASA BERAKHIR</div>
                        <div class="info-value">${formatDateInvoice(data.akhir)}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">SISA HARI</div>
                        <div class="info-value">${daysLeft > 0 ? daysLeft + ' hari' : 'Berakhir'}</div>
                    </div>
                </div>
                
                <div class="invoice-divider"></div>
                
                <table class="invoice-table-modern">
                    <thead><tr><th>DESKRIPSI</th><th class="text-right">TOTAL</th></tr></thead>
                    <tbody><tr><td>Langganan Tahunan</td><td class="text-right">${data.pendapatan}</td></tr></tbody>
                    <tfoot><tr><td class="text-right"><strong>TOTAL</strong></td><td class="text-right"><strong>${data.pendapatan}</strong></td></tr></tfoot>
                </table>
                
                <div class="invoice-payment-info">
                    <div class="payment-title">💳 INFORMASI PEMBAYARAN</div>
                    <div class="payment-bank">
                        <span class="bank-name">Bank BCA</span>
                        <span class="bank-account">123-456-7890</span>
                        <span class="bank-owner">a.n PT Admin Panel</span>
                    </div>
                    <div class="payment-bank">
                        <span class="bank-name">Bank Mandiri</span>
                        <span class="bank-account">987-654-3210</span>
                        <span class="bank-owner">a.n PT Admin Panel</span>
                    </div>
                    <div class="payment-note">*Konfirmasi pembayaran via WA ke nomor admin</div>
                </div>
                
                <div class="invoice-divider"></div>
                
                <div class="invoice-message">
                    <p>Terima kasih atas kepercayaan dan kerja samanya.</p>
                    <div class="signature">
                        <p>Hormat kami,</p>
                        <p><strong>Admin Panel</strong></p>
                    </div>
                </div>
                
                <div class="invoice-note">*Invoice ini digenerate secara otomatis oleh sistem.</div>
                
                <div class="invoice-actions">
                    <button class="btn-print" onclick="printInvoiceFromDashboard()">🖨️ CETAK</button>
                    <button class="btn-wa" id="sendWABtn">📱 KIRIM LINK WA</button>
                    <button class="btn-close close-invoice">TUTUP</button>
                </div>
            </div>
        </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', popupHtml);
    
    // Tombol WA Kirim Link
    const waBtn = document.getElementById('sendWABtn');
if (waBtn) {
    waBtn.addEventListener('click', function() {

        fetch(`/invoice/generate/${data.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert("Gagal generate invoice");
                return;
            }

            const pdfLink = res.url;

            const message = encodeURIComponent(
                `Yth. ${data.nama}\n\n` +
                `Berikut invoice langganan Anda:\n\n` +
                `📄 Link Invoice: ${pdfLink}\n` +
                `📅 Jatuh Tempo: ${formatDateInvoice(data.akhir)}\n` +
                `💰 Total: ${data.pendapatan}\n\n` +
                `Hormat kami,\nAdmin Panel`
            );

            const phoneNumber = data.phone || '';
            if (phoneNumber) {
                window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
            } else {
                alert("Nomor tidak ada");
            }
        });

    });
}
            
            const phoneNumber = data.phone || '';
            if (phoneNumber) {
                window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
            } else {
                Swal.fire({
                    title: 'Masukkan Nomor WhatsApp',
                    input: 'tel',
                    inputLabel: 'Nomor WhatsApp Klien',
                    inputPlaceholder: 'Contoh: 6281234567890',
                    showCancelButton: true,
                    confirmButtonText: 'Kirim',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        let phone = result.value.replace(/[^0-9]/g, '');
                        if (phone.startsWith('0')) phone = '62' + phone.substring(1);
                        window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
                    }
                });
            }
        });
    }
    
    document.querySelectorAll('.close-invoice').forEach(btn => {
        btn.addEventListener('click', () => document.getElementById('popupInvoice')?.remove());
    });
    
    document.getElementById('popupInvoice')?.addEventListener('click', function(e) {
        if (e.target === this) this.remove();
    });
}

// Fungsi print
window.printInvoiceFromDashboard = function() {
    const invoiceContainer = document.querySelector('#popupInvoice .invoice-container');
    if (!invoiceContainer) return;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Invoice</title>
                <link rel="stylesheet" href="/css/invoice.css">
            </head>
            <body style="margin:0; padding:20px;">
                ${invoiceContainer.outerHTML}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
};

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}