    // Display message if any
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('success') || urlParams.get('error');
    if (message) {
        const messageType = urlParams.get('success') ? 'alert-success' : 'alert-danger';
        const messageDiv = document.getElementById('message');
        if (messageDiv) {
            messageDiv.textContent = message;
            messageDiv.classList.add(messageType);
            messageDiv.classList.remove('hidden');

            // Hide the message after 3 seconds
            setTimeout(function() {
                messageDiv.classList.add('hidden');
            }, 3000);
        }
    }