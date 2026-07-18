/**
 * Client-side Form Validation
 * Real-time validation with visual feedback
 */

(function() {
    'use strict';

    // Auto-validate all forms with 'needs-validation' class
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.needs-validation');
        
        forms.forEach(function(form) {
            // Real-time validation on input
            form.querySelectorAll('input, select, textarea').forEach(function(input) {
                input.addEventListener('input', function() {
                    validateField(this);
                });
                
                input.addEventListener('blur', function() {
                    validateField(this);
                });
            });
            
            // On submit
            form.addEventListener('submit', function(event) {
                let isValid = true;
                
                form.querySelectorAll('input[required], select[required], textarea[required]').forEach(function(input) {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    event.preventDefault();
                    event.stopPropagation();
                    showToast('Please fix the errors in the form.', 'warning');
                }
                
                form.classList.add('was-validated');
            });
        });
    });

    function validateField(field) {
        let isValid = true;
        const value = field.value.trim();
        
        // Required check
        if (field.hasAttribute('required') && !value) {
            setFieldError(field, 'This field is required.');
            return false;
        }
        
        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                setFieldError(field, 'Please enter a valid email address.');
                return false;
            }
        }
        
        // Phone validation
        if (field.type === 'tel' && value) {
            const phoneRegex = /^[0-9+\-\s()]{7,20}$/;
            if (!phoneRegex.test(value)) {
                setFieldError(field, 'Please enter a valid phone number.');
                return false;
            }
        }
        
        // Min length
        if (field.minLength > 0 && value.length < field.minLength) {
            setFieldError(field, `Minimum ${field.minLength} characters required.`);
            return false;
        }
        
        // Password strength
        if (field.dataset.validatePassword && value) {
            if (value.length < 6) {
                setFieldError(field, 'Password must be at least 6 characters.');
                return false;
            }
        }
        
        // Password confirm match
        if (field.dataset.matchPassword) {
            const matchField = document.getElementById(field.dataset.matchPassword);
            if (matchField && value !== matchField.value) {
                setFieldError(field, 'Passwords do not match.');
                return false;
            }
        }
        
        // Clear error if valid
        if (value || !field.hasAttribute('required')) {
            clearFieldError(field);
        }
        
        return isValid;
    }

    function setFieldError(field, message) {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        let feedback = field.parentElement.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentElement.appendChild(feedback);
        }
        feedback.textContent = message;
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    }

    // Password visibility toggle
    document.addEventListener('click', function(e) {
        if (e.target.closest('.password-toggle')) {
            const toggle = e.target.closest('.password-toggle');
            const input = toggle.closest('.input-group').querySelector('input');
            const icon = toggle.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    });

    // Password strength meter
    window.updatePasswordStrength = function(input) {
        const meter = document.getElementById('passwordStrength');
        if (!meter) return;
        
        const val = input.value;
        let strength = 0;
        
        if (val.length >= 6) strength++;
        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;
        
        const colors = ['', 'bg-danger', 'bg-warning', 'bg-info', 'bg-primary', 'bg-success'];
        const labels = ['', 'Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'];
        const widths = ['0%', '20%', '40%', '60%', '80%', '100%'];
        
        meter.style.width = widths[strength];
        meter.className = 'progress-bar ' + colors[strength];
        
        const label = document.getElementById('passwordStrengthLabel');
        if (label) label.textContent = labels[strength];
    };
})();
