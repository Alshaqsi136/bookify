// ===================================================================
// CONTACT FORM VALIDATION FUNCTIONS
// ===================================================================

/**
 * Validate name (letters and spaces, min 2 chars)
 */
function validateName(name) {
    const namePattern = /^[A-Za-z\s]{2,50}$/;
    return namePattern.test(name.trim());
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email.trim());
}

/**
 * Validate phone number (8-15 digits)
 */
function validatePhone(phone) {
    const cleanPhone = phone.replace(/[\s\-()]/g, '');
    const phonePattern = /^[0-9]{8,15}$/;
    return phonePattern.test(cleanPhone);
}

/**
 * Validate message length (min 10 characters)
 */
function validateMessage(message) {
    return message.trim().length >= 10;
}

// ===================================================================
// EVENT LISTENERS AND FORM HANDLING
// ===================================================================

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (!form) return;
    
    const firstnameInput = document.getElementById('firstname');
    const lastnameInput = document.getElementById('lastname');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const subjectInput = document.getElementById('subject');
    const messageInput = document.getElementById('message');
    
    // ===== FIRST NAME VALIDATION =====
    firstnameInput.addEventListener('blur', function() {
        if (this.value.trim() !== '') {
            const isValid = validateName(this.value);
            if (!isValid) {
                showError(this, 'First name must contain only letters and spaces (2-50 characters).');
            } else {
                clearError(this);
            }
        }
    });
    
    // ===== LAST NAME VALIDATION =====
    lastnameInput.addEventListener('blur', function() {
        if (this.value.trim() !== '') {
            const isValid = validateName(this.value);
            if (!isValid) {
                showError(this, 'Last name must contain only letters and spaces (2-50 characters).');
            } else {
                clearError(this);
            }
        }
    });
    
    // ===== EMAIL VALIDATION =====
    emailInput.addEventListener('blur', function() {
        if (this.value.trim() !== '') {
            const isValid = validateEmail(this.value);
            if (!isValid) {
                showError(this, 'Please enter a valid email address (e.g., user@example.com).');
            } else {
                clearError(this);
            }
        }
    });
    
    // ===== PHONE VALIDATION =====
    phoneInput.addEventListener('blur', function() {
        if (this.value.trim() !== '') {
            const isValid = validatePhone(this.value);
            if (!isValid) {
                showError(this, 'Please enter a valid phone number (8-15 digits).');
            } else {
                clearError(this);
            }
        }
    });
    
    // ===== MESSAGE VALIDATION =====
    messageInput.addEventListener('blur', function() {
        if (this.value.trim() !== '') {
            const isValid = validateMessage(this.value);
            if (!isValid) {
                showError(this, 'Message must be at least 10 characters.');
            } else {
                clearError(this);
            }
        }
    });
    
    // ===== FORM SUBMISSION VALIDATION =====
    form.addEventListener('submit', function(event) {
        let isFormValid = true;
        
        // Validate first name
        if (!validateName(firstnameInput.value)) {
            showError(firstnameInput, 'First name is required and must contain only letters and spaces.');
            isFormValid = false;
        } else {
            clearError(firstnameInput);
        }
        
        // Validate last name
        if (!validateName(lastnameInput.value)) {
            showError(lastnameInput, 'Last name is required and must contain only letters and spaces.');
            isFormValid = false;
        } else {
            clearError(lastnameInput);
        }
        
        // Validate email
        if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Please provide a valid email address.');
            isFormValid = false;
        } else {
            clearError(emailInput);
        }
        
        // Validate phone if provided
        if (phoneInput.value.trim() !== '' && !validatePhone(phoneInput.value)) {
            showError(phoneInput, 'Please provide a valid phone number (8-15 digits).');
            isFormValid = false;
        } else {
            clearError(phoneInput);
        }
        
        // Validate subject
        if (subjectInput.value === '') {
            showError(subjectInput, 'Please select a subject.');
            isFormValid = false;
        } else {
            clearError(subjectInput);
        }
        
        // Validate message
        if (!validateMessage(messageInput.value)) {
            showError(messageInput, 'Message must be at least 10 characters.');
            isFormValid = false;
        } else {
            clearError(messageInput);
        }
        
        if (!isFormValid) {
            event.preventDefault();
            event.stopPropagation();
            alert('Please fix all errors before submitting the form.');
        }
    });
});

// ===================================================================
// HELPER FUNCTIONS FOR ERROR DISPLAY
// ===================================================================

/**
 * Display error message near a form field
 */
function showError(element, message) {
    // Remove existing error message if any
    const existingError = element.parentElement.querySelector('.contact-error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error styling
    element.classList.add('is-invalid');
    element.classList.remove('is-valid');
    
    // Create and display error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'contact-error-message text-danger small mt-1';
    errorDiv.textContent = message;
    element.parentElement.appendChild(errorDiv);
}

/**
 * Clear error message from a form field
 */
function clearError(element) {
    if (!element) return;
    
    element.classList.remove('is-invalid');
    
    const existingError = element.parentElement.querySelector('.contact-error-message');
    if (existingError) {
        existingError.remove();
    }
}
