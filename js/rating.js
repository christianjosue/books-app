const ratingContainer = document.querySelector('.rating');
const rating = document.getElementById('rating');
var list = document.getElementsByClassName('fa-star');

const setStars = () => {
    const fragment = document.createDocumentFragment();
    for (let i = 1; i < 6; i++) {
        const star = document.createElement('i');
        star.classList.add('fa-regular', 'fa-star');
        star.setAttribute('key', i);
        fragment.appendChild(star);
    }
    ratingContainer.appendChild(fragment);
    tickStars();
}

const getRating = () => {
    ratingContainer.addEventListener('click', (e) => {
        for (let i of list) {
            if (i == e.target) {
                rating.value = i.getAttribute('key');
            }
        }
        tickStars();
    });
}

const tickStars = () => {
    for (let star of list) {
        if (star.getAttribute('key') <= rating.value) {
            star.classList.add('fa-solid');
            star.classList.remove('fa-regular');
        } else {
            star.classList.add('fa-regular');
            star.classList.remove('fa-solid');
        }
    }
}

setStars();
getRating();