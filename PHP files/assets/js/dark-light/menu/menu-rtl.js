"use strict!"
$(document).ready(function() {
    // variable
    var noofdays = 1;                       //  total no of days cookie will store
    var Navbarbg = "theme1";           //  navbar color                themelight1 / theme1
    var headerbg = "theme1";                //  header color                theme1 / theme2 / theme3 / theme4 / theme5 / theme6
    var menucaption = "theme9";             //  menu caption color          theme1 / theme2 / theme3 / theme4 / theme5 / theme6 / theme7 / theme8 / theme9
    var bgpattern = "theme1";               //  background color            theme1 / theme2 / theme3 / theme4 / theme5 / theme6
    var activeitemtheme = "theme1";         //  menu active color           theme1 / theme2 / theme3 / theme4 / theme5 / theme6 / theme7 / theme8 / theme9 / theme10 / theme11 / theme12
    var frametype = "theme1";               //  preset frame color          theme1 / theme2 / theme3 / theme4 / theme5 / theme6
    var layout_type = "light";              //  theme layout color          dark / light
    var layout_width = "wide";              //  theme layout size           wide / box
    var menu_effect_desktop = "shrink";     //  navbar effect in desktop    shrink / overlay / push
    var menu_effect_tablet = "overlay";     //  navbar effect in tablet     shrink / overlay / push
    var menu_effect_phone = "overlay";      //  navbar effect in phone      shrink / overlay / push
    var menu_icon_style = "st2";            //  navbar menu icon            st1 / st2
    function setCookie(cname, cvalue, exdays) {
		var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(noofdays);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    function checkCookie() {
        Navbarbg = (getCookie("NavbarBackground") != "") ? getCookie("NavbarBackground"): Navbarbg;
        headerbg = (getCookie("header-theme") != "") ? getCookie("header-theme"): headerbg;
        menucaption = (getCookie("menu-title-theme") != "") ? getCookie("menu-title-theme"): menucaption;
        bgpattern = (getCookie("themebg-pattern") != "") ? getCookie("themebg-pattern"): bgpattern;
        activeitemtheme = (getCookie("active-item-theme") != "") ? getCookie("active-item-theme"): activeitemtheme;
        frametype = (getCookie("fream-type") != "") ? getCookie("fream-type"): frametype;
        layout_type = (getCookie("layoutlayout") != "") ? getCookie("layoutlayout"): layout_type;
        layout_width = (getCookie("vertical-layout") != "") ? getCookie("vertical-layout"): layout_width;
        menu_effect_desktop = (getCookie("vertical-effect") != "") ? getCookie("vertical-effect"): menu_effect_desktop;
        menu_icon_style = (getCookie("menu-icon-style") != "") ? getCookie("menu-icon-style"): menu_icon_style;
    }
    //checkCookie();
    $("#pcoded").pcodedmenu({
        themelayout: 'vertical',
        verticalMenuplacement: 'right', // value should be left/right
        verticalMenulayout: layout_width,
        MenuTrigger: 'click', // click / hover
        SubMenuTrigger: 'click', // click / hover
        activeMenuClass: 'active',
        ThemeBackgroundPattern: bgpattern,
        HeaderBackground: headerbg,
        LHeaderBackground: menucaption,
        NavbarBackground: Navbarbg,
        ActiveItemBackground: activeitemtheme,
        SubItemBackground: 'theme2',
        ActiveItemStyle: 'style0',
        ItemBorder: true,
        ItemBorderStyle: 'solid',
        freamtype: frametype,
        SubItemBorder: true,
        DropDownIconStyle: 'style1', // Value should be style1,style2,style3
        menutype: menu_icon_style,
        layouttype: layout_type,
        FixedNavbarPosition: false, // Value should be true / false  header postion
        FixedHeaderPosition: false, // Value should be true / false  sidebar menu postion
        collapseVerticalLeftHeader: true,
        VerticalSubMenuItemIconStyle: 'style1', // value should be style1, style2, style3, style4, style5, style6
        VerticalNavigationView: 'view1',
        verticalMenueffect: {
            desktop: menu_effect_desktop,
            tablet: menu_effect_tablet,
            phone: menu_effect_phone,
        },
        defaultVerticalMenu: {
            desktop: "expanded", // value should be offcanvas/collapsed/expanded/compact/compact-acc/fullpage/ex-popover/sub-expanded
            tablet: "offcanvas", // value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
            phone: "offcanvas", // value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
        },
        onToggleVerticalMenu: {
            desktop: "collapsed", // value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
            tablet: "expanded", // value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
            phone: "expanded", // value should be offcanvas/collapsed/expanded/compact/fullpage/ex-popover/sub-expanded
        },

    });
    /* layout type Change function Start */
    function handlelayouttheme() {
        $('.theme-color > a.Layout-type').on("click", function() {
            var layout = $(this).attr("layout-type");
            $('.pcoded').attr("layout-type", layout);
            setCookie("layoutlayout", layout, noofdays);
            if (layout == 'dark') {
                $('.pcoded-header').attr("header-theme", "theme2");
                $('.pcoded-navbar').attr("navbar-theme", "theme1");
                $('.pcoded-navbar').attr("active-item-theme", "theme2");
                $('.pcoded').attr("fream-type", "theme2");
                $('body').addClass('dark');
                $('body').attr("themebg-pattern", "theme2");
                $('.pcoded-navigation-label').attr("menu-title-theme", "theme9");
                setCookie("header-theme", "theme2", noofdays);
                setCookie("NavbarBackground", "theme1", noofdays);
                setCookie("menu-title-theme", "theme9", noofdays);
                setCookie("themebg-pattern", "theme2", noofdays);
                setCookie("fream-type", "theme2", noofdays);
                setCookie("active-item-theme", "theme2", noofdays);
            }
            if (layout == 'light') {
                $('.pcoded-header').attr("header-theme", "theme1");
                $('.pcoded-navbar').attr("navbar-theme", "themelight1");
                $('.pcoded-navigation-label').attr("menu-title-theme", "theme1");
                $('.pcoded-navbar').attr("active-item-theme", "theme1");
                $('.pcoded').attr("fream-type", "theme1");
                $('body').removeClass('dark');
                $('body').attr("themebg-pattern", "theme1");
                setCookie("header-theme", "theme1", noofdays);
                setCookie("NavbarBackground", "themelight1", noofdays);
                setCookie("menu-title-theme", "theme1", noofdays);
                setCookie("themebg-pattern", "theme1", noofdays);
                setCookie("fream-type", "theme1", noofdays);
                setCookie("active-item-theme", "theme1", noofdays);
            }
            if (layout == 'reset') {
                setCookie("NavbarBackground", null, 0);
                setCookie("header-theme", null, 0);
                setCookie("menu-title-theme", null, 0);
                setCookie("themebg-pattern", null, 0);
                setCookie("active-item-theme", null, 0);
                setCookie("fream-type", null, 0);
                setCookie("layoutlayout", null, 0);
                setCookie("vertical-layout", null, 0);
                setCookie("vertical-effect", null, 0);
                location.reload();
            }
        });
    };
    handlelayouttheme();

    /* Left header Theme Change function Start */
    function handleleftheadertheme() {
        $('.theme-color > a.leftheader-theme').on("click", function() {
            var lheadertheme = $(this).attr("menu-caption");
            $('.pcoded-navigation-label').attr("menu-title-theme", lheadertheme);
            setCookie("menu-title-theme", lheadertheme, noofdays);
        });
    };
    handleleftheadertheme();
    /* Left header Theme Change function Close */
    /* header Theme Change function Start */
    function handleheadertheme() {
        $('.theme-color > a.header-theme').on("click", function() {
            var headertheme = $(this).attr("header-theme");
            var activeitem = $(this).attr("active-item-color");
            $('.pcoded-header').attr("header-theme", headertheme);
            $('.pcoded-navbar').attr("active-item-theme", activeitem);
            $('.pcoded').attr("fream-type", headertheme);
            $('.pcoded-navigation-label').attr("menu-title-theme", headertheme);
            $('body').attr("themebg-pattern", headertheme);

            // coockies
            setCookie("header-theme", headertheme, noofdays);
            setCookie("active-item-theme", activeitem, noofdays);
            setCookie("menu-title-theme", headertheme, noofdays);
            setCookie("themebg-pattern", headertheme, noofdays);
            setCookie("fream-type", headertheme, noofdays);
        });
    };
    handleheadertheme();
    /* header Theme Change function Close */
    /* Navbar Theme Change function Start */
    function handlenavbartheme() {
        $('.theme-color > a.navbar-theme').on("click", function() {
            var navbartheme = $(this).attr("navbar-theme");
            $('.pcoded-navbar').attr("navbar-theme", navbartheme);
            setCookie("NavbarBackground", navbartheme, noofdays);
            if (navbartheme == 'themelight1') {
                $('.pcoded-navigation-label').attr("menu-title-theme", "theme1");
                setCookie("menu-title-theme", "theme1", noofdays);
            }
            if (navbartheme == 'theme1') {
                $('.pcoded-navigation-label').attr("menu-title-theme", "theme9");
                setCookie("menu-title-theme", "theme9", noofdays);
            }
        });
    };

    handlenavbartheme();
    /* Navbar Theme Change function Close */
    /* Active Item Theme Change function Start */
    function handleactiveitemtheme() {
        $('.theme-color > a.active-item-theme').on("click", function() {
            var activeitemtheme = $(this).attr("active-item-theme");
            $('.pcoded-navbar').attr("active-item-theme", activeitemtheme);
            setCookie("active-item-theme", activeitemtheme, noofdays);
        });
    };

    handleactiveitemtheme();
    /* Active Item Theme Change function Close */

    /* Theme background pattren Change function Start */
    function handlethemebgpattern() {
        $('.theme-color > a.themebg-pattern').on("click", function() {
            var themebgpattern = $(this).attr("themebg-pattern");
            $('body').attr("themebg-pattern", themebgpattern);
            setCookie("themebg-pattern", themebgpattern, noofdays);
        });
    };

    handlethemebgpattern();
    /* Theme background pattren Change function Close */

    /* Theme Layout Change function start*/
    function handlethemeverticallayout() {
        $('#theme-layout').change(function() {
            if ($(this).is(":checked")) {
                $('.pcoded').attr('vertical-layout', "box");
                setCookie("vertical-layout", "box", noofdays);
                $('#bg-pattern-visiblity').removeClass('d-none');

            } else {
                $('.pcoded').attr('vertical-layout', "wide");
                setCookie("vertical-layout", "wide", noofdays);
                $('#bg-pattern-visiblity').addClass('d-none');
            }
        });
    };
    handlethemeverticallayout();
    /* Theme Layout Change function Close*/
    /* Menu effect change function start*/
    function handleverticalMenueffect() {
        $('#vertical-menu-effect').val('shrink').on('change', function(get_value) {
            get_value = $(this).val();
            $('.pcoded').attr('vertical-effect', get_value);
            setCookie("vertical-effect", get_value, noofdays);
        });
    };

    handleverticalMenueffect();
    /* Menu effect change function Close*/

    /* Vertical Item border Style change function Start*/
    function handleverticalboderstyle() {
        $('#vertical-border-style').val('solid').on('change', function(get_value) {
            get_value = $(this).val();
            $('.pcoded-navbar .pcoded-item').attr('item-border-style', get_value);
        });
    };

    handleverticalboderstyle();
    /* Vertical Item border Style change function Close*/

    /* Vertical Dropdown Icon change function Start*/
    function handleVerticalDropDownIconStyle() {
        $('#vertical-dropdown-icon').val('style1').on('change', function(get_value) {
            get_value = $(this).val();
            $('.pcoded-navbar .pcoded-hasmenu').attr('dropdown-icon', get_value);
        });
    };

    handleVerticalDropDownIconStyle();
    /* Vertical Dropdown Icon change function Close*/
    /* Vertical SubItem Icon change function Start*/

    function handleVerticalSubMenuItemIconStyle() {
        $('#vertical-subitem-icon').val('style5').on('change', function(get_value) {
            get_value = $(this).val();
            $('.pcoded-navbar .pcoded-hasmenu').attr('subitem-icon', get_value);
        });
    };

    handleVerticalSubMenuItemIconStyle();
    /* Vertical SubItem Icon change function Close*/
    /* Vertical Navbar Position change function Start*/
    function handlesidebarposition() {
        $('#sidebar-position').change(function() {
            if ($(this).is(":checked")) {
                $('.pcoded-navbar').attr("pcoded-navbar-position", 'fixed');
                $('.pcoded-header .pcoded-left-header').attr("pcoded-lheader-position", 'fixed');
            } else {
                $('.pcoded-navbar').attr("pcoded-navbar-position", 'absolute');
                $('.pcoded-header .pcoded-left-header').attr("pcoded-lheader-position", 'relative');
            }
        });
    };

    handlesidebarposition();
    /* Vertical Navbar Position change function Close*/
    /* Vertical Header Position change function Start*/
    function handleheaderposition() {
        $('#header-position').change(function() {
            if ($(this).is(":checked")) {
                $('.pcoded-header').attr("pcoded-header-position", 'fixed');
                $('.pcoded-navbar').attr("pcoded-header-position", 'fixed');
                $('.pcoded-main-container').css('margin-top', $(".pcoded-header").outerHeight());
            } else {
                $('.pcoded-header').attr("pcoded-header-position", 'relative');
                $('.pcoded-navbar').attr("pcoded-header-position", 'relative');
                $('.pcoded-main-container').css('margin-top', '0px');
            }
        });
    };
    handleheaderposition();
    /* Vertical Header Position change function Close*/
    /*  collapseable Left Header Change Function Start here*/
    function handlecollapseLeftHeader() {
        $('#collapse-left-header').change(function() {
            if ($(this).is(":checked")) {
                $('.pcoded-header, .pcoded ').removeClass('iscollapsed');
                $('.pcoded-header, .pcoded').addClass('nocollapsed');
            } else {
                $('.pcoded-header, .pcoded').addClass('iscollapsed');
                $('.pcoded-header, .pcoded').removeClass('nocollapsed');
            }
        });
    };
    handlecollapseLeftHeader();
    /*  collapseable Left Header Change Function Close here*/
    function handlemenutype(get_value) {
        $('.pcoded').attr('nav-type', get_value);
        setCookie("menu-icon-style", get_value, noofdays);
    };

    handlemenutype("st2");
});
