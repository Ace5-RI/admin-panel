// ==================== SETTINGS JS ====================

console.log("SETTINGS JS LOADED 🔥");

// ================== LOAD SETTINGS FROM DATABASE ==================
async function loadSettings() {
    try {
        const response = await fetch('/api/settings');
        const data = await response.json();
        
        const companyName = document.getElementById('companyName');
        const companyAddress = document.getElementById('companyAddress');
        const companyPhone = document.getElementById('companyPhone');
        const companyEmail = document.getElementById('companyEmail');
        const bankBCA = document.getElementById('bankBCA');
        const bankMandiri = document.getElementById('bankMandiri');
        const bankAccountName = document.getElementById('bankAccountName');
        
        if (companyName) companyName.value = data.company_name || '';
        if (companyAddress) companyAddress.value = data.company_address || '';
        if (companyPhone) companyPhone.value = data.company_phone || '';
        if (companyEmail) companyEmail.value = data.company_email || '';
        if (bankBCA) bankBCA.value = data.bank_bca || '';
        if (bankMandiri) bankMandiri.value = data.bank_mandiri || '';
        if (bankAccountName) bankAccountName.value = data.bank_account_name || '';
        
        const logoPreview = document.getElementById('logoPreview');
        if (logoPreview) {
            if (data.company_logo && data.company_logo !== '/img/logos.png') {
                logoPreview.innerHTML = `<img src="${data.company_logo}?t=${Date.now()}" alt="Logo">`;
            } else {
                logoPreview.innerHTML = '<span class="no-logo">🏢</span>';
            }
        }
        
        updatePreview();
        
        localStorage.setItem('companySettings', JSON.stringify({
            companyName: data.company_name,
            companyAddress: data.company_address,
            companyPhone: data.company_phone,
            companyEmail: data.company_email,
            bankBCA: data.bank_bca,
            bankMandiri: data.bank_mandiri,
            bankAccountName: data.bank_account_name,
            companyLogo: data.company_logo
        }));
        
    } catch (error) {
        console.error('Error loading settings:', error);
        loadFromLocalStorage();
    }
}

function loadFromLocalStorage() {
    const settings = JSON.parse(localStorage.getItem('companySettings') || '{}');
    
    const companyName = document.getElementById('companyName');
    const companyAddress = document.getElementById('companyAddress');
    const companyPhone = document.getElementById('companyPhone');
    const companyEmail = document.getElementById('companyEmail');
    const bankBCA = document.getElementById('bankBCA');
    const bankMandiri = document.getElementById('bankMandiri');
    const bankAccountName = document.getElementById('bankAccountName');
    
    if (companyName) companyName.value = settings.companyName || '';
    if (companyAddress) companyAddress.value = settings.companyAddress || '';
    if (companyPhone) companyPhone.value = settings.companyPhone || '';
    if (companyEmail) companyEmail.value = settings.companyEmail || '';
    if (bankBCA) bankBCA.value = settings.bankBCA || '';
    if (bankMandiri) bankMandiri.value = settings.bankMandiri || '';
    if (bankAccountName) bankAccountName.value = settings.bankAccountName || '';
    
    const logoPreview = document.getElementById('logoPreview');
    if (logoPreview && settings.companyLogo && settings.companyLogo !== '/img/logos.png') {
        logoPreview.innerHTML = `<img src="${settings.companyLogo}" alt="Logo">`;
    }
    
    updatePreview();
}

