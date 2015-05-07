define(function(require, exports){
    "use strict";

    var jQuery = require('jquery');
    require.async(['jqueryUI', 'jqueryDatatables', 'jqueryCookie','jqueryMorris','jqueryToggles','bootstrap','modernizr','retina']);

    jQuery(window).load(function () {
        // Page Preloader
        jQuery('#preloader').delay(350).fadeOut(function () {
            jQuery('body').delay(350).css({'overflow': 'visible'});
        });
    });

    jQuery(document).ready(function () {
        // Toggle Left Menu
        jQuery('.leftpanel .nav-parent > a').live('click', function () {

            var parent = jQuery(this).parent();
            var sub = parent.find('> ul');

            // Dropdown works only when leftpanel is not collapsed
            if (!jQuery('body').hasClass('leftpanel-collapsed')) {
                if (sub.is(':visible')) {
                    sub.slideUp(200, function () {
                        parent.removeClass('nav-active');
                        jQuery('.mainpanel').css({height: ''});
                        adjustmainpanelheight();
                    });
                } else {
                    closeVisibleSubMenu();
                    parent.addClass('nav-active');
                    sub.slideDown(200, function () {
                        adjustmainpanelheight();
                    });
                }
            }
            return false;
        });

        function closeVisibleSubMenu() {
            jQuery('.leftpanel .nav-parent').each(function () {
                var t = jQuery(this);
                if (t.hasClass('nav-active')) {
                    t.find('> ul').slideUp(200, function () {
                        t.removeClass('nav-active');
                    });
                }
            });
        }

        function adjustmainpanelheight() {
            // Adjust mainpanel height
            var docHeight = jQuery(document).height();
            if (docHeight > jQuery('.mainpanel').height())
                jQuery('.mainpanel').height(docHeight);
        }

        adjustmainpanelheight();

        // Close Button in Panels
        jQuery('.panel .panel-close').click(function () {
            jQuery(this).closest('.panel').fadeOut(200);
            return false;
        });

        // Form Toggles
        jQuery('.toggle').toggles({on: true});

        jQuery('.toggle-chat1').toggles({on: false});

        var scColor1 = '#428BCA';
        if (jQuery.cookie('change-skin') && jQuery.cookie('change-skin') == 'bluenav') {
            scColor1 = '#fff';
        }

        // Minimize Button in Panels
        jQuery('.minimize').click(function () {
            var t = jQuery(this);
            var p = t.closest('.panel');
            if (!jQuery(this).hasClass('maximize')) {
                p.find('.panel-body, .panel-footer').slideUp(200);
                t.addClass('maximize');
                t.html('&plus;');
            } else {
                p.find('.panel-body, .panel-footer').slideDown(200);
                t.removeClass('maximize');
                t.html('&minus;');
            }
            return false;
        });


        // Add class everytime a mouse pointer hover over it
        jQuery('.nav-bracket > li').hover(function () {
            jQuery(this).addClass('nav-hover');
        }, function () {
            jQuery(this).removeClass('nav-hover');
        });


        // Menu Toggle
        jQuery('.menutoggle').click(function () {

            var body = jQuery('body');
            var bodypos = body.css('position');

            if (bodypos != 'relative') {

                if (!body.hasClass('leftpanel-collapsed')) {
                    body.addClass('leftpanel-collapsed');
                    jQuery('.nav-bracket ul').attr('style', '');

                    jQuery(this).addClass('menu-collapsed');

                } else {
                    body.removeClass('leftpanel-collapsed chat-view');
                    jQuery('.nav-bracket li.active ul').css({display: 'block'});

                    jQuery(this).removeClass('menu-collapsed');

                }
            } else {

                if (body.hasClass('leftpanel-show'))
                    body.removeClass('leftpanel-show');
                else
                    body.addClass('leftpanel-show');

                adjustmainpanelheight();
            }

        });

        reposition_topnav();
        reposition_searchform();

        jQuery(window).resize(function () {
            if (jQuery('body').css('position') == 'relative') {
                jQuery('body').removeClass('leftpanel-collapsed chat-view');
            } else {
                jQuery('body').removeClass('chat-relative-view');
                jQuery('body').css({left: '', marginRight: ''});
            }

            reposition_topnav();
            reposition_searchform();
        });


        /* This function will reposition search form to the left panel when viewed
         * in screens smaller than 767px and will return to top when viewed higher
         * than 767px
         */
        function reposition_searchform() {
            if (jQuery('.searchform').css('position') == 'relative') {
                jQuery('.searchform').insertBefore('.leftpanelinner .userlogged');
            } else {
                jQuery('.searchform').insertBefore('.header-right');
            }
        }


        /* This function allows top navigation menu to move to left navigation menu
         * when viewed in screens lower than 1024px and will move it back when viewed
         * higher than 1024px
         */
        function reposition_topnav() {
            if (jQuery('.nav-horizontal').length > 0) {

                // top navigation move to left nav
                // .nav-horizontal will set position to relative when viewed in screen below 1024
                if (jQuery('.nav-horizontal').css('position') == 'relative') {

                    if (jQuery('.leftpanel .nav-bracket').length == 2) {
                        jQuery('.nav-horizontal').insertAfter('.nav-bracket:eq(1)');
                    } else {
                        // only add to bottom if .nav-horizontal is not yet in the left panel
                        if (jQuery('.leftpanel .nav-horizontal').length == 0)
                            jQuery('.nav-horizontal').appendTo('.leftpanelinner');
                    }

                    jQuery('.nav-horizontal').css({display: 'block'})
                        .addClass('nav-pills nav-stacked nav-bracket');

                    jQuery('.nav-horizontal .children').removeClass('dropdown-menu');
                    jQuery('.nav-horizontal > li').each(function () {

                        jQuery(this).removeClass('open');
                        jQuery(this).find('a').removeAttr('class');
                        jQuery(this).find('a').removeAttr('data-toggle');

                    });

                    if (jQuery('.nav-horizontal li:last-child').has('form')) {
                        jQuery('.nav-horizontal li:last-child form').addClass('searchform').appendTo('.topnav');
                        jQuery('.nav-horizontal li:last-child').hide();
                    }

                } else {
                    // move nav only when .nav-horizontal is currently from leftpanel
                    // that is viewed from screen size above 1024
                    if (jQuery('.leftpanel .nav-horizontal').length > 0) {

                        jQuery('.nav-horizontal').removeClass('nav-pills nav-stacked nav-bracket')
                            .appendTo('.topnav');
                        jQuery('.nav-horizontal .children').addClass('dropdown-menu').removeAttr('style');
                        jQuery('.nav-horizontal li:last-child').show();
                        jQuery('.searchform').removeClass('searchform').appendTo('.nav-horizontal li:last-child .dropdown-menu');
                        jQuery('.nav-horizontal > li > a').each(function () {

                            jQuery(this).parent().removeClass('nav-active');

                            if (jQuery(this).parent().find('.dropdown-menu').length > 0) {
                                jQuery(this).attr('class', 'dropdown-toggle');
                                jQuery(this).attr('data-toggle', 'dropdown');
                            }

                        });
                    }

                }

            }
        }


        // Sticky Header
        if (jQuery.cookie('sticky-header'))
            jQuery('body').addClass('stickyheader');

        // Sticky Left Panel
        if (jQuery.cookie('sticky-leftpanel')) {
            jQuery('body').addClass('stickyheader');
            jQuery('.leftpanel').addClass('sticky-leftpanel');
        }

        // Left Panel Collapsed
        if (jQuery.cookie('leftpanel-collapsed')) {
            jQuery('body').addClass('leftpanel-collapsed');
            jQuery('.menutoggle').addClass('menu-collapsed');
        }

        // Changing Skin
        var c = jQuery.cookie('change-skin');
        var cssSkin = 'css/style.' + c + '.css';
        if (jQuery('body').css('direction') == 'rtl') {
            cssSkin = '../css/style.' + c + '.css';
            jQuery('html').addClass('rtl');
        }
        if (c) {
            jQuery('head').append('<link id="skinswitch" rel="stylesheet" href="' + cssSkin + '" />');
        }

        // Changing Font
        var fnt = jQuery.cookie('change-font');
        if (fnt) {
            jQuery('head').append('<link id="fontswitch" rel="stylesheet" href="css/font.' + fnt + '.css" />');
        }

        // Check if leftpanel is collapsed
        if (jQuery('body').hasClass('leftpanel-collapsed'))
            jQuery('.nav-bracket .children').css({display: ''});


        // Handles form inside of dropdown
        jQuery('.dropdown-menu').find('form').click(function (e) {
            e.stopPropagation();
        });


        // This is not actually changing color of btn-primary
        // This is like you are changing it to use btn-orange instead of btn-primary
        // This is for demo purposes only
        var c = jQuery.cookie('change-skin');
        if (c && c == 'greyjoy') {
            $('.btn-primary').removeClass('btn-primary').addClass('btn-orange');
            $('.rdio-primary').addClass('rdio-default').removeClass('rdio-primary');
            $('.text-primary').removeClass('text-primary').addClass('text-orange');
        }
        initDataTable();
        function initDataTable(){
            jQuery('#table1').dataTable();
            jQuery('#table2').dataTable({
                "sPaginationType": "full_numbers"
            });

            //// Select2
            //jQuery('select').select2({
            //    minimumResultsForSearch: -1
            //});

            jQuery('select').removeClass('form-control');

            // Delete row in a table
            jQuery('.delete-row').click(function(){
                var c = confirm("Continue delete?");
                if(c)
                    jQuery(this).closest('tr').fadeOut(function(){
                        jQuery(this).remove();
                    });

                return false;
            });

            // Show aciton upon row hover
            jQuery('.table-hidaction tbody tr').hover(function(){
                jQuery(this).find('.table-action-hide a').animate({opacity: 1});
            },function(){
                jQuery(this).find('.table-action-hide a').animate({opacity: 0});
            });
        }
    });
});