// Form Validation for Contact Page
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            let isValid = true;
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const messageInput = document.getElementById('message');
            
            // Remove any existing error messages
            const errorElements = document.querySelectorAll('.form-error');
            errorElements.forEach(error => {
                if (!error.classList.contains('server-error')) {
                    error.remove();
                }
            });
            
            // Validate name
            if (!nameInput.value.trim()) {
                isValid = false;
                const errorElement = document.createElement('div');
                errorElement.className = 'form-error';
                errorElement.textContent = 'Nama tidak boleh kosong';
                nameInput.parentNode.appendChild(errorElement);
                nameInput.focus();
            }
            
            // Validate email
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                isValid = false;
                const errorElement = document.createElement('div');
                errorElement.className = 'form-error';
                errorElement.textContent = 'Email tidak valid';
                emailInput.parentNode.appendChild(errorElement);
                if (nameInput.value.trim()) {
                    emailInput.focus();
                }
            }
            
            // Validate message
            if (!messageInput.value.trim()) {
                isValid = false;
                const errorElement = document.createElement('div');
                errorElement.className = 'form-error';
                errorElement.textContent = 'Pesan tidak boleh kosong';
                messageInput.parentNode.appendChild(errorElement);
                if (nameInput.value.trim() && emailPattern.test(emailInput.value)) {
                    messageInput.focus();
                }
            }
            
            // If form is not valid, prevent submission
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});