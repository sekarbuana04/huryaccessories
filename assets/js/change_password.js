// Change Password JavaScript

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    let strength = 0;
    let text = '';
    let className = '';
    
    if (password.length >= 6) strength += 1;
    if (password.length >= 8) strength += 1;
    if (/[a-z]/.test(password)) strength += 1;
    if (/[A-Z]/.test(password)) strength += 1;
    if (/[0-9]/.test(password)) strength += 1;
    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    
    if (strength <= 2) {
        text = 'Lemah';
        className = 'strength-weak';
    } else if (strength <= 4) {
        text = 'Sedang';
        className = 'strength-medium';
    } else {
        text = 'Kuat';
        className = 'strength-strong';
    }
    
    const strengthText = document.getElementById('strength-text');
    const strengthFill = document.getElementById('strength-fill');
    
    strengthText.textContent = text;
    strengthFill.className = 'strength-fill ' + className;
    strengthFill.style.width = (strength / 6 * 100) + '%';
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('new_password').addEventListener('input', function() {
        checkPasswordStrength(this.value);
    });
    
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Konfirmasi password tidak cocok'
            });
            return;
        }
        
        const formData = new FormData(this);
        
        // Show loading
        Swal.fire({
            title: 'Mengubah Password...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('change_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Clear form only, stay on current page
                    document.getElementById('passwordForm').reset();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan sistem'
            });
        });
    });
});