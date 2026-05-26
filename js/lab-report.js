// Message display function
function showMessage(message) {
    const messageBox = document.createElement('div');
    messageBox.className = 'message-box';
    messageBox.innerText = message;
    document.body.appendChild(messageBox);

    // Remove message after 3 seconds
    setTimeout(() => {
        messageBox.remove();
    }, 3000);
}

// Attach event listener to download buttons
document.querySelectorAll('.download-btn').forEach(button => {
    button.addEventListener('click', () => {
        showMessage('Downloaded Successfully!');
    });
});
    document.querySelectorAll('.print-btn').forEach(button => {
    button.addEventListener('click', () => {
        showMessage('Print Successfully!');
    });
});