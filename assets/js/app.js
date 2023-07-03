/**
 * ZenBloggy | Multipurpose Bootstrap 5 HTML Template
 * Copyright 2023 Dequidt Robert
 * Theme core scripts
 *
 * @author Dequidt Robert
 * @version 1.2.0
*/

// css & scss

import '../css/app.css';


// Template Main JS File

//require('./theme-like.js');
import './themes.js';

// Start the Stimulus application

import '../bootstrap.js';

// js

const $ = require('jquery');
global.$ = global.jQuery = $;

// Vendor JS Files & Mode Modules

import 'jquery-confirm/dist/jquery-confirm.min.js';
import 'select2/dist/js/select2.js';
import 'summernote/dist/summernote-bs5.min.js';

/* window.Translator = require('bazinga-translator'); */
import 'pace-progressbar';

import 'fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js';
import '../vendor/owlcarousel/owl.carousel.min.js';
import '../vendor/jssocials/jssocials.min.js';
import '../vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js';
import '../vendor/morphext/morphext.min.js';
import 'waypoints/lib/jquery.waypoints.min.js';

import '../vendor/jquery-countdown/jquery.countdown.min.js';
import '../vendor/counter-up/jquery.counterup.min.js';
import '../vendor/circle-progress/circle-progress.min.js';
import './translations/config.js';
import './translations/en.js';
import './translations/fr.js';

global.PhotoSwipe = require('../vendor/photoswipe/photoswipe.min.js');
global.PhotoSwipeUI_Default = require('../vendor/photoswipe/photoswipe-ui-default.min.js');
import '../vendor/jq-photoswipe/jqPhotoSwipe.min.js';

import '../vendor/ninsuo-symfony-collection/jquery.collection.js';
import '../vendor/pugxautocompleter/autocompleter-select2.js';

import noUiSlider from 'nouislider/dist/nouislider.min.js';
import wNumb from 'wnumb/wNumb.js';

import '@adactive/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js';
import 'jquery-datetimepicker/build/jquery.datetimepicker.full.min.js';

import Readmore from 'readmore-js/readmore.js';
import Blazy from 'blazy/blazy.min.js';
import Ouical from 'add-to-calendar-buttons/ouical.min.js';
import Bloodhound from 'typeahead.js/dist/bloodhound.min.js';
import 'typeahead.js/dist/typeahead.jquery.min.js';

import Handlebars from 'handlebars/dist/handlebars.min.js';

import moment from 'moment/moment.js';
import 'moment-timezone/builds/moment-timezone-with-data-2012-2022.min.js';
import KnpPaginatorAjax from '../vendor/knppaginator-ajax/knppaginator-ajax.js';
import 'jquery-parallax.js/parallax.min.js';
import caleandar from '../vendor/caleandar.js/caleandar.js';
import bootbox from '../vendor/bootboxjs/bootbox.min.js';
import cookieBar from '../vendor/jquery.cookieBar/jquery.cookieBar.min.js';

// Declares utility functions

