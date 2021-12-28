jQuery(function ($) {
    $.horizontalMenu = function (el) {
        var $widget = $(el);
        var $mobMenu = $widget.find('.dt-nav-menu-horizontal--main');
        const $widgetContainer = $widget.find(".elementor-widget-container");
        const $menuWrap = $widget.find(".horizontal-menu-wrap");
        let touchEvent = $('.touchevents').length > 0;
        var $ulMenu = $widget.find('.dt-nav-menu-horizontal');
        var $toggleBtn = $widget.find('.horizontal-menu-toggle');
        var $window = $(window);
        var $page = $("#page");
        var methods;
        const haveDropdownDesktop = $widget.hasClass("horizontal-menu--dropdown-desktop");
        const haveDropdownTablet = $widget.hasClass("horizontal-menu--dropdown-tablet");
        const haveDropdownMobile = $widget.hasClass("horizontal-menu--dropdown-mobile");

        // Store a reference to the object
        $.data(el, "horizontalMenu", $widget);

        const state = {
            type: "horizontal",
            isDropdownOpen: false,
        };

        // Private methods
        methods = {
            init: function () {
                var menuMobileTimeoutHide;
                var parentsAreClickable = $widget.hasClass("parent-item-clickable-yes");
                var $elementsThatTriggerDropdown = $widget.find("li.has-children > a ");
                methods.bindEvents();
                methods.handleMobile();

                if (!parentsAreClickable) {
                    const $nonClickableLinks = $widget.find("li.has-children > a.not-clickable-item");
                    $elementsThatTriggerDropdown = $elementsThatTriggerDropdown.add($nonClickableLinks);
                }

                $ulMenu.each(function() {
                    var $mainNav = $(this);
                         $('body').on("click", function(e) {
                            if(!$(e.target).hasClass('horizontal-sub-nav') && !$mobMenu.hasClass('horizontal-menu-dropdown')){
                                $("li.dt-hovered > .horizontal-sub-nav", $mainNav).animate({
                                    "opacity": 0
                                }, 100, function() {
                                    $(this).css("visibility", "hidden");
                                });

                                $(" li.has-children", $mainNav).removeClass("dt-hovered parent-clicked");
                                $(" li.has-children > a", $mainNav).removeClass("is-clicked");
                            }
                        });


                    $(".act", $mainNav).parents("li").addClass("act");
                    $(" li.has-children ", $mainNav).each(function() {
                        var $this = $(this);
                        var $thisHover = $this.find("> a");

                        if(dtGlobals.isMobile || dtGlobals.isWindowsPhone){
                            $this.find("> a").on("click", function(e) {
                                if (!$(this).hasClass("dt-clicked")) {
                                    e.preventDefault();
                                    $mainNav.find(".dt-clicked").removeClass("dt-clicked");
                                    $(this).addClass("dt-clicked");
                                } else {
                                    e.stopPropagation();
                                }
                            });
                        }

                        if(($widget.hasClass('show-sub-menu-on-hover') || $widget.hasClass('parent-item-clickable-yes')) && !$widget.hasClass('horizontal-menu-dropdown')){

                            $thisHover.on("mouseenter tap", function(e) {
                                if(e.type == "tap") e.stopPropagation();
                                var $this = $(this);
                                methods.showSubMenu($this);

                            });

                            $this.on("mouseleave", function(e) {
                                var $this = $(this);
                                methods.hideSubMenu($this);
                            });
                        }else{
                            $thisHover.on("click", function(e) {
                                if(e.type == "tap") e.stopPropagation();
                                var $this = $(this),
                                    $thisLink = $this.parent("li");

                                if ($this.hasClass("is-clicked")) {
                                    methods.hideSubMenu($thisLink);
                                    $this.removeClass("is-clicked");
                                    $this.parent().removeClass("parent-clicked");
                                }else{
                                    methods.showSubMenu($this);
                                    $(" li.has-children > a").removeClass("is-clicked");
                                    $(" li.has-children").removeClass("parent-clicked");
                                    $this.parent().addClass("parent-clicked");
                                    if(!$(e.target).parents().hasClass('horizontal-sub-nav')){
                                        $(" li.has-children").removeClass("dt-hovered");
                                        $this.parent().addClass("dt-hovered");
                                    }
                                    if(!$thisHover.parents().hasClass('horizontal-menu-dropdown')){
                                        $(".dt-nav-menu-horizontal > li:not(.dt-hovered) > .horizontal-sub-nav").stop().animate({
                                            "opacity": 0
                                        }, 150, function() {
                                            $(this).css("visibility", "hidden");
                                        });
                                    }
                                    $this.parent().siblings().find(".horizontal-sub-nav ").stop().animate({
                                        "opacity": 0
                                    }, 150, function() {
                                        $(this).css("visibility", "hidden");
                                    });
                                    $this.addClass("is-clicked");
                                    return false;
                                }
                            })

                        }

                    });
                });

                // Close dropdown menu upon scrolling to the element.
                elementorFrontend.elements.$window.on("the7.anchorScrolling", function() {
                    methods.closeDropdownMenu();
                });

                // Toggle deropdown menu open/close on button click.
                $toggleBtn.on("click", function() {
                    if(state.isDropdownOpen) {
                        methods.closeDropdownMenu();
                    } else {
                        methods.openDropdownMenu();
                    }
                });

                // Close dropdown menu on any popup close.
                elementorFrontend.elements.$document.on('elementor/popup/hide',function() {
                    methods.closeDropdownMenu();
                });

                if(touchEvent){
                    $elementsThatTriggerDropdown.on("touchstart", function(e) {
                        origY = e.originalEvent.touches[0].pageY;
                        origX = e.originalEvent.touches[0].pageX;
                    });
                    $elementsThatTriggerDropdown.on("touchend", function(e) {
                        var touchEX = e.originalEvent.changedTouches[0].pageX,
                            touchEY = e.originalEvent.changedTouches[0].pageY;
                        if( origY == touchEY || origX == touchEX ){

                            var $this = $(this);
                            if(!$mobMenu.hasClass('horizontal-menu-dropdown')) {
                                return;
                            }
                            var $thisTarget = $this.attr("target") ? $this.attr("target") : "_self";
                            e = window.event || e;

                            e.stopPropagation();
                            e.preventDefault();

                            clearTimeout(menuMobileTimeoutHide);
                            menuMobileTimeoutHide = setTimeout(function () {
                                if (!$(e.target).hasClass("submenu-indicator") && parentsAreClickable) {
                                    window.open($this.attr("href"), $thisTarget);
                                    // return true;
                                } else {
                                    if ($this.hasClass("item-active")) {
                                        methods.closeMobSubMenu($this);
                                    } else {
                                        methods.openMobSubMenu($this);
                                    }
                                    return false;
                                }
                            }, 100);
                        }
                    });

                    $widget.find(".not-clickable-item").on("touchstart", function(e) {
                        origY = e.originalEvent.touches[0].pageY;
                        origX = e.originalEvent.touches[0].pageX;
                    });
                    $widget.find(".not-clickable-item").on("touchend", function(e) {
                        var touchEX = e.originalEvent.changedTouches[0].pageX,
                            touchEY = e.originalEvent.changedTouches[0].pageY;
                        if( origY == touchEY || origX == touchEX ){
                            e.preventDefault();
                            e.stopPropagation();
                        }
                    });
                }else{
                    $elementsThatTriggerDropdown.on("click", function(e) {
                        var $this = $(this);

                        if(!$mobMenu.hasClass('horizontal-menu-dropdown')) {
                            return;
                        }
                        var $thisTarget = $this.attr("target") ? $this.attr("target") : "_self";
                        e = window.event || e;

                        e.stopPropagation();
                        e.preventDefault();

                        clearTimeout(menuMobileTimeoutHide);
                        menuMobileTimeoutHide = setTimeout(function () {
                            if (!$(e.target).hasClass("submenu-indicator") && parentsAreClickable) {
                                window.open($this.attr("href"), $thisTarget);
                            } else {
                                if ($this.hasClass("item-active")) {
                                    methods.closeMobSubMenu($this);
                                } else {
                                    methods.openMobSubMenu($this);
                                }
                                return false;
                            }
                        }, 100);
                    })

                    $widget.find(".not-clickable-item").on("click", function(e){
                        e.preventDefault();
                        e.stopPropagation();
                    });
                }
            },
            showSubMenu: function ($el) {
                var visibilityTimeout = $el.data("the7HorizontalSubMenuVisibilityTimeout") || 0;

                if($el.parent("li").length > 0){
                    var $thisPar = $el.parent(),
                        $subMenu = $el.siblings("ul");
                }else{
                    var $thisPar = $el,
                        $this_a = $el.find("> a"),
                        $subMenu = $this_a.siblings("ul");
                }

                $thisPar.addClass("dt-hovered");

                /*Right overflow menu*/
                if($subMenu.length > 0){
                    if ($page.width() - ($subMenu.offset().left - $page.offset().left) - $subMenu.innerWidth() < 0) {
                        $subMenu.addClass("right-overflow");
                    }else if (($subMenu.offset().left < $page.offset().left)) {
                        $subMenu.addClass("left-overflow");
                    }
                    /*Bottom overflow menu*/

                    if ($window.height() - ($subMenu.offset().top - dtGlobals.winScrollTop) - $subMenu.innerHeight() < 0) {
                        $subMenu.addClass("bottom-overflow");
                    };
                }

                clearTimeout(visibilityTimeout);

                visibilityTimeout = setTimeout(function() {
                    if($thisPar.hasClass("dt-hovered") && !$thisPar.parents().hasClass('horizontal-menu-dropdown')){
                        $subMenu.stop().css({"visibility": "visible", 'display': ''}).animate({
                            "opacity": 1
                        }, 200, function() {
                        });

                    }
                }, 100);

                $el.data("the7HorizontalSubMenuVisibilityTimeout", visibilityTimeout);
            },
            hideSubMenu: function ($el) {
                var $thisLink = $el.find("> a"),
                    $subMenu = $thisLink.siblings(" ul");
                var visibilityTimeout = $el.data("the7HorizontalSubMenuVisibilityTimeout") || 0;

                $el.removeClass("dt-hovered");
                $thisLink.removeClass("dt-clicked");
                clearTimeout(visibilityTimeout);

                visibilityTimeout = setTimeout(function() {
                    if (methods.isDropdown() || $el.hasClass("dt-hovered")) {
                        return;
                    }

                    $subMenu.stop().animate({
                        "opacity": 0
                    }, 150, function() {
                        $subMenu.css("visibility", "hidden");
                    });

                    setTimeout(function() {
                        if(!$el.hasClass("dt-hovered")){
                            $subMenu.removeClass(["right-overflow", "left-overflow", "bottom-overflow"]);
                        }
                    }, 400);
                }, 150);

                $el.data("the7HorizontalSubMenuVisibilityTimeout", visibilityTimeout);
            },
            bindEvents: function () {
                elementorFrontend.elements.$window.on('resize', methods.handleMobile);
            },
            unBindEvents: function () {
                elementorFrontend.elements.$window.off('resize', methods.handleMobile);
            },
            handleMobile: elementorFrontend.debounce( function () {
                const useDropdownTablet = haveDropdownTablet && window.innerWidth <= elementorFrontendConfig.breakpoints.lg;
                const useDropdownMobile = haveDropdownMobile && window.innerWidth <= (elementorFrontendConfig.breakpoints.md - 1);

                if (useDropdownTablet || useDropdownMobile || haveDropdownDesktop) {
                    $ulMenu.removeClass("dt-nav-menu-horizontal").addClass("horizontal-sub-nav");
                    $mobMenu.addClass("horizontal-menu-dropdown");
                } else if ($mobMenu.hasClass("horizontal-menu-dropdown")) {
                    $widget.find(".dt-nav-menu-horizontal--main > ul").addClass("dt-nav-menu-horizontal").removeClass("horizontal-sub-nav");
                    $mobMenu.removeClass("horizontal-menu-dropdown");
                }

                /**
                 * Setup dynamic css var with the menu wrapper left offset.
                 *
                 * It supports "justify" submenu alighnment.
                 */
                $widget.css("--dynamic-justified-submenu-left-offset", "-" + $widgetContainer.offset().left + "px" );

                /**
                 * Scrollbar width css var. It supports "justify" submenu max-width.
                 */
                $widget.css("--scrollbar-width", (window.innerWidth - document.scrollingElement.clientWidth) + "px" );
            }, 300),
            isDropdown: function() {
                return $mobMenu.hasClass("horizontal-menu-dropdown");
            },
            closeDropdownMenu: function () {
                $toggleBtn.attr("aria-expanded", "false").removeClass("elementor-active");
                $mobMenu.attr("aria-hidden", "true");

                state.isDropdownOpen = false;
                 if(dtGlobals.isMobile){
                    elementorFrontend.elements.$body.css({'overflow-y': '', 'position': '', 'height': ''})
                }
                // Remove "closeOnOuterClickHandler" when menu is closed.
                elementorFrontend.elements.$body.off("click, touchstart", methods.closeOnOuterClickHandler);

                // TODO: Do we really need this when body has overflow hidden?
                elementorFrontend.elements.$window.off("scroll", methods.setDropdownHeight);
            },
            openDropdownMenu: function() {
                $toggleBtn.attr("aria-expanded", "true").addClass("elementor-active");
                $mobMenu.attr("aria-hidden", "false");

                state.isDropdownOpen = true;
                methods.setDropdownHeight();

                if(dtGlobals.isMobile){
                    elementorFrontend.elements.$body.css({
                        'overflow-y': 'hidden',
                        'position': 'relative',
                        'height': window.innerHeight
                    });
                }

                // Add "closeOnOuterClickHandler" while opening dropdown menu.
                elementorFrontend.elements.$body
                    .off("click, touchstart", methods.closeOnOuterClickHandler)
                    .on("click, touchstart", methods.closeOnOuterClickHandler);

                // TODO: Do we really need this when body has overflow hidden?
                elementorFrontend.elements.$window
                    .off("scroll", methods.setDropdownHeight)
                    .on("scroll", methods.setDropdownHeight);
            },
            closeMobSubMenu: function ($el) {
                $el.siblings(".horizontal-sub-nav").css("opacity", "0").stop(true, true).slideUp(250);
                $el.removeClass("item-active");
            },
            openMobSubMenu: function($el) {
                $el.siblings(".horizontal-sub-nav").css("opacity", "0").stop(true, true).slideDown(250)
                    .animate(
                        {opacity: 1},
                        {queue: false, duration: 150}
                    );
                $el.addClass("item-active");
            },
            setDropdownHeight: function () {
                if(state.isDropdownOpen) {
                    let vh = (window.innerHeight - ($mobMenu.offset().top - dtGlobals.winScrollTop));
                    document.documentElement.style.setProperty('--vh', `${vh}px`);
                }
            },
            closeOnOuterClickHandler: function(event) {
                /**
                 * Close dropdown if event path not contains menu wrap object.
                 *
                 * @see https://developer.mozilla.org/en-US/docs/Web/API/Event/composedPath
                 */
                if (!event.originalEvent.composedPath().includes($menuWrap.get(0))) {
                    methods.closeDropdownMenu();
                }
            }
        };

        $widget.delete = function () {
            methods.unBindEvents();
            $widget.removeData("horizontalMenu");
        };
        methods.init();
    };
    $.fn.horizontalMenu = function () {
        return this.each(function () {
            var widgetData = $(this).data('horizontalMenu');
            if (widgetData !== undefined) {
                widgetData.delete();
            }
            new $.horizontalMenu(this);
        });
    };

});
(function ($) {
    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/the7_horizontal-menu.default", function ($widget, $) {
            $(document).ready(function () {
                $widget.horizontalMenu();
            })
        });
    });
})(jQuery);
