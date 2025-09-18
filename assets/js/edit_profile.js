document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const alertContainer = document.getElementById('alert-container');
    
    // Clear previous alerts
    alertContainer.innerHTML = '';
    
    // Disable submit button
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    
    fetch('edit_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alertContainer.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> ${data.message}
                </div>
            `;
            
            // Clear password fields
            document.getElementById('current_password').value = '';
            document.getElementById('new_password').value = '';
            document.getElementById('confirm_password').value = '';
            
            // Scroll to top
            window.scrollTo(0, 0);
        } else {
            alertContainer.innerHTML = `
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> ${data.message}
                </div>
            `;
            window.scrollTo(0, 0);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alertContainer.innerHTML = `
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> Terjadi kesalahan sistem
            </div>
        `;
        window.scrollTo(0, 0);
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Password validation
document.getElementById('new_password').addEventListener('input', function() {
    const currentPassword = document.getElementById('current_password');
    if (this.value && !currentPassword.value) {
        currentPassword.required = true;
    } else if (!this.value) {
        currentPassword.required = false;
    }
});