// ==================== INVOICE FUNCTIONS ====================

// DEFAULT SETTINGS (kalo dari database kosong)
const DEFAULT_SETTINGS = {
    companyName: 'Bali Solution Biz',
    companyAddress: 'Jl. Raya Kuta No. 123, Bali',
    companyPhone: '0361-123456',
    companyEmail: 'info@balisolutionbiz.com',
    companyLogo: '/img/logos.png',
    bankBCA: '1234567890',
    bankMandiri: '0987654321',
    bankAccountName: 'PT Bali Solution Biz',
    invoiceFooter: 'Terima kasih atas kepercayaan dan kerja samanya.'
};

// Cache settings
let cachedSettings = null;

function formatDateInvoice(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Load settings dengan cache
async function loadSettings() {
    if (cachedSettings) return cachedSettings;
    
    try {
        const response = await fetch('/api/settings');
        const data = await response.json();
        
        cachedSettings = {
            companyName: data.company_name || DEFAULT_SETTINGS.companyName,
            companyAddress: data.company_address || DEFAULT_SETTINGS.companyAddress,
            companyPhone: data.company_phone || DEFAULT_SETTINGS.companyPhone,
            companyEmail: data.company_email || DEFAULT_SETTINGS.companyEmail,
            companyLogo: data.company_logo || DEFAULT_SETTINGS.companyLogo,
            bankBCA: data.bank_bca || DEFAULT_SETTINGS.bankBCA,
            bankMandiri: data.bank_mandiri || DEFAULT_SETTINGS.bankMandiri,
            bankAccountName: data.bank_account_name || DEFAULT_SETTINGS.bankAccountName,
            invoiceFooter: data.invoice_footer || DEFAULT_SETTINGS.invoiceFooter
        };
        
        return cachedSettings;
    } catch (error) {
        console.error('Gagal load settings, pake default:', error);
        cachedSettings = DEFAULT_SETTINGS;
        return cachedSettings;
    }
}

// Copy ke clipboard
window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text);
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '✅ Tersalin!';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 1000);
};