async function saveSettings() {
    const companyNameInput = document.getElementById('companyName');
    if (!companyNameInput || !companyNameInput.value) {
        showAlert('Nama perusahaan wajib diisi!', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('company_name', companyNameInput.value);
    formData.append('company_address', document.getElementById('companyAddress')?.value || '');
    formData.append('company_phone', document.getElementById('companyPhone')?.value || '');
    formData.append('company_email', document.getElementById('companyEmail')?.value || '');
    formData.append('bank_bca', document.getElementById('bankBCA')?.value || '');
    formData.append('bank_mandiri', document.getElementById('bankMandiri')?.value || '');
    formData.append('bank_account_name', document.getElementById('bankAccountName')?.value || '');
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    
    try {
        const response = await fetch('/settings/update', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('✅ ' + result.message, 'success');
            
            const settings = {
                companyName: companyNameInput.value,
                companyAddress: document.getElementById('companyAddress')?.value || '',
                companyPhone: document.getElementById('companyPhone')?.value || '',
                companyEmail: document.getElementById('companyEmail')?.value || '',
                bankBCA: document.getElementById('bankBCA')?.value || '',
                bankMandiri: document.getElementById('bankMandiri')?.value || '',
                bankAccountName: document.getElementById('bankAccountName')?.value || ''
            };
            localStorage.setItem('companySettings', JSON.stringify(settings));
            
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('❌ Gagal menyimpan!', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('❌ Terjadi kesalahan!', 'error');
    }
}

async function uploadLogo(file) {
    const formData = new FormData();
    formData.append('logo', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
    
    try {
        const response = await fetch('/api/settings/logo', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            const logoPreview = document.getElementById('logoPreview');
            if (logoPreview) {
                logoPreview.innerHTML = `<img src="${result.logo_url}?t=${Date.now()}" alt="Logo">`;
            }
            showAlert('✅ Logo berhasil diupload!', 'success');
            
            let settings = JSON.parse(localStorage.getItem('companySettings') || '{}');
            settings.companyLogo = result.logo_url;
            localStorage.setItem('companySettings', JSON.stringify(settings));
            
            updatePreview();
        } else {
            showAlert('❌ Gagal upload logo!', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('❌ Gagal upload logo!', 'error');
    }
}

function updatePreview() {
    const previewName = document.getElementById('previewCompanyName');
    const previewAddress = document.getElementById('previewAddress');
    const previewPhone = document.getElementById('previewPhone');
    const previewEmail = document.getElementById('previewEmail');
    const previewBCA = document.getElementById('previewBCA');
    const previewMandiri = document.getElementById('previewMandiri');
    const previewAccountName = document.getElementById('previewAccountName');
    
    if (previewName) previewName.textContent = document.getElementById('companyName')?.value || 'Nama Perusahaan';
    if (previewAddress) previewAddress.textContent = document.getElementById('companyAddress')?.value || 'Jl. Contoh No. 123';
    if (previewPhone) previewPhone.textContent = document.getElementById('companyPhone')?.value || '+62 812-3456-7890';
    if (previewEmail) previewEmail.textContent = document.getElementById('companyEmail')?.value || 'info@perusahaan.com';
    if (previewBCA) previewBCA.textContent = document.getElementById('bankBCA')?.value || '-';
    if (previewMandiri) previewMandiri.textContent = document.getElementById('bankMandiri')?.value || '-';
    if (previewAccountName) previewAccountName.textContent = document.getElementById('bankAccountName')?.value || '-';
}

async function resetToDefault() {
    const confirm = await Swal.fire({
        title: 'Reset Pengaturan?',
        text: 'Semua pengaturan akan kembali ke default!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
    });
    
    if (confirm.isConfirmed) {
        const companyName = document.getElementById('companyName');
        const companyAddress = document.getElementById('companyAddress');
        const companyPhone = document.getElementById('companyPhone');
        const companyEmail = document.getElementById('companyEmail');
        const bankBCA = document.getElementById('bankBCA');
        const bankMandiri = document.getElementById('bankMandiri');
        const bankAccountName = document.getElementById('bankAccountName');
        
        if (companyName) companyName.value = 'Bali Solution Biz';
        if (companyAddress) companyAddress.value = '';
        if (companyPhone) companyPhone.value = '';
        if (companyEmail) companyEmail.value = '';
        if (bankBCA) bankBCA.value = '';
        if (bankMandiri) bankMandiri.value = '';
        if (bankAccountName) bankAccountName.value = '';
        
        updatePreview();
        await saveSettings();
    }
}

function showAlert(message, type) {
    const alertDiv = document.getElementById('alertMessage');
    if (!alertDiv) return;
    
    const alertClass = type === 'success' ? 'alert-success' : 'alert-warning';
    alertDiv.innerHTML = `<div class="alert ${alertClass}">${message}</div>`;
    setTimeout(() => {
        if (alertDiv.innerHTML) alertDiv.innerHTML = '';
    }, 3000);
}

// ================== LOGO UPLOAD HANDLER ==================
const logoUploadArea = document.getElementById('logoUploadArea');
const logoInput = document.getElementById('logoInput');

if (logoUploadArea) {
    logoUploadArea.addEventListener('click', () => {
        if (logoInput) logoInput.click();
    });
}

if (logoInput) {
    logoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file && ['image/jpeg', 'image/png'].includes(file.type)) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const logoPreview = document.getElementById('logoPreview');
                if (logoPreview) {
                    logoPreview.innerHTML = `<img src="${event.target.result}" alt="Logo">`;
                }
                updatePreview();
            };
            reader.readAsDataURL(file);
            uploadLogo(file);
        } else {
            showAlert('Format file tidak didukung! Gunakan JPG atau PNG', 'error');
        }
    });
}

// ================== INPUT LISTENERS ==================
['companyName', 'companyAddress', 'companyPhone', 'companyEmail', 'bankBCA', 'bankMandiri', 'bankAccountName'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
    }
});

// ================== LOAD USER DATA (LANGSUNG DARI LOCALSTORAGE) ==================
const avatarSidebar = document.getElementById("avatarSidebar");
const nameSidebar = document.getElementById("userNameSidebar");
const emailSidebar = document.getElementById("userEmailSidebar");

function updateSidebar(user) {
    if (nameSidebar) nameSidebar.textContent = user.name;
    if (emailSidebar) emailSidebar.textContent = user.email;
    if (avatarSidebar) {
        const nameParts = user.name.split(" ").slice(0, 2);
        avatarSidebar.textContent = nameParts.map(w => w[0]).join("").toUpperCase();
    }
}

const userLS = localStorage.getItem("user_name");
const emailLS = localStorage.getItem("user_email");

if (userLS && emailLS) {
    updateSidebar({ name: userLS, email: emailLS });
    if (!localStorage.getItem("welcome_shown")) {
        Swal.fire({ title: "Selamat Datang 👋", text: "Halo " + userLS, icon: "success", confirmButtonText: "OK" });
        localStorage.setItem("welcome_shown", "true");
    }
}

// ================== LOGOUT ==================
if (logoutBtn) {
    logoutBtn.addEventListener("click", async (e) => {
        const result = await Swal.fire({ 
            title: 'Yakin ingin logout?', 
            icon: 'question', 
            showCancelButton: true, 
            confirmButtonColor: '#d33', 
            confirmButtonText: 'Ya, Logout!', 
            cancelButtonText: 'Batal' 
        });
        
        if (result.isConfirmed) {
            // Buat form POST dan submit — cara paling reliable
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ================== INIT ==================
document.addEventListener('DOMContentLoaded', () => {
    loadUserData();
    loadSettings();
    setupLogout();
    console.log('✅ Settings page ready!');
});