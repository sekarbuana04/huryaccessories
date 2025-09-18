// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Handle form submission
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.querySelector('.btn-login-submit');
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const rememberMe = document.getElementById('remember_me').checked;
    
    if (!username || !password) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Username dan password harus diisi!',
            confirmButtonColor: '#B88A53'
        });
        return;
    }
    
    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    // Create form data
    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('username', username);
    formData.append('password', password);
    formData.append('remember_me', rememberMe ? '1' : '0');
    
    // Send AJAX request
    fetch('admin/auth.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Masuk!',
                text: data.message,
                confirmButtonColor: '#B88A53',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = data.redirect;
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: data.message,
                confirmButtonColor: '#B88A53'
            });
        }
    })
    .catch(error => {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan sistem. Silakan coba lagi.',
            confirmButtonColor: '#B88A53'
        });
        console.error('Login error:', error);
    });
});

// Auto-focus username field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('username').focus();
});