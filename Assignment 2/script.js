document.getElementById('loginForm').addEventListener('submit', function(event) {
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    if (!email || !password) {
        alert('Please fill in all fields.');
        event.preventDefault();
    }
});
