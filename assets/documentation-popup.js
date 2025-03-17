// assets/documentation-popup.js
document.addEventListener('DOMContentLoaded', function() {
    // Attach event listener to the button
    var openButton = document.getElementById('soovex-open-documentation');
    if (openButton) {
        openButton.addEventListener('click', soovexOpenDocumentation);
    }

    // Attach event listener to the close button
    var closeButton = document.querySelector('.soovex-close-popup');
    if (closeButton) {
        closeButton.addEventListener('click', soovexCloseDocumentation);
    }

    // Close popup when clicking outside the content
    document.addEventListener('click', function(event) {
        var popup = document.getElementById('soovex-documentation-popup');
        if (event.target === popup) {
            soovexCloseDocumentation();
        }
    });
});

function soovexOpenDocumentation() {
    document.getElementById('soovex-documentation-popup').style.display = 'flex';
}

function soovexCloseDocumentation() {
    document.getElementById('soovex-documentation-popup').style.display = 'none';
}