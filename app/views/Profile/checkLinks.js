document.addEventListener("DOMContentLoaded", () => {

    const navLinks = document.querySelectorAll('.nav-links a');

    const disableNavLinks = () => {
        navLinks.forEach(link => {
            if (link.href.includes('/profile') || link.href.includes('/select')) {
                link.classList.remove('disabled');
            } else {
                link.classList.add('disabled');
            }
        });
    };

    const enableNavLinks = () => {
        navLinks.forEach(link => {
            link.classList.remove('disabled');
        });
    };

    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    };

    const checkChildSelection = () => {
        const selectedChild = getCookie('childId');
        if (!selectedChild) {
            disableNavLinks();
        } else {
            enableNavLinks();
            console.log('Child selected');
        }
    };
    checkChildSelection();
});