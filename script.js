// Menu burger responsive
const burger = document.getElementById('burger-menu');
const navLinks = document.querySelector('.nav-links');

if (burger) {
    burger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
}

// Menu profil utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.querySelector('.user-profile-dropdown');
    
    if (profileDropdown) {
        const profileToggle = profileDropdown.querySelector('.profile-toggle');
        const dropdownMenu = profileDropdown.querySelector('.profile-dropdown-menu');
        
        // Ouvrir/fermer le menu au clic
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });
        
        // Fermer le menu en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('active');
            }
        });
        
        // Fermer le menu avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                profileDropdown.classList.remove('active');
            }
        });
        
        // Fermer le menu après clic sur un lien
        const dropdownLinks = dropdownMenu.querySelectorAll('.dropdown-item');
        dropdownLinks.forEach(link => {
            link.addEventListener('click', function() {
                profileDropdown.classList.remove('active');
            });
        });
    }
}); 