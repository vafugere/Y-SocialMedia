function validateSignup() {
    let isValid = true;
    const fname = $('#first_name').val().trim();
    const fnameInput = $('#first_name');
    const fnameError = $('#error_first_name');
    if (fname === '' || fname.length > 50) {
        let msg = (fname === '') ? 'First Name is required' : 'First Name cannot exceed 50 characters';
        fnameInput.addClass('error');
        fnameError.text(msg).addClass('error-msg');
        isValid = false;
    }
    const lname = $('#last_name').val().trim();
    const lnameInput = $('#last_name');
    const lnameError = $('#error_last_name');
    if (lname === '' || lname.length > 50) {
        let msg = (lname === '') ? 'Last Name is required' : 'Last Name cannot exceed 50 characters';
        lnameInput.addClass('error');
        lnameError.text(msg).addClass('error-msg');
        isValid = false;
    }
    const email = $('#email').val().trim();
    const emailInput = $('#email');
    const emailError = $('#error_email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '' || email.length > 250) {
        let msg = (email === '') ? 'Email is required' : 'Email cannot exceed 250 characters';
        emailInput.addClass('error');
        emailError.text(msg).addClass('error-msg');
        isValid = false;
    } else if (!emailPattern.test(email)) {
        let msg = 'Please use correct email format: example@domain.com';
        emailInput.addClass('error');
        emailError.text(msg).addClass('error-msg');
        isValid = false;
    }
    const confirmEmail = $('#confirm_email').val().trim();
    const confirmEmailInput = $('#confirm_email');
    const confirmEmailError = $('#error_confirm_email');
    if (confirmEmail !== email) {
        let msg = 'Email does not match';
        confirmEmailInput.addClass('error');
        confirmEmailError.text(msg).addClass('error-msg');
        isValid = false;
    }
    const username = $('#username').val().trim();
    const usernameInput = $('#username');
    const usernameError = $('#error_username');
    if (username === '' || username.length > 80) {
        let msg = (username === '') ? 'Username is required' : 'Username cannot exceed 80 characters';
        usernameInput.addClass('error');
        usernameError.text(msg).addClass('error-msg');
        isValid = false;
    }
    const password = $('#password').val().trim();
    const passwordInput = $('#password');
    const passwordError = $('#error_password');
    if (password.length < 5 || password.length > 250) {
        let msg = (password.length < 5) ? 'Password must be longer than 4 characters' : 'Password cannot exceed 250 characters';
        passwordInput.addClass('error');
        passwordError.text(msg).addClass('error-msg');
        isValid = false;
    }
    const confirmPassword = $('#confirm_password').val().trim();
    const confirmPasswordInput = $('#confirm_password');
    const confirmPasswordError = $('#error_confirm_password');
    if (confirmPassword !== password) {
        let msg = 'Password does not match';
        confirmPasswordInput.addClass('error');
        confirmPasswordError.text(msg).addClass('error-msg');
        isValid = false;
    }
    return isValid;
}
function availableEmail(email) {
    fetch('includes/fetch_email.php?email=' + encodeURIComponent(email))
        .then(response => response.json())
        .then(data => {
            if (!data.available) {
                $('#error_email').text('This email is not available').addClass('error-msg');
            } 
        })
        .catch(error => console.log('Error ', error));
}
function availableUsername(username) {
    fetch('includes/fetch_username.php?username=' + encodeURIComponent(username))
        .then(response => response.json())
        .then(data => {
            $('#error_username').text(data.available ? 'This username is available!' : 'This username is not available');
            if (!data.available) {
                $('#error_username').removeClass('success-msg').addClass('error-msg');
            } else {
                $('#error_username').removeClass('error-msg').addClass('success-msg');
            }
        })
        .catch(error => console.log('Error: ', error));
}

$(document).ready(function () {
    $('#email').on('input', function () {
        const email = $(this).val().trim();
        if (email.length > 0) {
            availableEmail(email);
        } else {
            $('#error_email').text('').removeClass('error-msg');
        }
    });

    $('#username').on('input', function () {
        const username = $(this).val().trim();
        if (username.length > 0) {
            availableUsername(username);
        } else {
            $('#error_username').text('').removeClass('success-msg').addClass('error-msg');
        }
    });

    $('#signup_form').on('submit', function (e) {
        if (!validateSignup() || $('#error_email').hasClass('error-msg') || $('#error_username').hasClass('error-msg')) {
            e.preventDefault();
        }
    });

    $('#first_name, #last_name, #email, #confirm_email, #username, #password, #confirm_password').on('focus', function () {
        $(this).removeClass('error');
        const errorId = '#error_' + this.id;
        $(errorId).text('');
        $(errorId).removeClass('error-msg');
    });

});