// ----------------------
// VALIDATION FUNCTIONS
// ----------------------

// Validate that the name contains only letters and spaces (2–50 chars)
function validateName(name) {
    const namePattern = /^[A-Za-z\s]{2,50}$/;
    return namePattern.test(name.trim());
}

// Validate that the email follows a standard email format
function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email.trim());
}

// Validate phone number (Omani format), removing spaces and dashes first
function validatePhone(phone) {
    const cleanPhone = phone.replace(/[\s-]/g, '');
    const phonePattern = /^(\+968|968)?[279][0-9]{7,8}$/;
    return phonePattern.test(cleanPhone);
}

// Validate feedback message length (20–1000 characters)
function validateFeedbackMessage(message) {
    const trimmedMessage = message.trim();
    return trimmedMessage.length >= 20 && trimmedMessage.length <= 1000;
}

// -----------------------------
// MAIN FORM VALIDATION HANDLING
// -----------------------------

// Run when the page has finished loading
document.addEventListener('DOMContentLoaded', function() {

    // Get references to form and input fields
    const form = document.getElementById('feedbackForm');
    const nameInput = document.getElementById('feedbackName');
    const emailInput = document.getElementById('feedbackEmail');
    const phoneInput = document.getElementById('feedbackPhone');
    const messageInput = document.getElementById('feedbackMessage');

    // --------------------------
    // LIVE INPUT FIELD VALIDATION
    // --------------------------

    // Validate name while typing
    nameInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            validateName(this.value)
                ? this.classList.replace('is-invalid', 'is-valid')
                : this.classList.replace('is-valid', 'is-invalid');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });

    // Validate email while typing
    emailInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            validateEmail(this.value)
                ? this.classList.replace('is-invalid', 'is-valid')
                : this.classList.replace('is-valid', 'is-invalid');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });

    // Validate phone number while typing
    phoneInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            validatePhone(this.value)
                ? this.classList.replace('is-invalid', 'is-valid')
                : this.classList.replace('is-valid', 'is-invalid');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });

    // Validate message while typing
    messageInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            validateFeedbackMessage(this.value)
                ? this.classList.replace('is-invalid', 'is-valid')
                : this.classList.replace('is-valid', 'is-invalid');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });

    // --------------------------
    // FORM SUBMISSION VALIDATION
    // --------------------------

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isValid = true;

        if (!validateName(nameInput.value)) {
            nameInput.classList.add('is-invalid');
            isValid = false;
        } else {
            nameInput.classList.add('is-valid');
        }

        if (!validateEmail(emailInput.value)) {
            emailInput.classList.add('is-invalid');
            isValid = false;
        } else {
            emailInput.classList.add('is-valid');
        }

        if (!validatePhone(phoneInput.value)) {
            phoneInput.classList.add('is-invalid');
            isValid = false;
        } else {
            phoneInput.classList.add('is-valid');
        }

        if (!validateFeedbackMessage(messageInput.value)) {
            messageInput.classList.add('is-invalid');
            isValid = false;
        } else {
            messageInput.classList.add('is-valid');
        }

        const satisfaction = document.querySelector('input[name="satisfaction"]:checked');
        if (!satisfaction) {
            isValid = false;
            alert('Please select your overall satisfaction level.');
        }

        const hearAbout = document.querySelector('input[name="hearAbout"]:checked');
        if (!hearAbout) {
            isValid = false;
            alert('Please tell us how you heard about us.');
        }

        const recommend = document.querySelector('input[name="recommend"]:checked');
        if (!recommend) {
            isValid = false;
            alert('Please tell us if you would recommend Booklify.');
        }

        if (isValid) {
            form.submit();
        } else {
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        form.classList.add('was-validated');
    });
});
