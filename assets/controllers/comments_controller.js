import { Controller } from '@hotwired/stimulus';
import { template } from 'lodash';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/fr';
// eslint-disable-next-line max-len
import userCircle from '@fortawesome/fontawesome-free/svgs/solid/circle-user.svg';
import nl2br from 'nl2br';

/**
 * @class BlogCommentController
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
     *              comments: {
     *                createdAt: string,
     *                content: string,
     *                user: {nickname: string}
     *              }[]
     *          }
     *      }
     *  }
     */
    data = {
        _links: {
            next: {
                href: `/en/api/blogs/${this.element.dataset.postId}/comments`,
            },
        },
    };

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
        this.data._embedded.comments.forEach((comment) => {
            comment.createdAt = dayjs(comment.createdAt).fromNow();
            comment.content = nl2br(comment.content);
            comment.author.avatar = comment.author.avatar !== null ?
                `/uploads/user/${comment.author.avatar}` :
                userCircle;
            this.listTarget.innerHTML += this.template(comment);
        });

        if (!this.data._links.next) {
            this.loadMoreTarget.remove();
        }
    }
}
