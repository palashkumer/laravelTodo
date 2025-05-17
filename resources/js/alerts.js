document.addEventListener('DOMContentLoaded', function () {
    const alertBox = document.getElementById('success-alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = "opacity 0.5s ease";
            alertBox.style.opacity = "0";
            setTimeout(() => {
                alertBox.remove();
            }, 500);
        }, 3000);
    }
});
