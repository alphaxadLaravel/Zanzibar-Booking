function openWidget() {
    var widgetPopup = document.getElementById('widgetPopup');
    widgetPopup.style.display = 'block';
    // Trigger the animation after a small delay to ensure display is set
    setTimeout(function() {
        widgetPopup.classList.add('show');
    }, 10);
}

function closeWidget() {
    var widgetPopup = document.getElementById('widgetPopup');
    widgetPopup.classList.remove('show');
    // Hide the element after the transition completes
    setTimeout(function() {
        widgetPopup.style.display = 'none';
    }, 300);
}