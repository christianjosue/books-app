const welcome = document.querySelector('.welcome');
const navbar = document.querySelector('.navbar');

const resizeHeight = () => {
    const height = window.innerHeight - navbar.clientHeight;
    welcome.style.height = `${height}px`;
}

window.onresize = resizeHeight;
window.onload = resizeHeight;