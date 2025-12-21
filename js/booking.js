// ===================================================================
// BOOKING FORM VALIDATION FUNCTIONS
// ===================================================================

/**
 * Validate destination field (non-empty, min 3 characters)
 */
function validateDestination(destination) {
    const trimmed = destination.trim();
    return trimmed.length >= 3;
}

/**
 * Validate email format
 */
function validateEmail(email) {
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return emailPattern.test(email.trim());
}

/**
 * Validate phone number (10-15 digits)
 */
function validatePhone(phone) {
    const cleanPhone = phone.replace(/[\s\-()]/g, '');
    const phonePattern = /^[0-9]{10,15}$/;
    return phonePattern.test(cleanPhone);
}

/**
 * Validate check-in and check-out dates
 */
function validateDates(checkinDate, checkoutDate) {
    if (!checkinDate || !checkoutDate) {
        return { valid: false, message: 'Both check-in and check-out dates are required.' };
    }
    
    const checkin = new Date(checkinDate);
    const checkout = new Date(checkoutDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (checkin < today) {
        return { valid: false, message: 'Check-in date cannot be in the past.' };
    }
    
    if (checkout <= checkin) {
        return { valid: false, message: 'Check-out date must be after check-in date.' };
    }
    
    return { valid: true, message: '' };
}

/**
 * Validate number of guests (at least 1 adult and logical room count)
 */
function validateGuests(adults, children, rooms) {
    if (parseInt(adults) < 1) {
        return { valid: false, message: 'At least 1 adult is required.' };
    }
    
    const totalGuests = parseInt(adults) + parseInt(children);
    const maxGuestsPerRoom = 4;
    
    if (totalGuests > parseInt(rooms) * maxGuestsPerRoom) {
        return { 
            valid: false, 
            message: `Too many guests for ${rooms} room(s). Maximum ${parseInt(rooms) * maxGuestsPerRoom} guests allowed.` 
        };
    }
    
    return { valid: true, message: '' };
}

/**
 * Validate guest name (letters and spaces, min 2 chars)
 */
function validateName(name) {
    const namePattern = /^[A-Za-z\s]{2,50}$/;
    return namePattern.test(name.trim());
}

/**
 * Validate that at least one confirmation method is selected
 */
function validateConfirmationMethod(methodElement) {
    const form = methodElement.closest('form');
    const checkedMethods = form.querySelectorAll('input[name="confirmation_method"]:checked');
    return checkedMethods.length > 0;
}

// ===================================================================
// EVENT LISTENERS AND FORM HANDLING
// ===================================================================

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    
    if (!form) return;
    
    const destinationInput = document.getElementById('destination');
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    const roomsInput = document.getElementById('rooms');
    const firstnameInput = document.getElementById('firstname');
    const lastnameInput = document.getElementById('lastname');
    const emailInput = document.getElementById('guest_email');
    const phoneInput = document.getElementById('guest_phone');
    const confirmationInputs = form.querySelectorAll('input[name="confirmation_method"]');
    
    // ===== DESTINATION VALIDATION =====
    destinationInput.addEventListener('blur', function() {
        const isValid = validateDestination(this.value);
        if (!isValid && this.value.trim() !== '') {
            showError(this, 'Please enter a destination with at least 3 characters.');
        } else {
            clearError(this);
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
                showError(this, 'Please enter a valid phone number (10-15 digits).');
            } else {
                clearError(this);
            }
        }
    });
    
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
    
    // ===== DATES VALIDATION =====
    checkoutInput.addEventListener('change', function() {
        const validation = validateDates(checkinInput.value, this.value);
        if (!validation.valid) {
            showError(this, validation.message);
        } else {
            clearError(this);
            clearError(checkinInput);
        }
    });
    
    checkinInput.addEventListener('change', function() {
        if (checkoutInput.value) {
            const validation = validateDates(this.value, checkoutInput.value);
            if (!validation.valid) {
                showError(this, validation.message);
            } else {
                clearError(this);
                clearError(checkoutInput);
            }
        }
    });
    
    // ===== GUEST COUNT VALIDATION =====
    [adultsInput, childrenInput, roomsInput].forEach(function(input) {
        input.addEventListener('change', function() {
            const validation = validateGuests(adultsInput.value, childrenInput.value, roomsInput.value);
            if (!validation.valid) {
                showError(roomsInput, validation.message);
            } else {
                clearError(roomsInput);
                clearError(adultsInput);
                clearError(childrenInput);
            }
        });
    });
    
    // ===== FORM SUBMISSION VALIDATION =====
    form.addEventListener('submit', function(event) {
        let isFormValid = true;
        
        // Validate destination
        if (!validateDestination(destinationInput.value)) {
            showError(destinationInput, 'Please enter a valid destination (at least 3 characters).');
            isFormValid = false;
        } else {
            clearError(destinationInput);
        }
        
        // Validate dates
        const dateValidation = validateDates(checkinInput.value, checkoutInput.value);
        if (!dateValidation.valid) {
            showError(checkoutInput, dateValidation.message);
            isFormValid = false;
        } else {
            clearError(checkoutInput);
            clearError(checkinInput);
        }
        
        // Validate guest info
        if (!validateName(firstnameInput.value)) {
            showError(firstnameInput, 'First name is required and must contain only letters and spaces.');
            isFormValid = false;
        } else {
            clearError(firstnameInput);
        }
        
        if (!validateName(lastnameInput.value)) {
            showError(lastnameInput, 'Last name is required and must contain only letters and spaces.');
            isFormValid = false;
        } else {
            clearError(lastnameInput);
        }
        
        if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Please provide a valid email address.');
            isFormValid = false;
        } else {
            clearError(emailInput);
        }
        
        if (!validatePhone(phoneInput.value)) {
            showError(phoneInput, 'Please provide a valid phone number (10-15 digits).');
            isFormValid = false;
        } else {
            clearError(phoneInput);
        }
        
        // Validate guests
        const guestValidation = validateGuests(adultsInput.value, childrenInput.value, roomsInput.value);
        if (!guestValidation.valid) {
            showError(roomsInput, guestValidation.message);
            isFormValid = false;
        } else {
            clearError(roomsInput);
        }
        
        // Validate confirmation method
        const checkedMethods = form.querySelectorAll('input[name="confirmation_method"]:checked');
        if (checkedMethods.length === 0) {
            const confirmationContainer = form.querySelector('[role="group"]');
            showError(confirmationContainer, 'Please select a confirmation method.');
            isFormValid = false;
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
    const existingError = element.parentElement.querySelector('.booking-error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Add error styling
    element.classList.add('is-invalid');
    element.classList.remove('is-valid');
    
    // Create and display error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'booking-error-message text-danger small mt-1';
    errorDiv.textContent = message;
    element.parentElement.appendChild(errorDiv);
}

/**
 * Clear error message from a form field
 */
function clearError(element) {
    if (!element) return;
    
    element.classList.remove('is-invalid');
    
    const existingError = element.parentElement.querySelector('.booking-error-message');
    if (existingError) {
        existingError.remove();
    }
}