(function () {
    'use strict';

    // Sets the language for the date pickers
    $.datetimepicker.setLocale($("html").attr("lang"));

    // Retreives a parameter value passed in the url
    function getURLParameter(paramatername) {
        return decodeURIComponent((new RegExp('[?|&]' + paramatername + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null;
    }

    // Retreives an array parameter value passed in the url
    function getURLArrayParameter(paramatername) {
        paramatername = encodeURIComponent(paramatername);
        var match = window.location.href.match(/[^=&?]+\s*=\s*[^&#]*/g);
        var obj = {};
        for (var i = match.length; i--; ) {
            var spl = match[i].split("=");
            var name = spl[0].replace("[]", "");
            var value = spl[1];
            obj[name] = obj[name] || [];
            obj[name].push(value);
        }
        if (obj[paramatername] !== undefined) {
            return obj[paramatername];
        } else {
            return null;
        }
    }

    // Checks if an email address is valid
    function isEmailValid(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // Animates an element using the animate.css library
    /*
    function animateCSS(element, animationName, callback) {
        const node = document.querySelector(element)
        node.classList.add('animated', animationName)

        function handleAnimationEnd() {
            node.classList.remove('animated', animationName)
            node.removeEventListener('animationend', handleAnimationEnd)

            if (typeof callback === 'function')
                callback()
        }

        node.addEventListener('animationend', handleAnimationEnd)
    }
    */

    global.getURLParameter = getURLParameter;
    global.getURLArrayParameter = getURLArrayParameter;

    // Initializes bootstrap components
    $('[data-toggle="popover"], .has-popover').popover();
    $('[data-toggle="tooltip"], .has-tooltip').tooltip({
        trigger: 'hover'
    });

    // Sets input file name
    $(document).on('change', '.custom-file-input', function (e) {
        $(e.target).siblings('.custom-file-label').html(e.target.files[0].name);
    });

    // Resets forms
    $('button[type="reset"]').on('click', function (e) {
        $(this).closest('form').find('input[type="text"], input:password, input:file, textarea').val('');
        $(this).closest('form').find('input[type="text"], input:password, input:file, textarea').attr('value', '');
        $(this).closest('form').find('.autocomplete').each(function () {
            $('#fake_' + $(this).attr('id')).select2('data', {});
        });
        $(this).closest('form').find('select').val("all").trigger('change');
        $(this).closest('form').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    });

    // Fixes disabled links with a tooltip
    $("a.btn.disabled.has-tooltip").click(function (e) {
        e.preventDefault();
        return false;
    });

    // Prevents closing from click inside dropdown
    $(document).on('click', '.dropdown-menu', function (e) {
        e.stopPropagation();
    });
    if ($(window).width() > 768) {
        $(window).scroll(function () {

            // Fixes menu on scroll for desktop
            if ($('.navbar-landing').length) {
                if ($(this).scrollTop() > 125) {
                    $('.navbar-landing').addClass("fixed-top");
                } else {
                    $('.navbar-landing').removeClass("fixed-top");
                }
            }

            // Sticky sidebar padding
            if ($('.sticky-sidebar').length) {
                if ($('.sticky-sidebar').offset().top <= $(this).scrollTop()) {
                    $('.sticky-sidebar').addClass('pt-lg-6');
                } else {
                    $('.sticky-sidebar').removeClass('pt-lg-6');
                }
            }
        });
    }

    // Closes the Responsive Menu on Menu Item Click
    $('.navbar-collapse ul li a.page-scroll').click(function () {
        $('.navbar-toggler:visible').click();
    });

    // Handles top search form
    $(".header-main form.search-wrap").submit(function (e) {
        if ($(this).find(".top-search").val() == "") {
            e.preventDefault();
        }
    });
    $(".header-main form.search-wrap .input-icon > i").click(function () {
        $(".header-main form.search-wrap").submit();
    });

    // Jssocials share
    if ($('.sharer').length) {
        $(".sharer").jsSocials({
            showLabel: false,
            showCount: false,
            shares: [{
                    renderer: function () {
                        var $result = $("<div>");
                        var script = document.createElement("script");
                        script.text = "(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = \"//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.3\"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));";
                        $result.append(script);
                        $("<div>").addClass("fb-share-button")
                                .attr("data-layout", "button_count")
                                .appendTo($result);
                        return $result;
                    }
                }, {
                    renderer: function () {
                        var $result = $("<div>");
                        var script = document.createElement("script");
                        script.src = "https://apis.google.com/js/platform.js";
                        $result.append(script);
                        $("<div>").addClass("g-plus")
                                .attr({
                                    "data-action": "share",
                                    "data-annotation": "bubble"
                                })
                                .appendTo($result);
                        return $result;
                    }
                }, {
                    renderer: function () {
                        var $result = $("<div>");
                        var script = document.createElement("script");
                        script.text = "window.twttr=(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],t=window.twttr||{};if(d.getElementById(id))return t;js=d.createElement(s);js.id=id;js.src=\"https://platform.twitter.com/widgets.js\";fjs.parentNode.insertBefore(js,fjs);t._e=[];t.ready=function(f){t._e.push(f);};return t;}(document,\"script\",\"twitter-wjs\"));";
                        $result.append(script);
                        $("<a>").addClass("twitter-share-button")
                                .text("Tweet")
                                .attr("href", "https://twitter.com/share")
                                .appendTo($result);
                        return $result;
                    }
                }, {
                    renderer: function () {
                        var $result = $("<div>");
                        var script = document.createElement("script");
                        script.src = "//platform.linkedin.com/in.js";
                        $result.append(script);
                        $("<script>").attr({type: "IN/Share", "data-counter": "right"})
                                .appendTo($result);
                        return $result;
                    }
                }]
        });
    }

    // Bootstrap Touchspin
    if ($('.touchspin-integer').length) {
        $(".touchspin-integer").TouchSpin({
            verticalbuttons: true,
            min: $(this).data('min'),
            max: $(this).data('max')
        });
    }
    if ($('.touchspin-decimal').length) {
        $(".touchspin-decimal").TouchSpin({
            verticalbuttons: true,
            min: $(this).data('min'),
            max: $(this).data('max'),
            decimals: 2,
            step: 0.01,
            prefix: $('body').data('currency-symbol')
        });
    }

    // Implements on hover trigger for bootstrap 4/5 dropdowns
    function toggleDropdown(e) {
        const _d = $(e.target).closest('.dropdown'),
            _m = $('.dropdown-menu', _d)
        ;
        setTimeout(function () {
            const shouldOpen = e.type !== 'click' && _d.is(':hover');
            _m.toggleClass('show', shouldOpen);
            _d.toggleClass('show', shouldOpen);
            $('[data-toggle="dropdown"]', _d).attr('aria-expanded', shouldOpen);
        }, e.type === 'mouseleave' ? 25 : 0);
    }

    $('body')
        .on('mouseenter mouseleave', '.dropdown-hover', toggleDropdown)
        .on('click', '.dropdown-menu a', toggleDropdown)
    ;

    // Initializes word rotator
    if ($('.rotate-words').length) {
        $('.rotate-words').Morphext({
            animation: "fadeIn",
            separator: "|",
            speed: 2000,
        });
    }

    // Initializes checkout timer
    if ($('.checkout-timer').length) {
        $('.checkout-timer').each(function () {
            var $checkouttimer = $(this);
            var $checkouttimerto = moment().add($checkouttimer.data('seconds-left'), 'seconds').format('YYYY-MM-DD HH:mm:ss');
            $checkouttimer.countdown($checkouttimerto, function (event) {

                if (event.strftime('%M') < 2) {
                    $('.checkout-timer-wrapper > .alert').removeClass('alert-warning').addClass('alert-danger');
                }

                if (event.strftime('%M') < 1) {
                    animateCSS('.checkout-timer-wrapper', 'flash');
                }

                $(this).html(event.strftime('%M:%S'));
            }).on('finish.countdown', function () {
                $('#checkout_submit').prop('disabled', true);
                $.confirm({
                    title: Translator.trans("Time's up", {}, 'javascript'),
                    content: Translator.trans("The tickets have been released", {}, 'javascript'),
                    theme: 'supervan',
                    buttons: {
                        confirm: {
                            text: Translator.trans('Return to cart', {}, 'javascript'),
                            btnClass: 'btn-primary btn-sm',
                            keys: ['enter'],
                            action: function () {
                                location.href = location.protocol + '//' + location.host + Routing.generate('dashboard_attendee_cart', {}, false);
                            }
                        }
                    }
                });
            });
        });
    }

    // Initializes Event start countdown
    if ($('.countdown').length) {
        $('.countdown').each(function () {
            var $thiscountdown = $(this);
            var $countdownto = $thiscountdown.data('count-down-to');
            $thiscountdown.countdown($countdownto, function (event) {
                $(this).html(event.strftime(''
                        + '<span>%w</span> ' + Translator.trans('weeks', {}, 'javascript') + ' '
                        + '<span>%d</span> ' + Translator.trans('days', {}, 'javascript') + ' '
                        + '<span>%H</span> ' + Translator.trans('hr', {}, 'javascript') + ' '
                        + '<span>%M</span> ' + Translator.trans('min', {}, 'javascript') + ' '
                        + '<span>%S</span> ' + Translator.trans('sec', {}, 'javascript') + ' '));
            });
        });
    }

    // Initializes counters
    if ($('.counter').length) {
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    }

    // Initializes circular progress
    if ($('.chart-circle').length) {
        $('.chart-circle').each(function () {
            let $this = $(this);
            $this.circleProgress({
                fill: {
                    gradient: ["red", "green"]
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: '#F4F4F4',
                lineCap: 'round'
            });
        });
    }

    // Focuses on form errors
    var offsettop = 100;
    if ($(window).width() < 992) {
        offsettop = 170;
    }
    if ($('.form-control.is-invalid').length > 0) {
        $('html, body').animate({scrollTop: $($('.form-control.is-invalid')[0]).offset().top - offsettop}, 0);
    }

    // Initializes Jquery Confirm
    $(document).on('click', '.requires-confirmation', function (e) {
        e.preventDefault();
        var $thisElement = $(this);

        if (typeof $(this).attr('href') !== typeof undefined && $(this).attr('href') !== false) {
            if ($(this).attr('href').indexOf('/delete') >= 0 || $(this).attr('href').indexOf('/disable') >= 0 || $(this).attr('href').indexOf('/featured') >= 0 || $(this).attr('href').indexOf('/notfeatured') >= 0 || $(this).attr('href').indexOf('/hide') >= 0) {
                return false;
            }
        }
        if (typeof $(this).attr('data-target') !== typeof undefined && $(this).attr('data-target') !== false) {
            if ($(this).attr('data-target').indexOf('/delete') >= 0 || $(this).attr('data-target').indexOf('/cancel') >= 0) {
                return false;
            }
        }

        var $confirmDialog = $.confirm({
            title: Translator.trans('Confirmation required', {}, 'javascript'),
            content: $thisElement.data('confirmation-text'),
            buttons: {
                confirm: {
                    text: Translator.trans('Confirm', {}, 'javascript'),
                    btnClass: 'btn-primary btn-sm',
                    keys: ['enter'],
                    action: function () {
                        location.href = $thisElement.data('target');
                    }
                },
                cancel: {
                    text: Translator.trans('Cancel', {}, 'javascript'),
                    btnClass: 'btn-default btn-sm',
                    keys: ['esc'],
                    action: function () {
                        $confirmDialog.close();
                    }
                }
            }
        });
    });

    $(document).on('click', '.ajax-loading', function (e) {
        e.preventDefault();
        var $thisElement = $(this);
        var $confirmDialog = $.confirm({
            title: $thisElement.data('title'),
            columnClass: 'col-12',
            backgroundDismiss: true,
            content: function () {
                var self = this;
                return $.ajax({
                    url: $thisElement.data('url'),
                    dataType: 'html',
                    method: 'get'
                }).done(function (response) {
                    self.setContent(response);
                }).fail(function () {
                    self.setContent(Translator.trans('An error has occured', {}, 'javascript'));
                });
            },
            onContentReady: function () {
                if ($('.fancybox').length) {
                    $(".fancybox").jqPhotoSwipe();
                }
                if ($('.gallery').length) {
                    $(".gallery a").jqPhotoSwipe({
                        forceSingleGallery: true
                    });
                }
            },
            buttons: {
                close: {
                    text: Translator.trans('Close', {}, 'javascript'),
                    btnClass: 'btn-default btn-sm',
                    keys: ['esc'],
                    action: function () {
                        $confirmDialog.close();
                    }
                }
            }
        });
    });

    // Initializes Font Awesome picker
    if ($('.icon-picker').length) {
        $('.icon-picker').iconpicker({
            animation: false,
            inputSearch: true
        });
    }

    // Initializes select2
    $('.select2').each(function () {
        if ($(this).data("sort-options") == "1") {
            $(this).select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: Translator.trans('Select an option', {}, 'javascript'),
                sortResults: data => data.sort((a, b) => a.text.localeCompare(b.text)),
            });
        } else {
            $(this).select2({
                theme: 'bootstrap4',
                allowClear: true,
                placeholder: Translator.trans('Select an option', {}, 'javascript'),
            });
        }
    });

    // Sortable select
    if ($('#sortable-select').length) {
        $('#sortable-select option').each(function () {
            if ($(this).data('direction') == getURLParameter('direction') && $(this).data('criteria') == getURLParameter('sort'))
            {
                $(this).prop('selected', true).trigger('change');
                $(this).prop('disabled', true).trigger('change');
                $('#slug').val(getURLParameter('sort')).change();
            }
        });
    }
    $('#sortable-select').on('select2-selecting', function (e) {
        window.location = e.val;
    });

    // Initializes Photoswipe gallery
    if ($('.fancybox').length) {
        $(".fancybox").jqPhotoSwipe();
    }
    if ($('.gallery').length) {
        $(".gallery a").jqPhotoSwipe({
            forceSingleGallery: true
        });
    }

    // Initializes wysiwyg editor
    if ($('.wysiwyg').length) {
        $('.wysiwyg').summernote({
            height: 500,
        });
    }

    // Initializes form collection plugin
    if ($('.form-collection:not(.manual-init)').length) {
        $('.form-collection:not(.manual-init)').each(function () {
            $(this).collection({
                add_at_the_end: true,
                allow_add: true,
                allow_remove: true,
                allow_up: true,
                allow_down: true,
                add: '<a href="#" class="mr-3 btn btn-outline-dark btn-sm"><i class="fas fa-plus-square"></i> ' + Translator.trans('Add', {}, 'javascript') + '</a>',
                remove: '<a href="#" class="mr-3 btn btn-outline-dark btn-sm"><i class="fas fa-minus-square"></i> ' + Translator.trans('Remove', {}, 'javascript') + '</a>',
                up: '<a href="#" class="mr-3 btn btn-outline-dark btn-sm"><i class="fas fa-caret-square-up"></i> ' + Translator.trans('Move up', {}, 'javascript') + '</a>',
                down: '<a href="#" class="mr-3 btn btn-outline-dark btn-sm"><i class="fas fa-caret-square-down"></i> ' + Translator.trans('Move down', {}, 'javascript') + '</a>',
                position_field_selector: '.form-collection-position'
            });
        });
    }

    // Initializes range slider
    if ($('.range-slider').length) {
        $('.range-slider').each(function () {
            var $thisRangerSlider = $(this);
            noUiSlider.create($thisRangerSlider[0], {
                start: [$thisRangerSlider.data('start-left'), $thisRangerSlider.data('start-right')],
                direction: $('html').attr('lang') == 'ar' ? 'rtl' : 'ltr',
                connect: true,
                range: {
                    'min': $thisRangerSlider.data('min'),
                    'max': $thisRangerSlider.data('max')
                },
                format: wNumb({
                    decimals: 0
                })
            }
            );
            $thisRangerSlider[0].noUiSlider.on('update', function (values, handle) {
                $thisRangerSlider.siblings('.ranger-slider-inputs').find('.range-slider-min-input').val(values[0]);
                $thisRangerSlider.siblings('.ranger-slider-inputs').find('.range-slider-max-input').val(values[1]);
            }
            );
            $thisRangerSlider.siblings('.ranger-slider-inputs').find('.range-slider-min-input').on('change', function () {
                $thisRangerSlider[0].noUiSlider.set([this.value, null]);
            }
            );
            $thisRangerSlider.siblings('.ranger-slider-inputs').find('.range-slider-max-input').on('change', function () {
                $thisRangerSlider[0].noUiSlider.set([null, this.value]);
            }
            );
        });
    }

    // Select2 autocomplete
    if ($('.autocomplete').length) {
        $('.autocomplete').each(function () {
            var $thisAutocomplete = $(this);
            $thisAutocomplete.autocompleter({
                url_list: $thisAutocomplete.data('url-list'),
                otherOptions: {
                    minimumInputLength: typeof $thisAutocomplete.data("minimum-input-length") !== 'undefined' ? $thisAutocomplete.data("minimum-input-length") : 3,
                    formatNoMatches: Translator.trans('No results found', {}, 'javascript'),
                    formatSearching: Translator.trans('Searching ...', {}, 'javascript'),
                    formatInputTooShort: Translator.trans('Insert at least 3 characters', {}, 'javascript')
                }
            });
        });
    }

    // Tags input
    if ($(".tags-input").length) {
        $(".tags-input").each(function () {
            $(this).tagsinput({
                tagClass: 'badge bg-primary'
            });
        });
        $('.bootstrap-tagsinput').each(function () {
            $(this).addClass('form-control');
        });
    }

    // Datetimepickers
    if ($('.datetimepicker').length) {
        $('.datetimepicker').each(function () {
            $(this).datetimepicker({
                format: 'Y-m-d H:i'
            });
        });
    }

    if ($('.datepicker').length) {
        $('.datepicker').each(function () {
            $(this).datetimepicker({
                format: 'Y-m-d',
                timepicker: false
            });
        });
    }

    // Post favorites ajax add and remove
    $(document).on("click", ".post-favorites-add, .post-favorites-remove", function () {
        var $thisButton = $(this);
        if ($thisButton.attr("data-action-done") == "1") {
            $thisButton.unbind("click");
            return false;
        }
        $.ajax({
            type: "GET",
            url: $thisButton.data('target'),
            beforeSend: function () {
                $thisButton.attr("data-action-done", "1");
                $thisButton.html("<i class='fas fa-spinner fa-spin'></i>");
            },
            success: function (response) {
                if (response.hasOwnProperty('success')) {
                    if ($thisButton.hasClass('event-favorites-add')) {
                        $thisButton.html('<i class="fas fa-heart"></i>');
                    } else {
                        $thisButton.html('<i class="far fa-heart"></i>');
                    }
                    $thisButton.attr("title", response.success).tooltip("_fixTitle");
                    showStackBarTop('success', '', response.success);
                } else if (response.hasOwnProperty('error')) {
                    $thisButton.html('<i class="far fa-heart"></i>');
                    $thisButton.attr("title", response.error).tooltip("_fixTitle");
                    showStackBarTop('error', '', response.error);
                } else {
                    $thisButton.html('<i class="far fa-heart"></i>');
                    $thisButton.attr("title", Translator.trans('An error has occured', {}, 'javascript')).tooltip("_fixTitle");
                    showStackBarTop('error', '', Translator.trans('An error has occured', {}, 'javascript'));
                }
            }
        });
    });

    // Lazy loading images
    var bLazy = new Blazy({
        selector: '.img-lazy-load',
        success: function (ele) {
            $(ele).find('.loader').remove();
        },
        error: function (ele, msg) {
            $(ele).find('.loader').remove();
        }
    });

    //  Owl Carousel
    if ($('.owl-init').length) {
        $(".owl-init").each(function () {
            var owlcarousel = $(this);
            owlcarousel.owlCarousel({
                rtl: ($("html").attr("lang") == "ar" ? true : false),
                loop: owlcarousel.data('loop'),
                margin: owlcarousel.data('margin'),
                nav: owlcarousel.data('nav'),
                dots: owlcarousel.data('dots'),
                autoplay: owlcarousel.data('autoplay'),
                items: owlcarousel.data('items'),
                navText: ["<i class='fa fa-chevron-left'></i>", "<i class='fa fa-chevron-right'></i>"],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: owlcarousel.data('items')
                    }
                }
            });
            $('.' + owlcarousel.data('custom-nav') + '.owl-custom-next').click(function () {
                $('#' + owlcarousel.attr('id')).trigger('next.owl.carousel');
            });
            $('.' + owlcarousel.data('custom-nav') + '.owl-custom-prev').click(function () {
                $('#' + owlcarousel.attr('id')).trigger('prev.owl.carousel');
            }
            );
            // TODO ventic: recheck this
            owlcarousel.on('changed.owl.carousel', bLazy.revalidate);
        });
    }

    // Readmore
    if ($('.readmore').length) {
        $('.readmore').each(function () {
            new Readmore($(this), {
                speed: 200,
                lessLink: '<a href="#">' + Translator.trans('Close', {}, 'javascript') + '</a>',
                moreLink: '<a href="#">' + Translator.trans('Read more', {}, 'javascript') + '</a>',
                collapsedHeight: parseInt($(this).data('collapsed-height')),
                heightMargin: parseInt($(this).data('height-margin')),
                embedCSS: true,
                afterToggle: function (trigger, element, expanded) {
                    if (!expanded) { // The "Close" link was clicked
                        $(element).removeClass("expanded");
                    } else {
                        $(element).addClass("expanded");
                    }
                },
                blockProcessed: function (element, collapsable) {
                    if (collapsable) {
                        $(element).addClass("collapsable");
                    }
                }
            });
        });
    }

    // Add to calendar
    if ($('#add-to-calendar').length) {
        var myCalendar = Ouical.createCalendar({
            data: {
                title: $('#add-to-calendar-link').data('title'),
                start: $('#add-to-calendar-link').data('start') ? new Date($('#add-to-calendar-link').data('start')) : '',
                duration: '',
                end: $('#add-to-calendar-link').data('end') ? new Date($('#add-to-calendar-link').data('end')) : '',
                address: $('#add-to-calendar-link').data('address') ? $('#add-to-calendar-link').data('address') : '',
                description: $('#add-to-calendar-link').data('description') + '...'
            }
        });
        document.querySelector('#add-to-calendar').appendChild(myCalendar);
    }

    // Follow / unfollow post
    /*
    $(document).on("click", ".post-follow, .post-unfollow", function () {
        var $thisButton = $(this);
        if ($thisButton.attr("data-action-done") == "1") {
            $thisButton.unbind("click");
            return false;
        }
        $.ajax({
            type: "GET",
            url: $thisButton.data('target'),
            beforeSend: function () {
                $thisButton.attr("data-action-done", "1");
                $thisButton.html("<i class='fas fa-spinner fa-spin'></i>");
            },
            success: function (response) {
                if (response.hasOwnProperty('success')) {
                    if ($thisButton.hasClass('post-follow')) {
                        $thisButton.html('<i class="fas fa-folder-plus"></i>');
                    } else {
                        $thisButton.html('<i class="fas fa-folder-minus"></i>');
                    }
                    $thisButton.attr("title", response.success).tooltip("_fixTitle");
                    showStackBarTop('success', '', response.success);
                } else if (response.hasOwnProperty('error')) {
                    $thisButton.html('<i class="fas fa-folder"></i>');
                    $thisButton.attr("title", response.error).tooltip("_fixTitle");
                    showStackBarTop('error', '', response.error);
                } else {
                    $thisButton.html('<i class="fas fa-folder"></i>');
                    $thisButton.attr("title", Translator.trans('An error has occured', {}, 'javascript')).tooltip("_fixTitle");
                    showStackBarTop('error', '', Translator.trans('An error has occured', {}, 'javascript'));
                }
            }
        });
    });
    */

    //Newsletter subscribe
    $(document).on("click", "#newsletter-subscribe", function () {
        var $thisButton = $(this);
        if ($thisButton.attr("data-action-done") == "1") {
            $thisButton.unbind("click");
            return false;
        }

        if (!isEmailValid($("#newsletter-email").val())) {
            showStackBarTop('error', '', Translator.trans('Make sure to enter a valid email address', {}, 'javascript'));
            return false;
        }

        $.ajax({
            type: "POST",
            url: $thisButton.data('target'),
            data: {email: $("#newsletter-email").val()},
            beforeSend: function () {
                $thisButton.attr("data-action-done", "1");
                $thisButton.html("<i class='fas fa-spinner fa-spin'></i>");
            },
            success: function (response) {
                if (response.hasOwnProperty('success')) {
                    $thisButton.html('<i class="fas fa-envelope-open-text"></i>');
                    $thisButton.attr("title", response.success).tooltip("_fixTitle");
                    showStackBarTop('success', '', response.success);
                } else if (response.hasOwnProperty('error')) {
                    $thisButton.html('<i class="fas fa-exclamation-circle"></i>');
                    $thisButton.attr("title", response.error).tooltip("_fixTitle");
                    showStackBarTop('error', '', response.error);
                } else {
                    $thisButton.html('<i class="fas fa-exclamation-circle"></i>');
                    $thisButton.attr("title", Translator.trans('An error has occured', {}, 'javascript')).tooltip("_fixTitle");
                    showStackBarTop('error', '', Translator.trans('An error has occured', {}, 'javascript'));
                }
            }
        });
    });

    // Initializes Bloodhound Search Engine
    var eventsForTopSearch = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            /*url: location.protocol + '//' + location.host + Routing.generate('get_events', {'_locale': $('html').attr('lang')}, false) + "?q=%QUERY",*/
            wildcard: '%QUERY'
        },
    });

    $('.top-search').typeahead({
        hint: false,
        highlight: true,
        minLength: 0,
        limit: 3
    }, {
        name: 'top-search',
        display: 'text',
        source: eventsForTopSearch,
        templates: {
            empty: [
                '<div class="dropdown-menu show">',
                Translator.trans('No results found', {}, 'javascript'),
                '</div>'
            ].join('\n'),
            suggestion: Handlebars.compile($("#top-search-result-template").html())
        }
    });

    // Initializes Ajax Pagination
    if ($('.ajax-pagination').length) {
        new KnpPaginatorAjax().init({
            'loadMoreText': Translator.trans('Load more', {}, 'javascript'),
            'loadingText': Translator.trans('Loading...', {}, 'javascript'),
            'elementsSelector': '.ajax-pagination',
            'paginationSelector': 'ul.pagination'
        });
    }

    // Dashboard sidenav
    function openDashboardSideNav() {
        document.getElementById("dashboard-sidenav").style.left = "0";
        document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
    }
    function closeDashboardSideNav() {
        document.getElementById("dashboard-sidenav").style.left = "-250px";
        document.body.style.backgroundColor = "white";
    }
    global.openDashboardSideNav = openDashboardSideNav;
    global.closeDashboardSideNav = closeDashboardSideNav;

    // Juqery Cookie Bar
    if (typeof $("body").data('cookie-bar-page-link') !== 'undefined') {
        $.cookieBar('addTranslation', 'fr', {
            message: 'Nous utilisons des cookies pour fournir nos services. En utilisant ce site Web, vous acceptez cela.',
            acceptText: 'D\'accord',
            infoText: 'Plus d\'information'
        });
        $.cookieBar('addTranslation', 'es', {
            message: 'Usamos cookies para brindar nuestros servicios. Al utilizar este sitio web, acepta esto.',
            acceptText: 'Bueno',
            infoText: 'Más información'
        });
        $.cookieBar('addTranslation', 'ar', {
            message: 'نحن نستخدم ملفات تعريف الارتباط لتقديم خدماتنا. باستخدام هذا الموقع ، فإنك توافق على ذلك.',
            acceptText: 'حسنا',
            infoText: 'المزيد من المعلومات'
        });
        $.cookieBar('addTranslation', 'de', {
            message: 'Wir verwenden Cookies, um unsere Dienste bereitzustellen. Durch die Nutzung dieser Website stimmen Sie dem zu.',
            acceptText: 'OK',
            infoText: 'Mehr Informationen'
        });
        $.cookieBar('addTranslation', 'pt', {
            message: 'Usamos cookies para fornecer nossos serviços. Ao usar este site, você concorda com isso.',
            acceptText: 'OK',
            infoText: 'Mais Informações'
        });
        $.cookieBar({
            style: 'bottom',
            infoLink: $("body").data('cookie-bar-page-link'),
            language: $("html").attr("lang")
        });
    }

    // Disables payment related settings modifications on demo mode
    if (typeof $("body").data('demo-mode') !== 'undefined') {
        function disableFormSubmissionOnDemoMode(event) {
            showStackBarTop('error', '', Translator.trans('This feature is disabled in demo mode', {}, 'javascript'));
            event.preventDefault();
            return false;
        }

        $('.dropdown-item').each(function () {
            if (typeof $(this).attr('href') !== typeof undefined && $(this).attr('href') !== false) {
                if ($(this).attr('href').indexOf('/delete') >= 0 || $(this).attr('href').indexOf('/disable') >= 0 || $(this).attr('href').indexOf('/featured') >= 0 || $(this).attr('href').indexOf('/notfeatured') >= 0 || $(this).attr('href').indexOf('/hide') >= 0) {
                    $(this).prop('onclick', null).off('click').unbind('click');
                }
            }
            if (typeof $(this).attr('data-target') !== typeof undefined && $(this).attr('data-target') !== false) {
                if ($(this).attr('data-target').indexOf('/delete') >= 0 || $(this).attr('data-target').indexOf('/cancel') >= 0) {
                    $(this).prop('onclick', null).off('click').unbind('click');
                }
            }
        });

        $('.dropdown-item').click(function (e) {
            if (typeof $(this).attr('href') !== typeof undefined && $(this).attr('href') !== false) {
                if ($(this).attr('href').indexOf('/delete') >= 0 || $(this).attr('href').indexOf('/disable') >= 0 || $(this).attr('href').indexOf('/featured') >= 0 || $(this).attr('href').indexOf('/notfeatured') >= 0 || $(this).attr('href').indexOf('/hide') >= 0 || $(this).attr('href').indexOf('/hidefromdirectory') >= 0) {
                    disableFormSubmissionOnDemoMode(e);
                }
            }
            if (typeof $(this).attr('data-target') !== typeof undefined && $(this).attr('data-target') !== false) {
                if ($(this).attr('data-target').indexOf('/delete') >= 0 || $(this).attr('data-target').indexOf('/cancel') >= 0) {
                    disableFormSubmissionOnDemoMode(e);

                }
            }
        });

        $('.requires-confirmation:not(.dropdown-item)').click(function (e) {
            if (typeof $(this).attr('href') !== typeof undefined && $(this).attr('href') !== false) {
                if ($(this).attr('href').indexOf('/delete') >= 0 || $(this).attr('href').indexOf('/disable') >= 0 || $(this).attr('href').indexOf('/featured') >= 0 || $(this).attr('href').indexOf('/notfeatured') >= 0 || $(this).attr('href').indexOf('/hide') >= 0) {
                    disableFormSubmissionOnDemoMode(e);
                }
            }
            if (typeof $(this).attr('data-target') !== typeof undefined && $(this).attr('data-target') !== false) {
                if ($(this).attr('data-target').indexOf('/delete') >= 0 || $(this).attr('data-target').indexOf('/cancel') >= 0) {
                    disableFormSubmissionOnDemoMode(e);
                }
            }
        });
    }

    // Send a test email to check the mail server configuration
    $('.send-test-email-button').click(function () {
        var $thisButton = $(this);
        var $sendTestEmailDialog = $.confirm({
            title: Translator.trans('Mail server test email', {}, 'javascript'),
            content: $thisButton.data('confirmation-text') +
                    '<input id="mail-test-email-address" type="email" class="form-control mt-3" placeholder="' + Translator.trans('Email address', {}, 'javascript') + '">',
            buttons: {
                confirm: {
                    text: Translator.trans('Send', {}, 'javascript'),
                    btnClass: 'btn-primary btn-sm',
                    keys: ['enter'],
                    action: function () {
                        if (!isEmailValid($('#mail-test-email-address').val())) {
                            showStackBarTop('error', '', Translator.trans('Make sure to enter a valid email address', {}, 'javascript'));
                            return false;
                        } else {
                            location.href = $thisButton.data('target') + "?email=" + $('#mail-test-email-address').val();
                        }
                    }
                },
                cancel: {
                    text: Translator.trans('Cancel', {}, 'javascript'),
                    btnClass: 'btn-default btn-sm',
                    keys: ['esc'],
                    action: function () {
                        $sendTestEmailDialog.close();
                    }
                }
            }
        });
    });
})();
