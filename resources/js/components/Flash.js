export const Flash = (message) => {
    if (message) {
        let el = document.querySelector('main.py-4');
        let alert = document.createElement('div');
        alert.className = 'alert alert-success position-fixed';
        alert.style.right = '1rem';
        alert.style.bottom = '5rem';
        alert.innerHTML = `<strong>Success</strong> ${message}`;
        el.insertAdjacentElement('beforeend', alert);
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}
window.Flash = Flash;
