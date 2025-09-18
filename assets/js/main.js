// Main JavaScript for Hury Asesoris Website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize components
    initSlider();
    initScrollAnimation();
    initMobileMenu();
    initScrollUp();
    initProductFilter();
    initFormValidation();
    initParallaxEffect();

    // Header scroll effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
});

// Hero Slider
function initSlider() {
    const slides = document.querySelectorAll('.slide');
    if (slides.length === 0) return;

    let currentSlide = 0;
    
    // Show first slide
    slides[0].classList.add('active');
    
    // Auto slide change
    setInterval(() => {
        // Hide current slide
        slides[currentSlide].classList.remove('active');
        
        // Move to next slide
        currentSlide = (currentSlide + 1) % slides.length;
        
        // Show next slide
        slides[currentSlide].classList.add('active');
    }, 5000);
}

// Scroll Animation
function initScrollAnimation() {
    const animatedElements = document.querySelectorAll('.fade-up, .fade-in, .fade-left, .fade-right, .product-card, .about-content, .about-image');
    
    // Fallback: Show all elements immediately if IntersectionObserver is not supported
    if (!window.IntersectionObserver) {
        animatedElements.forEach(element => {
            element.classList.add('animate');
        });
        return;
    }
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
    
    // Fallback: Show elements that are already in viewport
    setTimeout(() => {
        animatedElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                element.classList.add('animate');
            }
        });
    }, 100);
    
    // Special handling for catalog page - show all product cards immediately
    if (window.location.pathname.includes('catalog.php')) {
        setTimeout(() => {
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.classList.add('animate');
            });
        }, 200);
    }
}

// Mobile Menu
function initMobileMenu() {
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu');
    
    if (!menuBtn || !navMenu) return;
    
    menuBtn.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        menuBtn.innerHTML = navMenu.classList.contains('active') ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
    });
    
    // Close menu when clicking on a link
    const navLinks = document.querySelectorAll('.nav-menu a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
        });
    });
}

// Scroll Up Button
function initScrollUp() {
    const scrollUpBtn = document.querySelector('.scroll-up');
    
    if (!scrollUpBtn) return;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollUpBtn.classList.add('active');
        } else {
            scrollUpBtn.classList.remove('active');
        }
    });
    
    scrollUpBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Product Filter
function initProductFilter() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productItems = document.querySelectorAll('.catalog-item');
    
    if (filterBtns.length === 0 || productItems.length === 0) return;
    
    // Show all products initially
    productItems.forEach(item => {
        item.style.display = 'block';
        item.style.opacity = '1';
        item.style.transform = 'translateY(0)';
    });
    
    // Note: Filter functionality is handled by PHP via URL parameters
    // This function now only ensures products are visible
}

// Form Validation
function initFormValidation() {
    const contactForm = document.getElementById('contact-form');
    
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const messageInput = document.getElementById('message');
        
        // Reset errors
        const errorElements = document.querySelectorAll('.form-error');
        errorElements.forEach(error => error.style.display = 'none');
        
        // Validate name
        if (!nameInput.value.trim()) {
            document.getElementById('name-error').style.display = 'block';
            isValid = false;
        }
        
        // Validate email
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(emailInput.value)) {
            document.getElementById('email-error').style.display = 'block';
            isValid = false;
        }
        
        // Validate message
        if (!messageInput.value.trim()) {
            document.getElementById('message-error').style.display = 'block';
            isValid = false;
        }
        
        // Submit form if valid
        if (isValid) {
            // Here you would typically send the form data to a server
            // For now, we'll just show a success message
            contactForm.reset();
            alert('Pesan Anda telah berhasil dikirim. Terima kasih!');
        }
    });
}

// Parallax Effect
function initParallaxEffect() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length === 0) return;
    
    window.addEventListener('scroll', () => {
        parallaxElements.forEach(element => {
            const scrollPosition = window.scrollY;
            const elementPosition = element.offsetTop;
            const distance = elementPosition - scrollPosition;
            
            // Apply parallax effect
            element.style.backgroundPositionY = `${distance * 0.5}px`;
        });
    });
}