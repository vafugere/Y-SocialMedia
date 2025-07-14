function validateEditProfile() {
    let isValid = true;
    const name = $('#name').val().trim();
    if (name.length > 1) {
        if (name.length > 80) {
            alert('Name cannot exceed 80 characters');
            $('#name').val('');
            isValid = false;
        }
    }
    const email = $('#email').val().trim();
    const emailError = $('#error_email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.length > 1) {
        if (email.length > 250) {
            let msg = 'Email cannot exceed 250 characters';
            emailError.text(msg).addClass('error-text');
            isValid = false;
        } else if (!emailPattern.test(email)) {
            let msg = 'Please use correct email format: example@domain.com';
            emailError.text(msg).addClass('error-text');
            isValid = false;
        }
    }
    const password = $('#password').val().trim();
    const confirmPassword = $('#confirm_password').val().trim();
    const passwordError = $('#error_password');
    const currentPassword = $('#current_password').val().trim();
    const currentPasswordError = $('#error_current_password');
    
    if (password.length > 1) {
        if (password.length > 250 || password != confirmPassword) {
            let msg = (password.length > 250) ? 'Password cannot exceed 250 characters' : 'Password does not match';
            passwordError.text(msg).addClass('error-text');
            isValid = false;
        }
        if (currentPassword == '') {
            let msg = 'Please enter your current password';
            currentPasswordError.text(msg).addClass('error-text');
            isValid = false;
        }
    }
    return isValid;
}
function validatePassword(password) {
    fetch('includes/fetch_validation.php?password=' + encodeURIComponent(password))
        .then(res => res.json())
        .then(data => {
            if (!data.available) {
                $('#error_current_password').text('Incorrect current password').addClass('error-text');
            }
        })
        .catch(error => console.log('Error', error));
}
function validateUpload(input) {
    const file = input.files[0];
    const maxSize = 2 * 1024 * 1024;

    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('Please upload a valid image file (jpg, png, gif)');
        input.value = '';
        return;
    }
    if (file.size > maxSize) {
        alert('File size must be under 2MB');
        input.value = '';
        return;
    }
    if (file.name.length > 250) {
        alert('File name must be under 250 characters');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        $('#preview').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
}

$(document).ready(function () {
    $('#edit_icon').on('click', function () {
        $('#name').prop('disabled', false).focus();
    });

    $('body').on('change', '#profile_pic', function () {
        validateUpload(this);
    });

    $('#current_password').on('input', function () {
        $('#error_current_password').text('').removeClass('error-text');
    });

    $('#current_password').on('blur', function () {
        const password = $(this).val().trim();
        if (password.length > 1) {
            validatePassword(password);
        }
    });

    $('#edit_form').on('submit', function (e) {
        if (!validateEditProfile() || $('#error_old_password').hasClass('error-text')) {
            e.preventDefault();
        }
    }); 

    $('#email, #password, #confirm_password').on('focus', function () {
        const errorId = '#error_' + this.id;
        $(errorId).text('').removeClass('error-text');
    });

});

