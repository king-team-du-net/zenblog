import { Controller } from '@hotwired/stimulus';

/**
 * @class CollectionController
 */
export default class extends Controller {
	/**
	 * @type {string[]}
	 */
	static targets = ['items'];

	/**
	 * @type {[]}
	 */
	index = this.itemsTarget.childElementCount - 1;

	/**
	 * Delete media
	 * @param {Event} e
	 */
	delete(e) {
		e.currentTarget.closest('.media').remove();
		if (this.itemsTarget.querySelectorAll('.media').length === 0) {
			this.itemsTarget.querySelector('.alert').classList.remove('d-none');
		}
	}

	/**
	 * Add image
	 * @param {Event} e
	 */
	addImage(e) {
		e.preventDefault();
		this.add('image');
	}

	/**
	 * Add video
	 * @param {Event} e
	 */
	addVideo(e) {
		e.preventDefault();
		this.add('video');
	}

	/**
	 * Add media
	 * @param {string} type
	 */
	add(type) {
		if (this.itemsTarget.querySelectorAll('.media').length === 0) {
			this.itemsTarget.querySelector('.alert').classList.add('d-none');
		}
		this.index++;
		const mediaElement = document.createElement('div');
		mediaElement.classList.add('media', 'col-12', 'col-sm-6', 'col-lg-4');
		mediaElement.innerHTML = this.element.dataset.prototype.replace(
			/__name__/g,
			this.index,
		);
		mediaElement.querySelector(`.${type}`)
			.classList
			.remove('d-none')
		;
		mediaElement.querySelector(`.type`).value = type;
		this.itemsTarget.appendChild(mediaElement);
		mediaElement.scrollIntoView();
	}
}