// Fungsi buat generate link invoice
async function generateInvoiceLink(clientId) {
    try {
        const response = await fetch(`/invoice/generate/${clientId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        const result = await response.json();
        console.log("Response server:", result);
        
        if (result.success) {
            return result.url;
        } else {
            console.error('Gagal generate:', result.message);
            return null;
        }
    } catch (error) {
        console.error('Error generate invoice:', error);
        return null;
    }
}

// Fungsi utama buka popup
async function openInvoicePopup(data) {
    // Hapus popup lama
    const oldPopup = document.getElementById('popupInvoice');
    if (oldPopup) oldPopup.remove();
    
    // Load settings
    const settings = await loadSettings();
    
    // Hitung hari
    const today = new Date();
    const endDate = new Date(data.akhir);
    const daysLeft = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
    
    let statusText = '', statusClass = '';
    if (daysLeft <= 0) {
        statusText = 'EXPIRED';
        statusClass = 'status-expired';
    } else if (daysLeft <= 7) {
        statusText = `${daysLeft} HARI LAGI`;
        statusClass = 'status-warning';
    } else {
        statusText = 'AKTIF';
        statusClass = 'status-active';
    }
    
    const nomorInvoice = `INV/${new Date().getFullYear()}/${String(data.id).padStart(4, '0')}`;
    
    // Ambil dari settings
    const bankBCA = settings.bankBCA;
    const bankMandiri = settings.bankMandiri;
    const bankAccountName = settings.bankAccountName;
    const companyName = settings.companyName;
    const companyAddress = settings.companyAddress;
    const companyPhone = settings.companyPhone;
    const companyEmail = settings.companyEmail;
    const companyLogo = settings.companyLogo;
    const invoiceFooter = settings.invoiceFooter;
    
    // HTML Popup
    const popupHtml = `
    <style>
        .add.open {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10001;
        }
        .invoice-container {
            background: white;
            width: 90%;
            max-width: 600px;
            max-height: 85vh;
            overflow-y: auto;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .invoice-container::-webkit-scrollbar {
            display: none;
        }
        .invoice-header {
            padding: 20px 24px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background: white;
            border-radius: 16px 16px 0 0;
        }
        .header-left {
            flex: 1;
        }
        .company-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }
        .company-header img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }
        .company-header-text {
            line-height: 1.3;
        }
        .company-header-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .company-header-detail {
            font-size: 11px;
            color: #888;
            margin-top: 4px;
        }
        .company-header-detail div {
            margin-top: 2px;
        }
        .header-right {
            text-align: right;
        }
        .header-right h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header-right p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #999;
        }
        .close-invoice {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #aaa;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 16px;
        }
        .close-invoice:hover {
            background: #f5f5f5;
            color: #333;
        }
        .invoice-body {
            padding: 20px 24px;
        }
        .invoice-to {
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #f8f9fa;
            border-radius: 12px;
        }
        .to-label {
            font-size: 11px;
            font-weight: bold;
            color: #999;
            margin-bottom: 6px;
        }
        .to-name {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }
        .to-detail {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }
        .invoice-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .info-card {
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .info-label {
            font-size: 10px;
            font-weight: bold;
            color: #aaa;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }
        .info-value.status-active { color: #28a745; }
        .info-value.status-warning { color: #f59e0b; }
        .info-value.status-expired { color: #dc3545; }
        .invoice-divider {
            height: 1px;
            background: #eee;
            margin: 20px 0;
        }
        .invoice-table-modern {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .invoice-table-modern th, .invoice-table-modern td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        .invoice-table-modern th {
            text-align: left;
            color: #999;
            font-weight: normal;
            border-bottom: 1px solid #ddd;
        }
        .invoice-table-modern tfoot td {
            font-weight: bold;
            border-top: 1px solid #ddd;
        }
        .bank-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            border: 1px solid #eef2f6;
        }
        .bank-title {
            font-size: 13px;
            font-weight: bold;
            color: #555;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #eef2f6;
        }
        .bank-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }
        .bank-left {
            display: flex;
            gap: 16px;
            align-items: baseline;
        }
        .bank-name {
            font-size: 13px;
            font-weight: 600;
            color: #555;
            min-width: 60px;
        }
        .bank-account {
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
            color: #333;
            letter-spacing: 0.5px;
        }
        .copy-btn {
            background: none;
            border: 1px solid #ddd;
            padding: 4px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            color: #666;
            transition: all 0.2s;
        }
        .copy-btn:hover {
            background: #e9ecef;
            border-color: #adb5bd;
        }
        .bank-owner {
            font-size: 12px;
            color: #666;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid #eef2f6;
            text-align: left;
            font-weight: 500;
        }
        .invoice-message {
            margin: 20px 0;
            padding: 12px;
            background: #fafafa;
            border-radius: 12px;
            text-align: center;
        }
        .invoice-message p {
            margin: 4px 0;
            font-size: 11px;
            color: #888;
        }
        .signature {
            margin-top: 8px;
        }
        .invoice-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .invoice-actions button {
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-print {
            background: #333;
            color: white;
        }
        .btn-wa {
            background: #25D366;
            color: white;
        }
        .btn-close {
            background: #e9ecef;
            color: #333;
        }
        .invoice-actions button:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }
    </style>
    
    <div class="add open" id="popupInvoice">
        <div class="invoice-container">
            <div class="invoice-header">
                <div class="header-left">
                    <div class="company-header">
                        <img src="${companyLogo}" alt="Logo" onerror="this.src='/img/logos.png'">
                        <div class="company-header-text">
                            <div class="company-header-name">${escapeHtml(companyName)}</div>
                            <div class="company-header-detail">
                                ${companyAddress ? `<div> ${escapeHtml(companyAddress)}</div>` : ''}
                                <div> ${escapeHtml(companyPhone)} &nbsp;|&nbsp; ${escapeHtml(companyEmail)}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <h2>INVOICE</h2>
                    <p>${nomorInvoice}</p>
                </div>
                <button class="close-invoice" id="closePopupBtn">&times;</button>
            </div>
            
            <div class="invoice-body">
                <div class="invoice-to">
                    <div class="to-label">KEPADA YTH:</div>
                    <div class="to-name">${escapeHtml(data.nama)}</div>
                    <div class="to-detail">${escapeHtml(data.perusahaan || '-')}</div>
                    <div class="to-detail">${escapeHtml(data.email)}</div>
                    <div class="to-detail">${data.phone || '-'}</div>
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
                    <thead>
                        <tr>
                            <th style="text-align: left;">DESKRIPSI</th>
                            <th style="text-align: right;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align: left;">${escapeHtml(data.description) || 'Langganan Tahunan'}</td>
                            <td style="text-align: right;">${data.pendapatan}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td style="text-align: left;"><strong>TOTAL</strong></td>
                            <td style="text-align: right;"><strong>${data.pendapatan}</strong></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="bank-section">
                    <div class="bank-title">REKENING TUJUAN</div>
                    ${bankBCA ? `
                    <div class="bank-item">
                        <div class="bank-left">
                            <span class="bank-name">BCA</span>
                            <span class="bank-account">: ${bankBCA}</span>
                        </div>
                        <button class="copy-btn" onclick="copyToClipboard('${bankBCA}')">Salin</button>
                    </div>
                    ` : ''}
                    ${bankMandiri ? `
                    <div class="bank-item">
                        <div class="bank-left">
                            <span class="bank-name">Mandiri</span>
                            <span class="bank-account">: ${bankMandiri}</span>
                        </div>
                        <button class="copy-btn" onclick="copyToClipboard('${bankMandiri}')">Salin</button>
                    </div>
                    ` : ''}
                    ${bankAccountName ? `<div class="bank-owner">Atas Nama : ${bankAccountName}</div>` : ''}
                </div>
                
                <div class="invoice-message">
                    <p>${invoiceFooter}</p>
                    <div class="signature">
                        <p>Hormat kami,</p>
                        <p><strong>${escapeHtml(companyName)}</strong></p>
                    </div>
                </div>
                
                <div class="invoice-actions">
                    <button class="btn-print" id="printBtn">🖨️ CETAK</button>
                    <button class="btn-wa" id="waBtn">📱 KIRIM WA</button>
                    <button class="btn-close" id="closePopupBtn2">TUTUP</button>
                </div>
            </div>
        </div>
    </div>`;
    
    // Insert ke body
    document.body.insertAdjacentHTML('beforeend', popupHtml);
    
    // ========== EVENT HANDLER ==========
    function closePopup() {
        const el = document.getElementById('popupInvoice');
        if (el) el.remove();
    }
    
    document.getElementById('closePopupBtn')?.addEventListener('click', closePopup);
    document.getElementById('closePopupBtn2')?.addEventListener('click', closePopup);
    
    document.getElementById('popupInvoice')?.addEventListener('click', function(e) {
        if (e.target === this) closePopup();
    });
    
    // Print
    document.getElementById('printBtn')?.addEventListener('click', function() {
        const container = document.querySelector('#popupInvoice .invoice-container');
        if (!container) return;
        const win = window.open('', '_blank');
        win.document.write(`
            <html>
            <head>
                <title>Invoice ${nomorInvoice}</title>
                <style>
                    * { margin: 0; padding: 0; box-sizing: border-box; }
                    body { font-family: Arial, sans-serif; padding: 20px; font-size: 12px; }
                    .invoice-container { max-width: 600px; margin: 0 auto; background: white; }
                    .invoice-header { padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: flex-start; }
                    .company-header { display: flex; align-items: center; gap: 10px; }
                    .company-header img { height: 40px; width: auto; }
                    .company-header-name { font-size: 14px; font-weight: bold; }
                    .company-header-detail { font-size: 10px; color: #666; }
                    .header-right h2 { font-size: 18px; margin: 0; }
                    .header-right p { font-size: 10px; margin: 0; }
                    .invoice-body { padding: 15px 20px; }
                    .invoice-to { margin-bottom: 15px; padding: 10px; background: #f8f9fa; border-radius: 8px; }
                    .to-label { font-size: 10px; font-weight: bold; color: #666; }
                    .to-name { font-size: 13px; font-weight: bold; }
                    .to-detail { font-size: 11px; color: #666; }
                    .invoice-info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 15px; }
                    .info-card { padding: 8px; background: #f8f9fa; border-radius: 8px; }
                    .info-label { font-size: 9px; font-weight: bold; color: #999; }
                    .info-value { font-size: 12px; font-weight: bold; }
                    .invoice-divider { height: 1px; background: #eee; margin: 15px 0; }
                    .invoice-table-modern { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
                    .invoice-table-modern th, .invoice-table-modern td { padding: 8px; border-bottom: 1px solid #eee; font-size: 12px; }
                    .invoice-table-modern th { text-align: left; border-bottom: 1px solid #ddd; }
                    .bank-section { background: #f8f9fa; border-radius: 8px; padding: 12px; margin: 15px 0; }
                    .bank-title { font-size: 12px; font-weight: bold; margin-bottom: 10px; }
                    .bank-item { display: flex; justify-content: space-between; padding: 5px 0; }
                    .copy-btn { display: none; }
                    .invoice-message { margin: 15px 0; padding: 10px; background: #fafafa; border-radius: 8px; text-align: center; }
                    .invoice-message p { font-size: 10px; margin: 3px 0; }
                    .invoice-actions { display: none; }
                    @media print {
                        body { padding: 0; margin: 0; }
                        .invoice-container { max-width: 100%; margin: 0; padding: 0; }
                        .invoice-header, .invoice-body { padding: 10px; }
                        .bank-section, .invoice-message { margin: 10px 0; padding: 8px; }
                        html, body { height: auto; overflow: visible; }
                        .invoice-container { page-break-inside: avoid; break-inside: avoid; }
                    }
                </style>
            </head>
            <body>
                ${container.cloneNode(true).outerHTML}
            </body>
            </html>
        `);
        win.document.close();
        win.print();
        win.close();
    });
    
    // ========== WA ==========
    const waBtn = document.getElementById('waBtn');
    if (waBtn) {
        waBtn.addEventListener('click', async function() {
            console.log("Tombol WA diklik!");
            
            Swal.fire({
                title: 'Mempersiapkan invoice...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            
            const s = await loadSettings();
            
            // Generate link
            const pdfLink = await generateInvoiceLink(data.id);
            
            let finalLink = pdfLink;
            if (!finalLink) {
              finalLink = window.location.origin + '/invoice/generate/' + data.id;
            }
            
            // Bank info
            const bankText = [];
            if (s.bankBCA && s.bankBCA !== DEFAULT_SETTINGS.bankBCA) bankText.push(`BCA: ${s.bankBCA}`);
            if (s.bankMandiri && s.bankMandiri !== DEFAULT_SETTINGS.bankMandiri) bankText.push(`Mandiri: ${s.bankMandiri}`);
            
            let bankInfo = '';
            if (bankText.length > 0) {
                bankInfo = '\n\nREKENING TUJUAN:\n' + bankText.join('\n') + '\nAtas Nama: ' + s.bankAccountName;
            }
            
            // Pesan
            let msg = 'INVOICE ' + nomorInvoice + '\n\n';
            msg += 'Kepada: ' + data.nama + '\n';
            msg += 'Perusahaan: ' + (data.perusahaan || '-') + '\n\n';
            msg += 'TAGIHAN:\n';
            msg += 'Tanggal: ' + formatDateInvoice(today) + '\n';
            msg += 'Total: ' + data.pendapatan + '\n';
            msg += 'Status: ' + statusText + bankInfo + '\n\n';
            msg += 'Link Invoice:\n' + finalLink + '\n\n';
            msg += s.invoiceFooter + '\n\n';
            msg += s.companyName + '\n';
            msg += s.companyAddress + '\n';
            msg += 'Telp: ' + s.companyPhone + ' | Email: ' + s.companyEmail;
            
            Swal.close();
            
            let phone = (data.phone || '').replace(/[^0-9]/g, '');
            if (!phone) {
                Swal.fire('Error', 'Nomor WhatsApp tidak tersedia!', 'error');
                return;
            }
            if (phone.startsWith('0')) phone = '62' + phone.substring(1);
            
            window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(msg), '_blank');
        });
    }
}

// Export ke global
window.openInvoicePopup = openInvoicePopup;
window.copyToClipboard = copyToClipboard;