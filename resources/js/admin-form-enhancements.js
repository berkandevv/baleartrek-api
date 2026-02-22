document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const message = form.dataset.confirm;
    if (!message) {
        return;
    }

    if (!window.confirm(message)) {
        event.preventDefault();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const editorElement = document.querySelector('[data-ckeditor="trek-description"]');
    if (!editorElement || typeof window.CKEDITOR === 'undefined') {
        return;
    }

    if (editorElement.dataset.ckeditorInitialized === '1') {
        return;
    }

    const { ClassicEditor, Essentials, Bold, Italic, Font, Paragraph, Undo } = window.CKEDITOR;

    ClassicEditor
        .create(editorElement, {
            licenseKey: editorElement.dataset.ckeditorLicenseKey || 'GPL',
            plugins: [Essentials, Bold, Italic, Font, Paragraph, Undo],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
            ],
        })
        .then(() => {
            editorElement.dataset.ckeditorInitialized = '1';
        })
        .catch((error) => {
            console.error(error);
        });
});
