document.addEventListener('DOMContentLoaded', () => {
    const loginModalBtn = document.getElementById('loginModalBtn');
    const authModal = document.getElementById('authModal');
    const closeBtn = document.getElementsByClassName('close')[0];
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const showRegister = document.getElementById('showRegister');
    const showLogin = document.getElementById('showLogin');

    loginModalBtn.onclick = () => {
        authModal.style.display = 'block';
    }

    closeBtn.onclick = () => {
        authModal.style.display = 'none';
    }

    showRegister.onclick = () => {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
    }

    showLogin.onclick = () => {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
    }

    window.onclick = (event) => {
        if (event.target == authModal) {
            authModal.style.display = 'none';
        }
    }
});
