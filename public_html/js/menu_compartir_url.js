 // Define the SweetAlert mixin for the customized toast
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Function to copy the current URL to the clipboard
function copyLink() {
    const currentUrl = window.location.href;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(currentUrl).then(() => {
            Toast.fire({
                icon: 'success',
                title: '¡Enlace copiado!',
            });
        }).catch(err => {
            console.error('Error al copiar con la API de Clipboard:', err);
            fallbackCopyTextToClipboard(currentUrl);
        });
    } else {
        fallbackCopyTextToClipboard(currentUrl);
    }
}

// Fallback function for non-compatible browsers
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.top = 0;
    textArea.style.left = 0;
    textArea.style.opacity = 0;
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        document.execCommand('copy');
        Toast.fire({
            icon: 'success',
            title: '¡Enlace copiado!',
        });
    } catch (err) {
        console.error('Error al copiar con el método de respaldo:', err);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Lo siento, el copiado no es compatible con tu navegador. Por favor, copia el enlace manualmente.'
        });
    } finally {
        document.body.removeChild(textArea);
    }
}

// ----------------------------------------------------
// Social media sharing functions
// ----------------------------------------------------

const currentUrl = encodeURIComponent(window.location.href);

// Function to share on X (formerly Twitter)
function shareOnX() {
    const url = `https://x.com/intent/tweet?url=${currentUrl}`;
    window.open(url, '_blank');
}

// Function to share on Facebook
function shareOnFacebook() {
    const url = `https://www.facebook.com/sharer/sharer.php?u=${currentUrl}`;
    window.open(url, '_blank');
}

// Function to share on LinkedIn
function shareOnLinkedIn() {
    const url = `https://www.linkedin.com/sharing/share-offsite/?url=${currentUrl}`;
    window.open(url, '_blank');
}

// Function to share on WhatsApp
function shareOnWhatsApp() {
    // Check for mobile vs. desktop to use the correct URL format
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    let url;
    if (isMobile) {
        url = `whatsapp://send?text=${currentUrl}`;
    } else {
        url = `https://web.whatsapp.com/send?text=${currentUrl}`;
    }
    window.open(url, '_blank');
}

// Function to share via Gmail
function shareOnGmail() {
    const subject = encodeURIComponent('Te recomiendo esta página');
    const url = `mailto:?subject=${subject}&body=${currentUrl}`;
    window.open(url, '_blank');
}

// Function for the print button (if you need it)
function boton_descargar() {
    window.print();
}
 