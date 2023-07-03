import axios from 'axios';

export default class Like {
  constructor(likeElements) {
    this.likeElements = likeElements;

    if (this.likeElements) {
      this.init();
    }
  }

  init() {
    this.likeElements.map(element => {
      element.addEventListener('click', this.onClick)
    })
  }

  onClick(event) {
    //console.log(event);
    event.preventDefault();
    const url = this.href;

    axios.get(url).then(res => {
      console.log(res, this);
      const nb = res.data.nbLike;
      const span = this.querySelector('span.fs-sm.fw-normal');

      this.dataset.nb = nb;
      span.innerHTML = nb + ' I like';

      const thumbsUpLike = this.querySelector('i.ai-like');
      const thumbsUpDislike = this.querySelector('i.ai-dislike');

      thumbsUpLike.classList.toggle('d-none');
      thumbsUpDislike.classList.toggle('d-none');
    })
  }
}