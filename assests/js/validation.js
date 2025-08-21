// Validation du formulaire d'inscription
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    if(registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let valid = true;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Vérification des mots de passe
            if(password.length < 6) {
                alert('Le mot de passe doit contenir au moins 6 caractères');
                valid = false;
            }
            
            if(password !== confirmPassword) {
                alert('Les mots de passe ne correspondent pas');
                valid = false;
            }
            
            if(!valid) {
                e.preventDefault();
            }
        });
    }
    
    // Validation du formulaire de connexion
    const loginForm = document.getElementById('loginForm');
    if(loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            
            if(!email.includes('@')) {
                alert('Veuillez entrer une adresse email valide');
                e.preventDefault();
            }
        });
    }
});