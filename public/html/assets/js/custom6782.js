(function ($) {
    'use strict';

    Object.size = function (obj) {
        return Object.keys(obj).length;
    };

    var GmzBookingCustom = {
        isValidated: {},
        init: function () {
            this.initDocumentReady();
            this.initMapbox();
            this.initCheckboxClicked();
            this.preventDropdownClick();
            this.initPopupIframe();
            this.initDatePicker();
            this.initOther();
            this.initFormAction();
            this.initReviewForm();
            this.initLinkAction();
            this.initSearchForm();
            this.initGuestDropdownApartment();
            this.initGuestDropdownTour();
            this.initGuestDropdownHotel();
            this.initGuestDropdownSpace();
            this.initSelect2();
            this.initTimeDropdown();
            this.initMenu();
            this.initBeautyBookingForm();
            this.initWishList();
            this.initFotorama();
            this.initHotelBookingForm(); // Initialize new professional hotel booking form
        },

        initFotorama: function(){
            if ($('[data-plugin="fotorama"]').length) {
                $('[data-plugin="fotorama"]').fotorama();
            }
        },

        initWishList: function(){
            $(document).on('click', '.add-wishlist', function(){
                var t = $(this),
                    postID = t.data('id'),
                    postType = t.data('post-type');

                if(!t.hasClass('gmz-box-popup')) {
                    t.addClass('active');
                    $.post(t.data('action'), {
                        post_id: postID,
                        post_type: postType,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }, function (respon) {
                        if (respon.status) {
                            t.parent().html(respon.html);
                            $('.tooltip').hide();
                            $('[data-toggle="tooltip"]').tooltip('update');
                        }
                        t.removeClass('active');
                    }, 'json');
                }
            });
        },

        initBoxPopup: function(){
            if ($('.gmz-box-popup').length) {
                $('.gmz-box-popup').magnificPopup({
                    removalDelay: 500,
                    closeBtnInside: true,
                    callbacks: {
                        beforeOpen: function () {
                            this.st.mainClass = this.st.el.attr('data-effect');
                        }
                    },
                    midClick: true,
                    fixedContentPos: true
                });
            }
        },

        getUrlVars: function () {
            var vars = {};
            var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
                function (m, key, value) {
                    vars[key] = value;
                });
            return vars;
        },

        currencyFormat: function(price ,symbol, position = null){
            if(position === 'right'){
                return price + symbol;
            }else if(position === 'right_space'){
                return price + ' ' + symbol;
            }else if(position === 'left_space'){
                return symbol + ' ' + price;
            }else{
                return symbol + price;
            }
        },

        initBeautyBookingForm: function () {
            var t = this;
            var bookingForm = $('#beautyBookingForm');
            var checkInInput = $('#beautyBookingForm__date', bookingForm);
            if (bookingForm.length) {
                var dateFormat = checkInInput.data("date-format");
                var divContent = $(".booking-form__content", bookingForm);
                var checkIn = t.getUrlVars()['check_in'];
                var currentDate = moment().unix();
                var basePrice = bookingForm.data("base-price");

                if (isNaN(checkIn) || checkIn === "" || checkIn < currentDate){
                    checkIn = currentDate;
                }

                var getData = function (checkIn) {

                    var handlePopup = function () {
                        //popup
                        var popup = $('.booking-agent__info');
                        popup.offset(function (n,c) {
                            var pos = {};
                            if(c.left < 0){
                                pos.left = 0;
                            }
                            pos.right = c.right;
                            return pos;
                        })
                    };

                    var handleAgent = function(){
                        var select = $("#beautyBookingForm__slot");
                        select.change(function () {
                            var x = $(this);
                            var agentOfSlot = x.find(':selected').data('agent');
                            agentOfSlot = JSON.parse(atob(agentOfSlot));
                            $('input[name="agent"]',bookingForm).each(function () {
                                var x = $(this);
                                var idElement = x[0].id;
                                var idAgent = idElement.replace('agent_','');
                                var label = $("#label_agent_"+idAgent);
                                x.removeAttr('disable');
                                label.show();
                                if(agentOfSlot.indexOf(idAgent) < 0){
                                    x.attr('disable');
                                    label.hide();
                                }
                            })
                        }).change();
                    };

                    $.ajax({
                        url: bookingForm.data('action'),
                        type: 'GET',
                        data: {
                            slug: bookingForm.data('post-slug'),
                            checkIn: checkIn,
                        },
                    }).done(function (data, status) {
                        if (divContent.length && data['html']){
                            divContent.empty().append(data['html']);
                            handlePopup();
                            handleAgent();
                            if(data["price"]){
                                $('.price-value', bookingForm).html(data["price"]);
                            }else{
                                $('.price-value', bookingForm).html(basePrice);
                            }
                            t.initFormAction();
                        }
                    });
                };
                getData(checkIn);

                var dateString = moment.unix(checkIn).toDate();

                var optionDatePicker = {
                    singleDatePicker: true,
                    autoApply: true,
                    startDate: dateString,
                    minDate: new Date()
                };

                if(typeof localeDateRangePicker === 'object'){
                    optionDatePicker.locale = localeDateRangePicker;
                }

                checkInInput.daterangepicker(optionDatePicker);

                checkInInput.on('apply.daterangepicker', function(ev, picker) {
                    checkIn = moment(picker.startDate.format('YYYY-MM-DD'),'YYYY-MM-DD').unix();
                    getData(checkIn);
                });

            }
        },

        initGDPR: function () {
            if (typeof gmz_gdpr_params !== 'undefined') {
                if (gmz_gdpr_params.enable === 'on') {
                    glowCookies.start('en', {
                        hideAfterClick: gmz_gdpr_params.hide_after_click,
                        position: gmz_gdpr_params.position,
                        bannerDescription: gmz_gdpr_params.banner_description,
                        bannerLinkText: gmz_gdpr_params.banner_link_text,
                        policyLink: gmz_gdpr_params.policy_page,
                        bannerHeading: gmz_gdpr_params.banner_heading,
                        acceptBtnText: gmz_gdpr_params.button_accept_text,
                        rejectBtnText: gmz_gdpr_params.button_reject_text,
                        manageText: gmz_gdpr_params.manage_text,
                    });
                }
            }
        },

        initSelect2: function () {
            // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function () {
                var ss = $('.gmz-select-2');
                if (ss.length) {
                    ss.select2();
                }
            });
        },

        initRoomCheckout: function () {
            var roomBookingForm = $('#gmz-room-booking');
            roomBookingForm.off('submit');
            roomBookingForm.on('submit', function (e) {
                e.preventDefault();
                var form = $(this),
                    loader = $('.gmz-loader', form),
                    action = form.attr('action'),
                    data = form.serializeArray();

                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });

                loader.show();

                $.post(action, data, function (respon) {
                    if (typeof respon === 'object') {
                        if (respon.status) {
                            window.location.href = respon.redirect;
                        } else {
                            alert(respon.message);
                        }
                    }
                    loader.hide();
                }, 'json');

            })
        },

        initRoomPrice: function () {
            var form = $('#gmz-room-booking');
            var action = form.data('action-real-price'),
                loader = $('.gmz-loader', form),
                data = form.serializeArray();

            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            loader.show();

            $.post(action, data, function (respon) {
                if (typeof respon === 'object') {
                    if (respon.status) {
                        $('#gmz-render-number-room').text(respon.number);
                        $('#gmz-render-price-room').text(respon.price);
                    }
                }
                loader.hide();
            }, 'json');
        },

        initRoomBookingForm: function () {
            var roomBookingForm = $('#room-booking-form');
            if (roomBookingForm.length) {
                var base = this;
                var searchForm = $('#search-room');
                var roomHtml = $('#room-render-wrapper .room-html');
                var screenWidth = searchForm.outerWidth();
                $(window).scroll(function () {
                    if ($(window).scrollTop() >= searchForm.offset().top - 300 &&
                        $(window).scrollTop() <= (roomHtml.offset().top - roomHtml.innerHeight() + 300)) {
                        roomBookingForm.addClass('fixed');
                        roomBookingForm.css({
                            width: screenWidth + 'px'
                        })
                    } else {
                        roomBookingForm.removeClass('fixed');
                        roomBookingForm.css({
                            width: 'auto'
                        })
                    }
                });

                var bookingForm = $('#gmz-room-booking');
                var selectNumber = $('select', bookingForm);
                selectNumber.on('change', function () {
                    var checkShow = false;
                    selectNumber.each(function () {
                        if ($(this).val() != 0) {
                            checkShow = true;
                        }
                    });
                    if (checkShow) {
                        base.initRoomPrice();
                        roomBookingForm.addClass('show');
                    } else {
                        roomBookingForm.removeClass('show');
                    }
                });

                var checkboxItem = $('input[type="checkbox"]', bookingForm);
                checkboxItem.on('change', function () {
                    base.initRoomPrice();
                });
            }
        },

        initModal: function () {
            var base = this;
            $('#room-render-wrapper').on('click', '.gmz-open-modal', function (e) {
                e.preventDefault();
                var t = $(this),
                    modal = $(t.data('target')),
                    action = t.data('action'),
                    params = t.data('params'),
                    loader = $('.gmz-loader', modal),
                    data = [];

                data.push(
                    {name: '_token', value: $('meta[name="csrf-token"]').attr('content')},
                    {name: 'params', value: params}
                );

                loader.show();
                modal.modal('show');

                $.post(action, data, function (respon) {
                    if (typeof respon === 'object') {
                        if (respon.status) {
                            $(modal).find('.render').html(respon.html);
                            $('[data-toggle="tooltip"]').tooltip();
                            var roomGallery = $('#room-detail-gallery');
                            if (roomGallery.length) {
                                roomGallery.fotorama();
                            }
                        } else {
                            alert(respon.message);
                            modal.modal('hide');
                        }
                    }
                    loader.hide();
                }, 'json');
            });
        },

        initSearchRoom: function () {
            var base = this;
            var parent = $('#room-render-wrapper'),
                form = $('#search-room'),
                loader = $('.gmz-loader', parent),
                renderEl = $('.room-html', parent),
                formData = form.serializeArray(),
                action = form.attr('action');

            formData.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            loader.show();

            $.post(action, formData, function (respon) {
                renderEl.html(respon.html);
                $('[data-toggle="tooltip"]').tooltip();
                $('#room-booking-form').removeClass('show');
                base.initModal();
                base.initRoomBookingForm();
                base.initRoomCheckout();
                loader.hide();
            }, 'json');
        },

        initMatchHeight: function () {
            if ($('[data-plugin="matchHeight"]').length) {
                $('[data-plugin="matchHeight"]').matchHeight();
            }
        },

        initMenu: function () {
            $('.toggle-menu').on('click', function () {
                $('.main-menu').addClass('show');
                $('.menu-overlay').addClass('show');
            });
            $(document).on('click', '.main-menu .back-menu, .menu-overlay', function () {
                $('.main-menu').removeClass('show');
                $('.menu-overlay').removeClass('show');
            });
            $('.main-menu').prepend('<li class="back-menu"><i class="far fa-long-arrow-left"></i></li>')
            $('.main-menu li.menu-item-has-children').each(function () {
                $(this).append('<span class="arrow">+</span>');
            });
            $('.main-menu>li.menu-item-has-children').each(function () {
                $(this).append('<span class="arrow-pc"><i class="far fa-chevron-down"></i></span>');
            });
            $('.main-menu li .sub-menu>li.menu-item-has-children').each(function () {
                $(this).append('<span class="arrow-pc"><i class="far fa-chevron-right"></i></span>');
            });
            $('.main-menu li.menu-item-has-children .arrow').on('click', function () {
                var t = $(this),
                    parent = t.parent();

                parent.find('.sub-menu').first().slideToggle();
                parent.toggleClass('open');
                if (parent.hasClass('open')) {
                    t.text('-');
                } else {
                    t.text('+');
                }
            });
        },

        initTimeDropdown: function () {
            var base = this;
            var searchTime = $('.search-form__time');
            if (searchTime.length) {
                var renderEl = $('.time-render', searchTime),
                    selectEl = $('.dropdown-menu .item select', searchTime),
                    selectStartEl = $('.dropdown-menu .item select[name="startTime"]', searchTime),
                    selectEndEl = $('.dropdown-menu .item select[name="endTime"]', searchTime);

                selectEl.on('change', function () {
                    var strStartTime = '';
                    var strEndTime = '';

                    if ($(this).attr('name') === 'startTime' && selectStartEl.val() !== '') {
                        strStartTime = selectStartEl.find('option[value="' + selectStartEl.val() + '"]').text();

                        selectEndEl.empty().html(selectStartEl.find('option').clone());
                        selectEndEl.addClass('option-rendered');

                        var startIndex = selectStartEl.find('option[value="' + selectStartEl.val() + '"]').index();

                        if (selectEndEl.hasClass('option-rendered')) {
                            selectEndEl.find('option').attr('disabled', false);
                            for (var i = 0; i <= startIndex; i++) {
                                if (i < selectStartEl.find('option').length - 1) {
                                    selectEndEl.find('option').eq(i).attr('disabled', true);
                                }
                            }

                            if (startIndex < selectStartEl.find('option').length - 1) {
                                selectEndEl.find('option').eq(startIndex + 1).attr('selected', true);
                            } else {
                                selectEndEl.find('option').eq(startIndex).attr('selected', true);
                            }
                        }
                    }

                    if ($(this).attr('name') === 'endTime') {
                        strStartTime = selectStartEl.find('option[value="' + selectStartEl.val() + '"]').text();
                    }

                    if (selectEndEl.val() !== '') {
                        strEndTime = ' - ' + selectEndEl.find('option[value="' + selectEndEl.val() + '"]').text();
                    } else {
                        strEndTime = '';
                    }

                    renderEl.text(strStartTime + strEndTime);
                });
            }

            var bookingTime = $('.booking-time');
            if (bookingTime.length) {
                var selectBookingEl = $('select', bookingTime),
                    selectBookingStartEl = $('select[name="startTime"]', bookingTime),
                    selectBookingEndEl = $('select[name="endTime"]', bookingTime);

                selectBookingEl.on('change', function () {
                    if ($(this).attr('name') === 'startTime' && selectBookingStartEl.val() !== '') {
                        selectBookingEndEl.empty().html(selectBookingStartEl.find('option').clone());
                        selectBookingEndEl.addClass('option-rendered');
                        selectBookingEndEl.find('option[value=""]').text(selectBookingEndEl.data('origin-text'));

                        var startIndex = selectBookingStartEl.find('option[value="' + selectBookingStartEl.val() + '"]').index();

                        if (selectBookingEndEl.hasClass('option-rendered')) {
                            selectBookingEndEl.find('option').attr('disabled', false);
                            for (var i = 0; i <= startIndex; i++) {
                                if (i < selectBookingStartEl.find('option').length - 1) {
                                    selectBookingEndEl.find('option').eq(i).attr('disabled', true);
                                }
                            }

                            if (startIndex < selectBookingStartEl.find('option').length - 1) {
                                selectBookingEndEl.find('option').eq(startIndex + 1).attr('selected', true);
                            } else {
                                selectBookingEndEl.find('option').eq(startIndex).attr('selected', true);
                            }
                        }
                    }
                    base.initBookingForm();
                });
            }
        },

        initGuestDropdownApartment: function () {
            var searchGuest = $('.search-form__guest.apartment');
            if (searchGuest.length) {
                var renderEl = $('.guest-render', searchGuest),
                    selectEl = $('.dropdown-menu .item select', searchGuest),
                    selectAdultEl = $('.dropdown-menu .item select[name="adult"]', searchGuest),
                    selectChildEl = $('.dropdown-menu .item select[name="children"]', searchGuest),
                    selectInfantEl = $('.dropdown-menu .item select[name="infant"]', searchGuest),
                    numGuest = 1,
                    numInfant = 0;

                selectEl.on('change', function () {
                    numGuest = parseInt(selectAdultEl.val()) + parseInt(selectChildEl.val());
                    numInfant = parseInt(selectInfantEl.val());

                    var strGuest = '';
                    var strInfant = '';
                    if (numGuest == 1) {
                        strGuest = numGuest + ' ' + gmz_params.i18n.guest;
                    } else {
                        strGuest = numGuest + ' ' + gmz_params.i18n.guests;
                    }

                    if (numInfant > 0) {
                        if (numInfant == 1) {
                            strInfant = ', ' + numInfant + ' ' + gmz_params.i18n.infant;
                        } else {
                            strInfant = ', ' + numInfant + ' ' + gmz_params.i18n.infants;
                        }
                    } else {
                        strInfant = '';
                    }

                    renderEl.text(strGuest + strInfant);
                });
            }
        },

        initGuestDropdownTour: function () {
            var searchGuest = $('.search-form__guest.tour');
            if (searchGuest.length) {
                var renderEl = $('.guest-render', searchGuest),
                    selectEl = $('.dropdown-menu .item select', searchGuest),
                    selectAdultEl = $('.dropdown-menu .item select[name="adult"]', searchGuest),
                    selectChildEl = $('.dropdown-menu .item select[name="children"]', searchGuest),
                    selectInfantEl = $('.dropdown-menu .item select[name="infant"]', searchGuest),
                    numGuest = 1,
                    numInfant = 0;

                selectEl.on('change', function () {
                    numGuest = parseInt(selectAdultEl.val()) + parseInt(selectChildEl.val());
                    numInfant = parseInt(selectInfantEl.val());

                    var strGuest = '';
                    var strInfant = '';
                    if (numGuest == 1) {
                        strGuest = numGuest + ' ' + gmz_params.i18n.guest;
                    } else {
                        strGuest = numGuest + ' ' + gmz_params.i18n.guests;
                    }

                    if (numInfant > 0) {
                        if (numInfant == 1) {
                            strInfant = ', ' + numInfant + ' ' + gmz_params.i18n.infant;
                        } else {
                            strInfant = ', ' + numInfant + ' ' + gmz_params.i18n.infants;
                        }
                    } else {
                        strInfant = '';
                    }

                    renderEl.text(strGuest + strInfant);
                });
            }
        },

        initGuestDropdownSpace: function () {
            var searchGuest = $('.search-form__guest.space');
            if (searchGuest.length) {
                var renderEl = $('.guest-render', searchGuest),
                    selectEl = $('.dropdown-menu .item select', searchGuest),
                    selectAdultEl = $('.dropdown-menu .item select[name="adult"]', searchGuest),
                    selectChildEl = $('.dropdown-menu .item select[name="children"]', searchGuest),
                    selectInfantEl = $('.dropdown-menu .item select[name="infant"]', searchGuest),
                    numGuest = 1,
                    numInfant = 0;

                selectEl.on('change', function () {
                    numGuest = parseInt(selectAdultEl.val()) + parseInt(selectChildEl.val());
                    numInfant = parseInt(selectInfantEl.val());

                    var strGuest = '';
                    var strInfant = '';
                    if (numGuest == 1) {
                        strGuest = numGuest + ' ' + gmz_params.i18n.guest;
                    } else {
                        strGuest = numGuest + ' ' + gmz_params.i18n.guests;
                    }

                    if (numInfant > 0) {
                        if (numInfant == 1) {
                            strInfant = ', ' + numInfant + ' ' + gmz_params.i18n.infant;
                        } else {
                            strInfant = ', ' + numInfant + ' ' + gmz_params.i18n.infants;
                        }
                    } else {
                        strInfant = '';
                    }

                    renderEl.text(strGuest + strInfant);
                });
            }
        },

        initGuestDropdownHotel: function () {
            var searchGuest = $('.search-form__guest.hotel');
            if (searchGuest.length) {
                var renderEl = $('.guest-render', searchGuest),
                    selectEl = $('.dropdown-menu .item select', searchGuest),
                    selectAdultEl = $('.dropdown-menu .item select[name="adult"]', searchGuest),
                    selectChildrenEl = $('.dropdown-menu .item select[name="children"]', searchGuest),
                    numAdult = 1,
                    numChildren = 0;

                selectEl.on('change', function () {
                    numAdult = parseInt(selectAdultEl.val());
                    numChildren = parseInt(selectChildrenEl.val());

                    var strGuest = '';
                    if (numAdult == 1) {
                        strGuest = numAdult + ' ' + gmz_params.i18n.adult;
                    } else {
                        strGuest = numAdult + ' ' + gmz_params.i18n.adults;
                    }

                    if (numChildren > 0) {
                        strGuest += ', ' + numChildren + ' ' + gmz_params.i18n.children;
                    }

                    renderEl.text(strGuest);
                });
            }
        },

        initSearchForm: function () {
            var searchFormWrapper = $('.search-form-wrapper');
            if ($('.booking-type', searchFormWrapper).length) {
                $('.booking-type a', searchFormWrapper).on('click', function (e) {
                    var parent = $(this).closest('.tab-pane');
                    e.preventDefault();
                    $('.booking-type a', parent).removeClass('active');
                    $(this).addClass('active');
                    var type = $(this).data('type');
                    if (type == 'day') {
                        $('.time-group', parent).addClass('d-none');
                        $('.date-group', parent).removeClass('d-none');
                        $('[name="bookingType"]', parent).val('day');
                    } else {
                        $('.time-group', parent).removeClass('d-none');
                        $('.date-group', parent).addClass('d-none');
                        $('[name="bookingType"]', parent).val('hour');
                    }
                });
            }
        },

        initLinkAction: function () {
            var base = this;
            $('.gmz-link-action').unbind();
            $('.gmz-link-action').on('click', function (e) {
                e.preventDefault();
                var t = $(this);
                var conf = true;
                var loader = $('.gmz-loader.gmz-page-loader');
                if (t.data('confirm') === true) {
                    conf = confirm('Are you sure want to to it?');
                }
                if (conf) {
                    loader.show();
                    var action = t.data('action'),
                        params = t.data('params'),
                        data = [];

                    data.push(
                        {name: '_token', value: $('meta[name="csrf-token"]').attr('content')},
                        {name: 'params', value: params}
                    );

                    $.post(action, data, function (respon) {
                        if (typeof respon === 'object') {

                            if (typeof respon.redirect !== 'undefined') {
                                setTimeout(function () {
                                    window.location.href = respon.redirect;
                                }, 1000);
                            }

                            if (typeof respon.reload !== 'undefined') {
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            }

                            loader.hide();
                        }
                    }, 'json');
                }
            });
        },

        initReviewForm: function () {
            $('.reply-box-wrapper .btn-reply').on('click', function (e) {
                e.preventDefault();
                var t = $(this),
                    wrapper = t.closest('li'),
                    parent = t.closest('.reply-box-wrapper'),
                    appendEl = parent.find('.reply-form'),
                    commentForm = $('.post-comment.parent-form');

                $('.post-comment.append-form').remove();
                $('.reply-box-wrapper').find('.reply-form').html('');
                $('.reply-box-wrapper').removeClass('active');

                parent.addClass('active');
                commentForm.find('input[name="comment_id"]').val(parent.data('comment_id'));
                appendEl.html(commentForm.clone().removeClass('parent-form').addClass('append-form').show());
                commentForm.hide();
            });

            $('.reply-box-wrapper .btn-cancel-reply').on('click', function (e) {
                e.preventDefault();
                var t = $(this),
                    wrapper = t.closest('li'),
                    parent = t.closest('.reply-box-wrapper'),
                    appendEl = parent.find('.reply-form'),
                    commentForm = $('.post-comment.parent-form');

                parent.removeClass('active');
                commentForm.find('input[name="comment_id"]').val('');
                appendEl.html('');
                commentForm.show();
            });

            $('.review-select-rate .fas-star .fa').each(function () {
                var list = $(this).parent(),
                    listItems = list.children(),
                    itemIndex = $(this).index(),
                    parentItem = list.parent();
                $(this).on({
                    mouseenter: function () {
                        for (var i = 0; i < listItems.length; i++) {
                            if (i <= itemIndex) {
                                $(listItems[i]).addClass('hovered');
                            } else {
                                break;
                            }
                        }
                        $(this).on('click', function () {
                            for (var i = 0; i < listItems.length; i++) {
                                if (i <= itemIndex) {
                                    $(listItems[i]).addClass('selected');
                                } else {
                                    $(listItems[i]).removeClass('selected');
                                }
                            }
                            parentItem.children('.review_star').val(itemIndex + 1);
                        });
                    },
                    mouseleave: function () {
                        listItems.removeClass('hovered');
                    }
                });

            });
        },

        initValidation: function (form, addEvent) {
            var base = this;
            $('.gmz-validation', form).each(function () {
                var _id = $(this).attr('id'),
                    validation = $(this).attr('data-validation'),
                    element = $(this);

                // Only run bootstrapValidate if the element has an ID
                if (_id) {
                    bootstrapValidate('#' + _id, validation, function (isValid) {
                        if (isValid) {
                            if (typeof base.isValidated[_id] !== 'undefined') {
                                delete base.isValidated[_id];
                            }
                        } else {
                            base.isValidated[_id] = 1;
                        }
                    });
                } else {
                    // For elements without ID, do manual validation
                    var isValid = true;
                    if (validation === 'required') {
                        isValid = element.val() !== '' && element.val() !== null && element.val() !== undefined;
                    }
                    
                    // Use name attribute as key if no ID
                    var key = _id || element.attr('name') || 'field_' + Math.random();
                    
                    if (isValid) {
                        if (typeof base.isValidated[key] !== 'undefined') {
                            delete base.isValidated[key];
                        }
                    } else {
                        base.isValidated[key] = 1;
                    }
                }
                
                if (addEvent) {
                    if ($(this).val() === '') {
                        $(this).trigger('focus').trigger('blur');
                    }
                }
            });
        },

        initFormAction: function () {
            var base = this;
            $('.gmz-form-action').off().on('submit', function (e) {
                e.preventDefault();

                var form = $(this),
                    action = form.attr('action'),
                    loader = $('.gmz-loader', form),
                    message = $('.gmz-message', form);

                console.log('Form submission started');
                console.log('Form action:', action);
                console.log('Form classes:', form.attr('class'));

                // Check if this is a booking form and validate dates
                if (form.hasClass('booking-form-single')) {
                    var checkIn = $('[name="check_in"]', form);
                    var checkOut = $('[name="check_out"]', form);
                    
                    console.log('Check-in value:', checkIn.val());
                    console.log('Check-out value:', checkOut.val());
                    
                    // Ensure dates are set
                    if (!checkIn.val() || !checkOut.val()) {
                        console.log('Dates are missing');
                        message.html('<div class="alert alert-danger">Please select valid check-in and check-out dates.</div>');
                        return false;
                    }
                    
                    // Validate date format
                    var checkInDate = new Date(checkIn.val());
                    var checkOutDate = new Date(checkOut.val());
                    
                    console.log('Check-in date object:', checkInDate);
                    console.log('Check-out date object:', checkOutDate);
                    
                    if (isNaN(checkInDate.getTime()) || isNaN(checkOutDate.getTime())) {
                        console.log('Invalid date format');
                        message.html('<div class="alert alert-danger">Please select valid dates.</div>');
                        return false;
                    }
                    
                    // For tour bookings with single date selection, check_in and check_out can be the same
                    if (form.hasClass('tour')) {
                        // For tours, single date selection is allowed (check_in = check_out)
                        if (checkOutDate < checkInDate) {
                            console.log('Check-out date is before check-in date');
                            message.html('<div class="alert alert-danger">Check-out date cannot be before check-in date.</div>');
                            return false;
                        }
                        // For tours, we allow check_out to be the same as check_in (single day tours)
                        console.log('Tour booking: check-in and check-out can be the same date');
                    } else {
                        // For other services (hotel, apartment, etc.), check_out must be after check_in
                        if (checkOutDate <= checkInDate) {
                            console.log('Check-out date must be after check-in date');
                            message.html('<div class="alert alert-danger">Check-out date must be after check-in date.</div>');
                            return false;
                        }
                    }
                }

                console.log('Date validation passed');

                console.log('Starting form validation...');
                console.log('Validation fields found:', $('.gmz-validation', form).length);
                $('.gmz-validation', form).each(function() {
                    console.log('Validation field:', $(this).attr('name'), 'value:', $(this).val(), 'validation:', $(this).attr('data-validation'));
                });

                base.initValidation(form, true);
                
                console.log('Validation completed. isValidated object:', base.isValidated);
                console.log('Object.size(base.isValidated):', Object.size(base.isValidated));
                
                if (Object.size(base.isValidated)) {
                    var invalidEl = $('.gmz-validation.is-invalid', form).first();
                    if (invalidEl.length) {
                        console.log('Form validation failed');
                        console.log('First invalid element:', invalidEl.attr('name'), 'value:', invalidEl.val());
                        $("html, body").animate({scrollTop: invalidEl.offset().top}, 500);
                        invalidEl.focus();
                    }
                    return false;
                }

                console.log('Form validation passed');

                form.trigger('gmz_form_action_before', [form]);
                var paymentMethod = $("[name='payment_method']:checked", form).val();
                if (paymentMethod === 'stripe') {
                    var stripeToken = $("[name='stripeToken']", form).length;
                    if (!stripeToken) {
                        form.trigger('gmz_form_stripe', [form]);
                        return false;
                    }
                }
                var data = form.serializeArray();
                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });

                console.log('Form data to be sent:', data);

                loader.show();

                if (message.length > 0) {
                    message.empty();
                }

                console.log('Sending AJAX request to:', action);

                $.post(action, data, function (respon) {
                    console.log('AJAX response received:', respon);
                    
                    if (typeof respon === 'object') {
                        if (message.length > 0 && typeof respon.message !== 'undefined') {
                            if(form.hasClass('account-form')){
                                message.html(respon.message);
                            }else {
                                var classMessage = '';
                                if (respon.status) {
                                    classMessage = 'alert alert-success';
                                } else {
                                    classMessage = 'alert alert-danger';
                                }
                                message.html('<div class="' + classMessage + '">' + respon.message + '</div>');
                            }
                        }

                        $('#checkout-form').attr('data-order-id', respon.order_id);
                    }

                    if (typeof respon.redirect !== 'undefined') {
                        console.log('Redirecting to:', respon.redirect);
                        setTimeout(function () {
                            window.location.href = respon.redirect;
                        });
                    }

                    if (typeof respon.reload !== 'undefined') {
                        console.log('Reloading page');
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    }

                    loader.hide();
                }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('AJAX request failed:');
                    console.error('Status:', textStatus);
                    console.error('Error:', errorThrown);
                    console.error('Response:', jqXHR.responseText);
                    
                    loader.hide();
                    if (message.length > 0) {
                        message.html('<div class="alert alert-danger">An error occurred: ' + (jqXHR.responseJSON ? jqXHR.responseJSON.message : errorThrown) + '</div>');
                    }
                });
            });
        },

        initOther: function () {
            var base = this;
            if ($('.booking-form-single').length) {
                var form = $('.booking-form-single'),
                    quantity = $('[name="number"]', form),
                    checkbox = $('.accordion [type="checkbox"]', form);

                quantity.on('change', function () {
                    base.initBookingForm();
                });

                checkbox.on('change', function () {
                    base.initBookingForm();
                });

                if (form.hasClass('tour')) {
                    $('[name="adult"], [name="children"], [name="infant"]', form).on('change', function () {
                        base.initBookingForm();
                    });
                }
            }
            $('#booking-mobile').on('click', function () {
                $('.siderbar-single .booking-form').fadeIn('fast');
            });
            $('.siderbar-single .booking-form .close').on('click', function () {
                $('.siderbar-single .booking-form').fadeOut('fast');
            });

            $("#gmz-dropdown-notification").on('show.bs.dropdown', function () {
                var t = $(this),
                    data = {};
                data['_token'] = $('meta[name="csrf-token"]').attr('content');
                data['params'] = t.data('params');

                $.post(t.data('action'), data, function (respon) {
                    if (typeof respon == 'object') {
                        t.find('.badge').remove();
                    }
                }, 'json');
            });

            $('.view-password').on('click', function(){
               $(this).toggleClass('active');
               var passwordField = $(this).closest('.field-wrapper').find('input');
               if($(this).hasClass('active')){
                   passwordField.attr('type', 'text');
               }else{
                   passwordField.attr('type', 'password');
               }
            });
        },

        initBookingForm: function () {
            var base = this;
            if ($('.booking-form-single').length) {
                var form = $('.booking-form-single'),
                    loader = $('.gmz-loader', form),
                    message = $('.gmz-message', form);

                loader.show();
                message.empty();
                var data = form.serializeArray();
                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });
                $.post(form.data('price-url'), data, function (respon) {
                    if (typeof respon == 'object') {
                        if (respon.status) {
                            if (typeof respon.price !== 'undefined') {
                                // Update price in booking form header
                                $('.booking-form .price-value').text(respon.price);
                                // Update price in booking summary
                                $('#gmz-render-price').text(respon.price);
                                // Update guests count
                                var adult = form.find('select[name="adult"]').val() || 0;
                                var children = form.find('select[name="children"]').val() || 0;
                                var infant = form.find('select[name="infant"]').val() || 0;
                                var totalGuests = parseInt(adult) + parseInt(children) + parseInt(infant);
                                $('#gmz-render-guests').text(totalGuests);
                            }
                        } else {
                            var classMessage = '';
                            if (respon.status) {
                                classMessage = 'alert alert-success';
                            } else {
                                classMessage = 'alert alert-danger';
                            }
                            message.html('<div class="' + classMessage + '">' + respon.message + '</div>');
                        }
                        base.initFormAction();
                    }
                    loader.hide();
                }, 'json');
            }
        },

        initTimeSlots: function (t) {
            var base = this;
            if (t.hasClass('has-time')) {
                var data = {
                    startDate: $('[name="check_in"]', t).val(),
                    postID: t.data('id'),
                    postHashing: t.data('hashing'),
                    postType: t.data('post_type'),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                $.post(t.data('action-time'), data, function (respon) {
                    if (typeof respon === 'object') {
                        t.closest('form').find('.booking-time-wrapper').html(respon.list_times);
                        base.initTimeDropdown();
                    } else {
                        console.log('Can not get data');
                    }
                }, 'json');
            }
        },

        initDatePicker: function () {
            var base = this;
            $('.booking-date.range').each(function (e) {
                var t = $(this),
                    checkIn = $('[name="check_in"]', t),
                    checkInRender = $('.booking-date__in .render', t),
                    checkOut = $('[name="check_out"]', t),
                    checkOutRender = $('.booking-date__out .render', t),
                    checkInOut = $('[name="check_in_out"]', t);

                var sameDate = true;
                if (typeof checkInOut.data('same-date') !== 'undefined') {
                    sameDate = checkInOut.data('same-date');
                }

                var options = {
                    autoApply: true,
                    customEnableLoader: true,
                    opens: "left",
                    minDate: new Date(),
                    sameDate: sameDate
                };

                if(typeof localeDateRangePicker === 'object'){
                    options.locale = localeDateRangePicker;
                }

                if (checkIn.val() !== '') {
                    options.startDate = new Date(checkIn.val());
                }
                if (checkOut.val() !== '') {
                    options.endDate = new Date(checkOut.val());
                }

                checkInOut.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkInRender.text(start.format(t.data('date-format')));
                            checkOut.val(end.format('YYYY-MM-DD'));
                            checkOutRender.text(end.format(t.data('date-format')));
                            
                            // Trigger the daterangepicker_change event for hotel forms
                            checkInOut.trigger('daterangepicker_change', [start, end, label]);
                            
                            // Trigger validation
                            checkIn.trigger('blur');
                            checkOut.trigger('blur');
                            
                            // Check if this is a hotel booking form and trigger appropriate functions
                            var form = t.closest('.booking-form-single.hotel');
                            if (form.length) {
                                // For hotel forms, trigger room search and price calculation
                                if (typeof base.initHotelRoomSearch === 'function') {
                                    base.initHotelRoomSearch();
                                }
                            } else {
                                // For other forms, use the default behavior
                                base.initBookingForm();
                            }
                        }
                    });
                checkInRender.on('click', function () {
                    checkInOut.trigger('click');
                });
                checkOutRender.on('click', function () {
                    checkInOut.trigger('click');
                });
            });

            $('.booking-date.single').each(function (e) {

                var t = $(this),
                    checkIn = $('[name="check_in"]', t),
                    checkOut = $('[name="check_out"]', t),
                    checkInRender = $('.booking-date__intime .render', t),
                    checkInOut = $('[name="check_in_out"]', t);

                // Check if this is a same-date booking (like tours)
                var sameDate = t.data('same-date') === true;

                base.initTimeSlots(t);

                var options = {
                    autoApply: true,
                    customEnableLoader: true,
                    opens: "right",
                    minDate: new Date(),
                    singleDatePicker: true
                };

                if(typeof localeDateRangePicker === 'object'){
                    options.locale = localeDateRangePicker;
                }

                checkInOut.daterangepicker(options,
                    function (start, end, label) {
                        if (start !== null && end !== null) {
                            checkIn.val(start.format('YYYY-MM-DD'));
                            checkInRender.text(start.format(t.data('date-format')));
                            
                            // For same-date bookings (like tours), set check_out to the same date
                            if (sameDate && checkOut.length > 0) {
                                checkOut.val(start.format('YYYY-MM-DD'));
                            }
                            
                            checkInOut.trigger('daterangepicker_change', [start, end, label]);
                            
                            // Trigger validation
                            checkIn.trigger('blur');
                            if (sameDate && checkOut.length > 0) {
                                checkOut.trigger('blur');
                            }
                            
                            base.initTimeSlots(t);
                            if (!t.hasClass('has-time')) {
                                base.initBookingForm();
                            }
                        }
                    });
                checkInRender.on('click', function () {
                    checkInOut.trigger('click');
                });
            });
        },

        initPopupIframe: function () {
            $.fn.magnificPopup && $('.gmz-iframe-popup').each(function () {
                var t = $(this);
                t.magnificPopup({
                    removalDelay: 500,
                    type: 'iframe',
                    mainClass: 'mfp-zoom-in',
                    fixedContentPos: true
                });
            })
        },

        initNiceScroll: function () {
            if ($('[data-plugin="nicescroll"]').length) {
                $('[data-plugin="nicescroll"]').niceScroll();
            }
        },

        preventDropdownClick: function () {
            $('.filter-item .dropdown-menu, .search-form__guest .dropdown-menu, .search-form__time .dropdown-menu').on("click.bs.dropdown", function (e) {
                e.stopPropagation();
            });
        },

        initCheckboxClicked: function () {
            $('.gmz-checkbox-wrapper .gmz-checkbox-item').on('click', function () {
                var t = $(this),
                    parent = t.closest('.gmz-checkbox-wrapper'),
                    hiddenInput = $('[type="hidden"]', parent);

                var data = parent.find('.gmz-checkbox-item').serializeArray();
                if (Object.keys(data).length > 0) {
                    var temp = [];
                    data.forEach(function (t2) {
                        temp.push(t2.value);
                    });
                    hiddenInput.val(temp.join(',')).change();
                } else {
                    hiddenInput.val('').change();
                }
            });
        },

        initDocumentReady: function () {
            var base = this;
            $(document).ready(function () {
                $('.gmz-page-loader').fadeOut();
                base.initNiceScroll();
                base.initMatchHeight();
                base.initGDPR();
                base.initBoxPopup();
                if ($('#room-render-wrapper').length) {
                    base.initSearchRoom();
                    $('#search-room').on('submit', function (e) {
                        e.preventDefault();
                        base.initSearchRoom();
                    });
                }
            });
        },

        initMapbox: function () {
            if (typeof mapboxgl === 'object' && gmz_params.mapbox_token != '') {
                mapboxgl.accessToken = gmz_params.mapbox_token;
                $('[data-plugin="mapbox-geocoder"]').each(function () {
                    var t = $(this);
                    var geocoder = new MapboxGeocoder({
                        accessToken: mapboxgl.accessToken,
                        mapboxgl: mapboxgl,
                        language: t.data('lang'),
                        placeholder: t.data().placeholder
                    });
                    var map = new mapboxgl.Map({
                        style: 'mapbox://styles/mapbox/light-v10',
                        container: t.next('.map').get(0)
                    });

                    t.get(0).appendChild(geocoder.onAdd(map));

                    var oldVal = t.data().value;
                    if (typeof oldVal === 'string') {
                        geocoder.setInput(oldVal);
                    }
                    geocoder.on('result', function (result) {
                        if (typeof result.result.geometry.coordinates === 'object') {
                            t.closest('.search-form__address').find('input[name="lng"]').attr('value', result.result.geometry.coordinates[0]).trigger('change');
                            t.closest('.search-form__address').find('input[name="lat"]').attr('value', result.result.geometry.coordinates[1]).trigger('change');
                            t.closest('.search-form__address').find('input[name="address"]').attr('value', result.result.place_name).trigger('change');
                        }
                    });

                    $('.mapboxgl-ctrl-geocoder--input', t).on('input', function () {
                        var igr = $(this);
                        if (igr.val() === '') {
                            t.closest('.search-form__address').find('input[name="lng"]').attr('value', '').trigger('change');
                            t.closest('.search-form__address').find('input[name="lat"]').attr('value', '').trigger('change');
                            t.closest('.search-form__address').find('input[name="address"]').attr('value', '').trigger('change');
                        }
                    });
                });

                $('.map-single').each(function () {
                    console.log(123);
                    var t = $(this),
                        lat = parseFloat(t.data('lat')),
                        lng = parseFloat(t.data('lng'));

                    var map = new mapboxgl.Map({
                        container: t.get(0),
                        style: 'mapbox://styles/mapbox/light-v10',
                        center: [lng, lat],
                        zoom: 14,
                    });

                    map.scrollZoom.disable();
                    map.addControl(new mapboxgl.NavigationControl({showCompass: false}), 'bottom-right');
                    //scroll zoom with ctrl
                    map.scrollZoom.disable();
                    map.scrollZoom.setWheelZoomRate(0.02); // Default 1/450
                    map.on("wheel", event => {
                        if (event.originalEvent.ctrlKey) { // Check if CTRL key is pressed
                            event.originalEvent.preventDefault(); // Prevent chrome/firefox default behavior
                            if (!map.scrollZoom._enabled) map.scrollZoom.enable(); // Enable zoom only if it's disabled
                        } else {
                            if (map.scrollZoom._enabled) map.scrollZoom.disable(); // Disable zoom only if it's enabled
                        }
                    });
                    // disable map rotation using right click + drag
                    map.dragRotate.disable();
                    // disable map rotation using touch rotation gesture
                    map.touchZoomRotate.disableRotation();

                    var el = document.createElement('div');
                    el.className = 'gmz-single-marker';
                    new mapboxgl.Marker(el)
                        .setLngLat([lng, lat])
                        .addTo(map);

                    map.on('style.load', function () {
                        map.addSource('markers', {
                            "type": "geojson",
                            "data": {
                                "type": "FeatureCollection",
                                "features": [{
                                    "type": "Feature",
                                    "geometry": {
                                        "type": "Point",
                                        "coordinates": [lng, lat]
                                    },
                                    "properties": {
                                        "modelId": 1,
                                    },
                                }]
                            }
                        });
                        map.addLayer({
                            "id": "circles1",
                            "source": "markers",
                            "type": "circle",
                            "paint": {
                                "circle-radius": 100,
                                "circle-color": "#969696",
                                "circle-opacity": 0.2,
                                "circle-stroke-width": 0,
                            },
                            "filter": ["==", "modelId", 1],
                        });
                    });
                });
            }
        },

        // New Professional Hotel Booking Functions
        initHotelBookingForm: function () {
            var base = this;
            
            // Initialize room search on page load
            base.initHotelRoomSearch();
            
            // Handle date changes from daterangepicker
            $(document).on('daterangepicker_change', '.date-input', function(e, start, end, label) {
                console.log('Date picker changed:', {start: start, end: end, label: label});
                // Update hidden inputs with new dates
                var form = $('.booking-form-single.hotel');
                $('input[name="check_in"]', form).val(start.format('YYYY-MM-DD'));
                $('input[name="check_out"]', form).val(end.format('YYYY-MM-DD'));
                // Trigger room search and price calculation
                base.initHotelRoomSearch();
            });
            
            // Handle guest changes
            $('select[name="adult"], select[name="children"]').on('change', function() {
                console.log('Guest selection changed');
                // Trigger room search and price calculation
                base.initHotelRoomSearch();
            });
            
            // Handle extra services changes
            $('.gmz-checkbox-item').on('change', function() {
                console.log('Extra service changed');
                base.initHotelPriceCalculation();
            });
            
            // Handle room selection changes
            $(document).on('change', 'select[name^="room["]', function() {
                console.log('Room selection changed');
                base.initHotelPriceCalculation();
            });
            
            // Initialize booking form submission
            base.initHotelBookingSubmission();
        },

        initHotelRoomSearch: function () {
            var base = this;
            var form = $('.booking-form-single.hotel');
            var roomHtml = $('.room-html', form);
            var loader = $('.gmz-loader', form);
            var action = '/room-search'; // Use room-search endpoint
            var data = [];

            // Check if we have valid dates
            var checkIn = $('input[name="check_in"]', form).val();
            var checkOut = $('input[name="check_out"]', form).val();
            var adult = $('select[name="adult"]', form).val();
            var children = $('select[name="children"]', form).val();
            
            console.log('Room search params:', {checkIn: checkIn, checkOut: checkOut, adult: adult, children: children});
            
            if (!checkIn || !checkOut) {
                roomHtml.html('<div class="room-loading-placeholder"><div class="placeholder-text"><i class="fas fa-calendar-alt text-muted"></i><p>Please select dates to view available rooms</p></div></div>');
                // Reset price display
                $('#gmz-render-number-room').text('0');
                $('#gmz-render-price-room').text('$0');
                $('#gmz-render-nights').text('0');
                // Disable booking button
                $('.btn-book-now', form).prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
                return;
            }

            // Get hotel ID from the form or data attribute
            var hotelId = $('.booking-date.range', form).data('id');
            if (!hotelId) {
                console.error('Hotel ID not found in form data');
                roomHtml.html('<div class="alert alert-danger">Error: Hotel ID not found</div>');
                return;
            }
            
            // Prepare data for room search
            data.push(
                {name: 'hotel_id', value: hotelId},
                {name: 'checkIn', value: checkIn},
                {name: 'checkOut', value: checkOut},
                {name: 'adult', value: adult},
                {name: 'children', value: children},
                {name: 'number_room', value: 1},
                {name: '_token', value: $('meta[name="csrf-token"]').attr('content')}
            );

            loader.show();
            roomHtml.html('<div class="loading-rooms"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading rooms...</span></div><p class="mt-2">Loading available rooms...</p></div>');

            console.log('Sending room search request:', {action: action, data: data});
            
            $.post(action, data, function (respon) {
                console.log('Room search response:', respon);
                if (typeof respon === 'object') {
                    if (respon.status) {
                        roomHtml.html(respon.html);
                        $('[data-toggle="tooltip"]').tooltip();
                        
                        // Calculate and display nights
                        var nights = moment(checkOut).diff(moment(checkIn), 'days');
                        $('#gmz-render-nights').text(nights);
                        
                        // Trigger price calculation after rooms are loaded
                        setTimeout(function() {
                            if (typeof base.initHotelPriceCalculation === 'function') {
                                base.initHotelPriceCalculation();
                            } else {
                                console.error('initHotelPriceCalculation function not found');
                            }
                        }, 100);
                    } else {
                        // No rooms available - show fallback calculation
                        roomHtml.html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-2"></i>' + (respon.message || 'No rooms available for selected dates') + '</div>');
                        
                        // Calculate fallback price based on base price and number of days
                        base.calculateFallbackPrice();
                    }
                } else {
                    console.error('Invalid response format:', respon);
                    roomHtml.html('<div class="alert alert-danger">Invalid response from server</div>');
                    // Calculate fallback price on error
                    base.calculateFallbackPrice();
                }
                loader.hide();
            }, 'json').fail(function(xhr, status, error) {
                console.error('Room search failed:', {xhr: xhr, status: status, error: error});
                roomHtml.html('<div class="alert alert-danger">Error loading rooms. Please try again.</div>');
                // Calculate fallback price on error
                base.calculateFallbackPrice();
                loader.hide();
            });
        },

        // Calculate fallback price when no rooms are available
        calculateFallbackPrice: function() {
            var base = this;
            var form = $('.booking-form-single.hotel');
            
            // Get base price from the form data attribute first
            var basePrice = parseFloat(form.data('base-price')) || 0;
            
            // If no base price in data attribute, try to get from the price display
            if (basePrice === 0) {
                var basePriceElement = $('.price-value', form.closest('.booking-form'));
                if (basePriceElement.length) {
                    // Extract numeric value from price text (e.g., "$150", "€150", "150" -> 150)
                    var priceText = basePriceElement.text();
                    basePrice = parseFloat(priceText.replace(/[^0-9.]/g, '')) || 0;
                }
            }
            
            // If still no base price, try to get from the price unit text
            if (basePrice === 0) {
                var priceUnitElement = $('.price-unit', form.closest('.booking-form'));
                if (priceUnitElement.length) {
                    var unitText = priceUnitElement.text();
                    // Look for price in the unit text (e.g., "Starting from $150/night")
                    var priceMatch = unitText.match(/\$(\d+(?:\.\d{2})?)/);
                    if (priceMatch) {
                        basePrice = parseFloat(priceMatch[1]);
                    }
                }
            }
            
            console.log('Base price found:', basePrice);
            
            // Calculate nights
            var checkIn = $('input[name="check_in"]', form).val();
            var checkOut = $('input[name="check_out"]', form).val();
            var nights = 1;
            
            if (checkIn && checkOut) {
                nights = moment(checkOut).diff(moment(checkIn), 'days');
                if (nights < 1) nights = 1;
            }
            
            // Calculate total price
            var totalPrice = basePrice * nights;
            
            // Format price with proper currency symbol
            var currencySymbol = '$'; // Default to dollar sign
            var priceDisplay = currencySymbol + totalPrice.toFixed(2);
            
            // Try to detect currency from the original price display
            var originalPriceElement = $('.price-value', form.closest('.booking-form'));
            if (originalPriceElement.length) {
                var originalPriceText = originalPriceElement.text();
                if (originalPriceText.includes('€')) {
                    currencySymbol = '€';
                    priceDisplay = currencySymbol + totalPrice.toFixed(2);
                } else if (originalPriceText.includes('£')) {
                    currencySymbol = '£';
                    priceDisplay = currencySymbol + totalPrice.toFixed(2);
                }
            }
            
            // Update display
            $('#gmz-render-nights').text(nights);
            $('#gmz-render-number-room').text('0');
            $('#gmz-render-price-room').text(priceDisplay);
            
            // Update booking button with informative message
            var bookButton = $('.btn-book-now', form);
            bookButton.prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
            
            if (basePrice > 0) {
                bookButton.html('<i class="fas fa-exclamation-triangle mr-2"></i>NO ROOMS AVAILABLE - ESTIMATED PRICE SHOWN');
                // Add a note about the fallback calculation
                if (!$('.fallback-note').length) {
                    $('.booking-summary').append('<div class="fallback-note alert alert-info mt-2"><small><i class="fas fa-info-circle mr-1"></i>Price shown is estimated based on base rate and number of nights. No rooms are currently available for the selected dates.</small></div>');
                }
            } else {
                bookButton.html('<i class="fas fa-exclamation-triangle mr-2"></i>NO ROOMS AVAILABLE');
                // Remove fallback note if no base price
                $('.fallback-note').remove();
            }
            
            console.log('Fallback price calculated:', {
                basePrice: basePrice,
                nights: nights,
                totalPrice: totalPrice,
                currencySymbol: currencySymbol,
                priceDisplay: priceDisplay
            });
        },

        initHotelPriceCalculation: function () {
            var base = this;
            var form = $('.booking-form-single.hotel');
            var action = form.data('price-url'); // This should be room-get-real-price
            var data = form.serializeArray();

            // Add hotel_id to the data
            var hotelId = $('.booking-date.range', form).data('id');
            data.push(
                {name: 'hotel_id', value: hotelId},
                {name: '_token', value: $('meta[name="csrf-token"]').attr('content')}
            );

            console.log('Sending price calculation request:', {action: action, data: data});
            
            // Show loading state
            var priceElement = $('#gmz-render-price-room');
            var originalPrice = priceElement.text();
            priceElement.text('Calculating...');
            
            $.post(action, data, function (respon) {
                console.log('Price calculation response:', respon);
                if (typeof respon === 'object') {
                    if (respon.status) {
                        $('#gmz-render-number-room').text(respon.number || 0);
                        $('#gmz-render-price-room').text(respon.price || '$0');
                        
                        // Calculate nights if not already set
                        var nightsElement = $('#gmz-render-nights');
                        if (nightsElement.text() === '0' || nightsElement.text() === '') {
                            var checkIn = $('input[name="check_in"]').val();
                            var checkOut = $('input[name="check_out"]').val();
                            if (checkIn && checkOut) {
                                var nights = moment(checkOut).diff(moment(checkIn), 'days');
                                nightsElement.text(nights);
                            }
                        }
                        
                        // Update booking button state
                        var totalRooms = parseInt(respon.number) || 0;
                        var bookButton = $('.btn-book-now', form);
                        if (totalRooms > 0) {
                            bookButton.prop('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
                            bookButton.html('<i class="fas fa-lock mr-2"></i>SECURE BOOKING NOW');
                        } else {
                            bookButton.prop('disabled', true).removeClass('btn-primary').addClass('btn-secondary');
                            bookButton.html('<i class="fas fa-lock mr-2"></i>SELECT ROOMS TO BOOK');
                        }
                        
                        console.log('Price calculation successful:', {
                            rooms: respon.number,
                            price: respon.price,
                            nights: nightsElement.text()
                        });
                    } else {
                        // Handle error in price calculation - try fallback
                        console.warn('Price calculation failed, trying fallback:', respon.message);
                        base.calculateFallbackPrice();
                    }
                } else {
                    console.error('Invalid response format:', respon);
                    // Try fallback calculation
                    base.calculateFallbackPrice();
                }
            }, 'json').fail(function(xhr, status, error) {
                console.error('Price calculation failed:', {xhr: xhr, status: status, error: error});
                // Try fallback calculation on error
                base.calculateFallbackPrice();
            });
        },

        // Debug function to test hotel price calculation
        debugHotelPriceCalculation: function() {
            var form = $('.booking-form-single.hotel');
            if (!form.length) {
                console.error('Hotel booking form not found');
                return;
            }
            
            console.log('=== Hotel Price Calculation Debug ===');
            console.log('Form data:', form.serializeArray());
            console.log('Check-in:', $('input[name="check_in"]', form).val());
            console.log('Check-out:', $('input[name="check_out"]', form).val());
            console.log('Adults:', $('select[name="adult"]', form).val());
            console.log('Children:', $('select[name="children"]', form).val());
            console.log('Hotel ID:', $('.booking-date.range', form).data('id'));
            console.log('Price URL:', form.data('price-url'));
            
            // Test room search
            if (typeof this.initHotelRoomSearch === 'function') {
                console.log('Testing room search...');
                this.initHotelRoomSearch();
            } else {
                console.error('initHotelRoomSearch function not found');
            }
        },

        initHotelBookingSubmission: function () {
            var base = this;
            $('.booking-form-single.hotel').off('submit').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var loader = $('.gmz-loader', form);
                var action = form.attr('action');
                var data = form.serializeArray();

                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });

                loader.show();

                $.post(action, data, function (respon) {
                    if (typeof respon === 'object') {
                        if (respon.status) {
                            window.location.href = respon.redirect;
                        } else {
                            $('.gmz-message', form).html('<div class="alert alert-danger">' + respon.message + '</div>');
                        }
                    }
                    loader.hide();
                }, 'json');
            });
        },

        // Enhanced room booking functions for compatibility
        initRoomCheckout: function () {
            var roomBookingForm = $('#gmz-room-booking');
            roomBookingForm.off('submit');
            roomBookingForm.on('submit', function (e) {
                e.preventDefault();
                var form = $(this),
                    loader = $('.gmz-loader', form),
                    action = form.attr('action'),
                    data = form.serializeArray();

                data.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                });

                loader.show();

                $.post(action, data, function (respon) {
                    if (typeof respon === 'object') {
                        if (respon.status) {
                            window.location.href = respon.redirect;
                        } else {
                            alert(respon.message);
                        }
                    }
                    loader.hide();
                }, 'json');

            })
        },

        initRoomPrice: function () {
            var form = $('#gmz-room-booking');
            var action = form.data('action-real-price'),
                loader = $('.gmz-loader', form),
                data = form.serializeArray();

            data.push({
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            });

            loader.show();

            $.post(action, data, function (respon) {
                if (typeof respon === 'object') {
                    if (respon.status) {
                        $('#gmz-render-number-room').text(respon.number);
                        $('#gmz-render-price-room').text(respon.price);
                    }
                }
                loader.hide();
            }, 'json');
        },

        initRoomBookingForm: function () {
            var roomBookingForm = $('#room-booking-form');
            if (roomBookingForm.length) {
                var base = this;
                var searchForm = $('#search-room');
                var roomHtml = $('#room-render-wrapper .room-html');
                var screenWidth = searchForm.outerWidth();
                $(window).scroll(function () {
                    if ($(window).scrollTop() >= searchForm.offset().top - 300 &&
                        $(window).scrollTop() <= (roomHtml.offset().top - roomHtml.innerHeight() + 300)) {
                        roomBookingForm.addClass('fixed');
                        roomBookingForm.css({
                            width: screenWidth + 'px'
                        })
                    } else {
                        roomBookingForm.removeClass('fixed');
                        roomBookingForm.css({
                            width: 'auto'
                        })
                    }
                });

                var bookingForm = $('#gmz-room-booking');
                var selectNumber = $('select', bookingForm);
                selectNumber.on('change', function () {
                    var checkShow = false;
                    selectNumber.each(function () {
                        if ($(this).val() != 0) {
                            checkShow = true;
                        }
                    });
                    if (checkShow) {
                        base.initRoomPrice();
                        roomBookingForm.addClass('show');
                    } else {
                        roomBookingForm.removeClass('show');
                    }
                });

                var checkboxItem = $('input[type="checkbox"]', bookingForm);
                checkboxItem.on('change', function () {
                    base.initRoomPrice();
                });
            }
        }
    };

    GmzBookingCustom.init();

    // Debug function to test hashing
    window.testHashing = function() {
        var postId = $('[name="post_id"]').val();
        var postHashing = $('[name="post_hashing"]').val();
        
        $.get('/apartment-debug-hashing', {
            post_id: postId,
            post_hashing: postHashing
        }, function(data) {
            console.log('Hashing test result:', data);
            alert('Hashing test result:\n' + JSON.stringify(data, null, 2));
        });
    };
    
    // Debug function to test hotel price calculation
    window.testHotelPriceCalculation = function() {
        if (typeof GmzBookingCustom !== 'undefined' && typeof GmzBookingCustom.debugHotelPriceCalculation === 'function') {
            GmzBookingCustom.debugHotelPriceCalculation();
        } else {
            console.error('GmzBookingCustom or debugHotelPriceCalculation not available');
        }
    };
    
    // Debug function to manually trigger hotel room search
    window.testHotelRoomSearch = function() {
        if (typeof GmzBookingCustom !== 'undefined' && typeof GmzBookingCustom.initHotelRoomSearch === 'function') {
            console.log('Manually triggering hotel room search...');
            GmzBookingCustom.initHotelRoomSearch();
        } else {
            console.error('GmzBookingCustom or initHotelRoomSearch not available');
        }
    };
    
    // Debug function to test fallback price calculation
    window.testFallbackPriceCalculation = function() {
        if (typeof GmzBookingCustom !== 'undefined' && typeof GmzBookingCustom.calculateFallbackPrice === 'function') {
            console.log('Testing fallback price calculation...');
            GmzBookingCustom.calculateFallbackPrice();
        } else {
            console.error('GmzBookingCustom or calculateFallbackPrice not available');
        }
    };
})(jQuery);