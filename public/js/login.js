// ambil semua tombol yang punya class select-btn
const buttons = document.querySelectorAll('.select-btn');

// looping setiap tombol
buttons.forEach(button => {

    // kasih event ketika tombol diklik
    button.addEventListener('click', function(){

        // hapus class active dari semua tombol
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // tambahkan class active ke tombol yang diklik
        this.classList.add('active');

    });

});

const card = document.querySelector(".login-card");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");

showRegister.onclick = function(e){
    e.preventDefault();
    card.classList.add("active");
}

showLogin.onclick = function(e){
    e.preventDefault();
    card.classList.remove("active");
}