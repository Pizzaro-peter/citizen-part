// script.js

// Function to display notifications
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert ${type}`; // Apply success or error class
    notification.innerText = message;

    document.body.appendChild(notification);

    // Automatically remove the notification after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Form validation function
function validateForm(form) {
    let valid = true;
    const inputs = form.querySelectorAll('input, textarea, select');

    inputs.forEach(input => {
        if (!input.value) {
            valid = false;
            input.classList.add('error'); // Add error class for visual feedback
            input.placeholder = 'This field is required'; // Placeholder for feedback
        } else {
            input.classList.remove('error');
        }
    });

    return valid;
}

// Event listener for form submission
const forms = document.querySelectorAll('form');

forms.forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent default form submission

        if (validateForm(form)) {
            showNotification('Form submitted successfully!', 'success');
            form.reset(); // Reset the form on successful submission
        } else {
            showNotification('Please fill in all required fields.', 'error');
        }
    });
});

// Example: Adding event listeners for buttons
const buttons = document.querySelectorAll('button');

buttons.forEach(button => {
    button.addEventListener('click', (e) => {
        showNotification(`${button.innerText} button clicked!`, 'success');
    });
});
