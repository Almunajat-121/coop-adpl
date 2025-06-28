 function showPage(pageType) {
            // Hide all pages
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            
            // Show selected page
            document.getElementById(pageType + '-page').classList.add('active');
            
            // Update navigation buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Find and activate correct button
            const buttons = document.querySelectorAll('.nav-btn');
            buttons.forEach(btn => {
                if ((pageType === 'register' && btn.textContent === 'Registrasi') ||
                    (pageType === 'login' && btn.textContent === 'Log in')) {
                    btn.classList.add('active');
                }
            });
        }

        // Form submission handlers
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Registrasi berhasil! Selamat datang di Reuse & Share.');
        });

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Login berhasil! Selamat datang kembali di Reuse & Share.');
        });

        // Page load animation
        document.addEventListener('DOMContentLoaded', function() {
            const rightSide = document.querySelector('.right-side');
            rightSide.style.opacity = '0';
            rightSide.style.transform = 'translateX(50px)';
            
            setTimeout(() => {
                rightSide.style.transition = 'all 0.6s ease';
                rightSide.style.opacity = '1';
                rightSide.style.transform = 'translateX(0)';
            }, 300);
        });
   