import Like from './like';

document.addEventListener('DOMContentLoaded', () => {
    console.log('Webpack Encore is working !');
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    if (likeElements) {
        new Like(likeElements);
    }
});
