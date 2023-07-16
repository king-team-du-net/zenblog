import { Controller } from '@hotwired/stimulus';
import { template } from 'lodash';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/fr';

/**
 * @class PostsController
 */
export default class extends Controller {
    /**
     * @type {string[]}
     */
    static targets = ['list', 'loadMore', 'template'];

    /**
     * @var {
     *      {
     *          page: int,
     *          pages: int,
     *          limit: int,
     *          total: int,
     *          count: int,
     *          _links: {
     *            self: {href: string},
     *            next?: {href: string}
     *          },
     *          _embedded: {
     *              posts: {
     *                title: string,
     *                slug: string,
     *                category: {name: string},
     *                author: {nickname: string},
     *                cover: {filename}
     *              }[]
     *          }
     *      }
     *  }
     */
    data = { _links: { next: { href: '/en/api/blogs' } } };

    /**
     * @type {boolean}
     */
    updatePostByOwnerOnly = parseInt(
        this.element.dataset.updatePostByOwnerOnly,
    ) === 1;

    /**
     * @type {boolean}
     */
    deletePostByOwnerOnly = parseInt(
        this.element.dataset.deletePostByOwnerOnly,
    ) === 1;

    /**
     * @type {number|null}
     */
    userId = this.element.dataset.userId !== null ?
        parseInt(this.element.dataset.userId) :
        null;

    /**
     * Connect
     */
    connect() {
        dayjs.extend(relativeTime).locale('fr-FR');
        this.template = template(this.templateTarget.innerHTML);
        this.next();
    }

    /**
     * Next page
     */
    next() {
        if (this.data._links.next) {
            fetch(this.data._links.next.href)
                .then((res) => res.json())
                .then((data) => this.data = data)
                .then(this.load.bind(this))
            ;
        }
    }

    /**
     * Load page
     */
    load() {
        this.data._embedded.posts.forEach((post) => {
            post.createdAt = dayjs(post.createdAt).fromNow();
            post.canBeUpdated = this.userId !== null &&
                (!this.updatePostByOwnerOnly || this.userId === post.user.id);
            post.canBeDeleted = this.userId !== null &&
                (!this.deletePostByOwnerOnly || this.userId === post.user.id);
            this.listTarget.innerHTML += this.template(post);
        });

        if (!this.data._links.next) {
            this.loadMoreTarget.remove();
        }
    }
}
