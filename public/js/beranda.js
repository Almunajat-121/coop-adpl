 // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function switchModal(currentModal, targetModal) {
            closeModal(currentModal);
            openModal(targetModal);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Form submissions
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Fitur login akan segera tersedia!');
            closeModal('loginModal');
        });

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Pendaftaran berhasil! Selamat datang di komunitas Reuse & Share!');
            closeModal('registerModal');
        });

        document.getElementById('shareForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Barang berhasil dibagikan! Terima kasih telah berkontribusi pada komunitas!');
            closeModal('shareModal');
        });

        // Add floating share button
        const floatingBtn = document.createElement('div');
        floatingBtn.innerHTML = '+';
        floatingBtn.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #4a9b8e, #5fb3a3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(74, 155, 142, 0.3);
            transition: all 0.3s;
            z-index: 999;
        `;
        
        floatingBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        
        floatingBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
        
        floatingBtn.addEventListener('click', function() {
            openModal('shareModal');
        });
        
        document.body.appendChild(floatingBtn);

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(10px)';
            } else {
                navbar.style.background = 'white';
                navbar.style.backdropFilter = 'none';
            }
        });

        // Add loading animation for feature cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });