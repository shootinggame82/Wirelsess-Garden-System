<?php
include '../../i18n_setup.php';
 ?>
"use strict";

function omstart() {
  //För att starta & stoppa aktuell pump
  var r = confirm("<?=gettext('Du är på väg att starta om styrenheten, är du helt säker på det?')?>");
  if (r == true) {
    jQuery.ajax({
      type: "POST",
      url: "ajax/omstart.php",
      data: {
        id: '1',
      },
      datatype: 'html',
      success: function(data) {
        var mydata = $.parseJSON(data);
        var fel = mydata.error;
        var kod = mydata.errorcode;
        if (fel == "true") {
          if (kod == "1") {
            alert('<?=gettext('Vi har tekniska problem med databasen just nu, kontakta admin.')?>');
          } else if (kod == "2") {
            alert('<?=gettext('Det är problem med att skicka just nu, kontakta admin.')?>');
          }
        } else if (fel == "false") {

          alert('<?=gettext('Styrenheten kommer att startas om nu.')?>');
        }
      }
    });
  } else {
    alert('<?=gettext('Styrenheten har INTE startats om.')?>');
  }
}

$(document).ready(function() {

  if (("standalone" in window.navigator) && window.navigator.standalone) {
    var noddy, remotes = false;
    document.addEventListener('click', function(event) {
      noddy = event.target;
      while (noddy.nodeName !== "A" && noddy.nodeName !== "HTML") {
        noddy = noddy.parentNode;
      }
      if ('href' in noddy && noddy.href.indexOf('http') !== -1 && (noddy.href.indexOf(document.location.host) !== -1 || remotes)) {
        event.preventDefault();
        document.location.href = noddy.href;
      }
    }, false);
  }
  // card js start
  $(".card-header-right .close-card").on('click', function() {
    var $this = $(this);
    $this.parents('.card').animate({
      'opacity': '0',
      '-webkit-transform': 'scale3d(.3, .3, .3)',
      'transform': 'scale3d(.3, .3, .3)'
    });

    setTimeout(function() {
      $this.parents('.card').remove();
    }, 800);
  });
  $(".card-header-right .reload-card").on('click', function() {
    var $this = $(this);
    $this.parents('.card').addClass("card-load");
    $this.parents('.card').append('<div class="card-loader"><i class="feather icon-radio rotate-refresh"></div>');
    setTimeout(function() {
      $this.parents('.card').children(".card-loader").remove();
      $this.parents('.card').removeClass("card-load");
    }, 3000);
  });
  $(".card-header-right .card-option .open-card-option").on('click', function() {
    var $this = $(this);
    if ($this.hasClass('icon-x')) {
      $this.parents('.card-option').animate({
        'width': '30px',
      });
      $(".open-card-option").removeClass("icon-x").fadeIn('slow');
      $(".open-card-option").addClass("icon-chevron-left").fadeIn('slow');
      $this.parents('.card-option').children(".first-opt").fadeIn();
    } else {
      $this.parents('.card-option').animate({
        'width': '130px',
      });
      $(".open-card-option").addClass("icon-x").fadeIn('slow');
      $(".open-card-option").removeClass("icon-chevron-left").fadeIn('slow');
      $this.parents('.card-option').children(".first-opt").fadeOut();
    }
  });
  $(".card-header-right .minimize-card").on('click', function() {
    var $this = $(this);
    var port = $($this.parents('.card'));
    var card = $(port).children('.card-block').slideToggle();
    $(this).toggleClass("icon-minus").fadeIn('slow');
    $(this).toggleClass("icon-plus").fadeIn('slow');
  });
  $(".card-header-right .full-card").on('click', function() {
    var $this = $(this);
    var port = $($this.parents('.card'));
    port.toggleClass("full-card");
    $(this).toggleClass("icon-minimize");
    $(this).toggleClass("icon-maximize");
  });
  $("#more-details").on('click', function() {
    $(".more-details").slideToggle(500);
  });
  $(".mobile-options").on('click', function() {
    $(".navbar-container .nav-right").slideToggle('slow');
  });
  $(".search-btn").on('click', function() {
    $(".main-search").addClass('open');
    $('.main-search .form-control').animate({
      'width': '200px',
    });
  });
  $(".search-close").on('click', function() {
    $('.main-search .form-control').animate({
      'width': '0',
    });
    setTimeout(function() {
      $(".main-search").removeClass('open');
    }, 300);
  });
  // card js end
  // $.mCustomScrollbar.defaults.axis = "yx";
  $("#styleSelector .style-cont").slimScroll({
    setTop: "1px",
    height: "calc(100vh - 520px)",
  });

  /*chatbar js start*/
  /*chat box scroll*/
  var a = $(window).height() - 80;
  $(".main-friend-list").slimScroll({
    height: a,
    allowPageScroll: false,
    wheelStep: 5
  });
  var a = $(window).height() - 155;
  $(".main-friend-chat").slimScroll({
    height: a,
    allowPageScroll: false,
    wheelStep: 5
  });

  // search
  $("#search-friends").on("keyup", function() {
    var g = $(this).val().toLowerCase();
    $(".userlist-box .media-body .chat-header").each(function() {
      var s = $(this).text().toLowerCase();
      $(this).closest('.userlist-box')[s.indexOf(g) !== -1 ? 'show' : 'hide']();
    });
  });

  // open chat box
  $('.displayChatbox').on('click', function() {
    var my_val = $('.pcoded').attr('vertical-placement');
    if (my_val == 'right') {
      var options = {
        direction: 'left'
      };
    } else {
      var options = {
        direction: 'right'
      };
    }
    $('.showChat').toggle('slide', options, 500);
  });

  //open friend chat
  $('.userlist-box').on('click', function() {
    var my_val = $('.pcoded').attr('vertical-placement');
    if (my_val == 'right') {
      var options = {
        direction: 'left'
      };
    } else {
      var options = {
        direction: 'right'
      };
    }
    $('.showChat_inner').toggle('slide', options, 500);
  });
  //back to main chatbar
  $('.back_chatBox').on('click', function() {
    var my_val = $('.pcoded').attr('vertical-placement');
    if (my_val == 'right') {
      var options = {
        direction: 'left'
      };
    } else {
      var options = {
        direction: 'right'
      };
    }
    $('.showChat_inner').toggle('slide', options, 500);
    $('.showChat').css('display', 'block');
  });
  $('.back_friendlist').on('click', function() {
    var my_val = $('.pcoded').attr('vertical-placement');
    if (my_val == 'right') {
      var options = {
        direction: 'left'
      };
    } else {
      var options = {
        direction: 'right'
      };
    }
    $('.p-chat-user').toggle('slide', options, 500);
    $('.showChat').css('display', 'block');
  });
  // /*chatbar js end*/

  $('[data-toggle="tooltip"]').tooltip();

  // wave effect js
  Waves.init();
  Waves.attach('.flat-buttons', ['waves-button']);
  Waves.attach('.float-buttons', ['waves-button', 'waves-float']);
  Waves.attach('.float-button-light', ['waves-button', 'waves-float', 'waves-light']);
  Waves.attach('.flat-buttons', ['waves-button', 'waves-float', 'waves-light', 'flat-buttons']);

  $('#mobile-collapse i').addClass('icon-toggle-right');
  $('#mobile-collapse').on('click', function() {
    $('#mobile-collapse i').toggleClass('icon-toggle-right');
    $('#mobile-collapse i').toggleClass('icon-toggle-left');
  });
});
$(document).ready(function() {
  var $window = $(window);
  // $('.loader-bar').animate({
  //     width: $window.width()
  // }, 1000);
  // setTimeout(function() {
  // while ($('.loader-bar').width() == $window.width()) {
  // $(window).on('load',function(){
  $('.loader-bg').fadeOut();
  // });

  // break;

  // }
  // }, 2000);
});

// toggle full screen
function toggleFullScreen() {
  var a = $(window).height() - 10;

  if (!document.fullscreenElement && // alternative standard method
    !document.mozFullScreenElement && !document.webkitFullscreenElement) { // current working methods
    if (document.documentElement.requestFullscreen) {
      document.documentElement.requestFullscreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullscreen) {
      document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  }
  $('.full-screen').toggleClass('icon-maximize');
  $('.full-screen').toggleClass('icon-minimize');
}

/* --------------------------------------------------------
        Color picker - demo only
-------------------------------------------------------- */
$('#styleSelector').append('' +
  '<div class="selector-toggle">' +
  '<a href="javascript:void(0)" class="waves-effect waves-light"></a>' +
  '</div>' +
  '<ul>' +
  '<li>' +
  '<p class="selector-title main-title st-main-title"><b>Able-pro </b>7.0 Customizer</p>' +
  '<span class="text-muted">Live customizer with tons of options</span>' +
  '</li>' +
  '<li>' +
  '<p class="selector-title">Main layouts</p>' +
  '</li>' +
  '<li>' +
  '<div class="theme-color">' +
  '<a href="#" data-toggle="tooltip" title="light Navbar" class="navbar-theme waves-effect waves-light" navbar-theme="themelight1"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" data-toggle="tooltip" title="Dark Navbar" class="navbar-theme waves-effect waves-light" navbar-theme="theme1"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" data-toggle="tooltip" title="light Layout" class="Layout-type waves-effect waves-light" layout-type="light"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" data-toggle="tooltip" title="Dark Layout" class="Layout-type waves-effect waves-light" layout-type="dark"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" data-toggle="tooltip" title="Reset Default" class="Layout-type waves-effect waves-light" layout-type="reset"><i class="feather icon-power"></i></a>' +
  '</div>' +
  '</li>' +
  '</ul>' +
  '<div class="style-cont m-t-10">' +
  '<ul class="nav nav-tabs  tabs" role="tablist">' +
  '<li class="nav-item waves-effect waves-light"><a class="nav-link active" data-toggle="tab" href="#tb-layout" role="tab">Layouts</a></li>' +
  '<li class="nav-item waves-effect waves-light"><a class="nav-link" data-toggle="tab" href="#tb-sidebar" role="tab">Sidebar Settings</a></li>' +
  '</ul>' +
  '<div class="tab-content tabs">' +
  '<div class="tab-pane active" id="tb-layout" role="tabpanel">' +
  '<ul>' +
  '<li class="theme-option">' +
  '<div class="checkbox-fade fade-in-primary">' +
  '<label>' +
  '<input type="checkbox" value="false" id="theme-layout" name="vertical-item-border">' +
  '<span class="cr"><i class="cr-icon feather icon-check txt-success f-w-600"></i></span>' +
  '<span>Box Layout - with background color</span>' +
  '</label>' +
  '</div>' +
  '</li>' +
  '<li class="theme-option d-none" id="bg-pattern-visiblity">' +
  '<div class="theme-color">' +
  '<a href="#" class="themebg-pattern small waves-effect waves-light" themebg-pattern="theme1">&nbsp;</a>' +
  '<a href="#" class="themebg-pattern small waves-effect waves-light" themebg-pattern="theme2">&nbsp;</a>' +
  '<a href="#" class="themebg-pattern small waves-effect waves-light" themebg-pattern="theme3">&nbsp;</a>' +
  '<a href="#" class="themebg-pattern small waves-effect waves-light" themebg-pattern="theme4">&nbsp;</a>' +
  '<a href="#" class="themebg-pattern small waves-effect waves-light" themebg-pattern="theme5">&nbsp;</a>' +
  '<a href="#" class="themebg-pattern small waves-effect waves-light" themebg-pattern="theme6">&nbsp;</a>' +
  '</div>' +
  '</li>' +
  '<li class="theme-option">' +
  '<div class="checkbox-fade fade-in-primary">' +
  '<label>' +
  '<input type="checkbox" value="false" id="sidebar-position" name="sidebar-position">' +
  '<span class="cr"><i class="cr-icon feather icon-check txt-success f-w-600"></i></span>' +
  '<span>Fixed Sidebar Position</span>' +
  '</label>' +
  '</div>' +
  '</li>' +
  '<li class="theme-option">' +
  '<div class="checkbox-fade fade-in-primary">' +
  '<label>' +
  '<input type="checkbox" value="false" id="header-position" name="header-position">' +
  '<span class="cr"><i class="cr-icon feather icon-check txt-success f-w-600"></i></span>' +
  '<span>Fixed Header Position</span>' +
  '</label>' +
  '</div>' +
  '</li>' +
  '</ul>' +
  '</div>' +
  '<div class="tab-pane" id="tb-sidebar" role="tabpanel">' +
  '<ul>' +
  '<li class="theme-option">' +
  '<p class="sub-title drp-title">Menu Type</p>' +
  '<div class="form-radio" id="menu-effect">' +
  '<div class="radio radio-primary radio-inline" data-toggle="tooltip" title="Color icon">' +
  '<label>' +
  '<input type="radio" name="radio" value="st1" onclick="handlemenutype(this.value)">' +
  '<i class="helper"></i><span class="micon st1"><i class="feather icon-bell"></i></span>' +
  '</label>' +
  '</div>' +
  '<div class="radio radio-inverse radio-inline" data-toggle="tooltip" title="simple icon">' +
  '<label>' +
  '<input type="radio" name="radio" value="st2" onclick="handlemenutype(this.value)" checked="true">' +
  '<i class="helper"></i><span class="micon st2"><i class="feather icon-bell"></i></span>' +
  '</label>' +
  '</div>' +
  '</div>' +
  '</li>' +
  '<li class="theme-option">' +
  '<p class="sub-title drp-title">SideBar Effect</p>' +
  '<select id="vertical-menu-effect" class="form-control minimal">' +
  '<option name="vertical-menu-effect" value="shrink" selected>Shrink</option>' +
  '<option name="vertical-menu-effect" value="overlay">Overlay</option>' +
  '<option name="vertical-menu-effect" value="push">Push</option>' +
  '</select>' +
  '</li>' +
  '<li class="theme-option">' +
  '<p class="sub-title drp-title">Hide/Show Border</p>' +
  '<select id="vertical-border-style" class="form-control minimal">' +
  '<option name="vertical-border-style" value="solid" selected>Solid</option>' +
  '<option name="vertical-border-style" value="dotted">Dotted</option>' +
  '<option name="vertical-border-style" value="dashed">Dashed</option>' +
  '<option name="vertical-border-style" value="none">No Border</option>' +
  '</select>' +
  '</li>' +
  '<li class="theme-option">' +
  '<p class="sub-title drp-title">Drop-Down Icon</p>' +
  '<select id="vertical-dropdown-icon" class="form-control minimal">' +
  '<option name="vertical-dropdown-icon" value="style1" selected>Style 1</option>' +
  '<option name="vertical-dropdown-icon" value="style2">style 2</option>' +
  '<option name="vertical-dropdown-icon" value="style3">style 3</option>' +
  '</select>' +
  '</li>' +
  '<li class="theme-option">' +
  '<p class="sub-title drp-title">Sub Menu Drop-down Icon</p>' +
  '<select id="vertical-subitem-icon" class="form-control minimal">' +
  '<option name="vertical-subitem-icon" value="style1" selected>Style 1</option>' +
  '<option name="vertical-subitem-icon" value="style2">style 2</option>' +
  '<option name="vertical-subitem-icon" value="style3">style 3</option>' +
  '<option name="vertical-subitem-icon" value="style4">style 4</option>' +
  '<option name="vertical-subitem-icon" value="style5">style 5</option>' +
  '<option name="vertical-subitem-icon" value="style6">style 6</option>' +
  '<option name="vertical-subitem-icon" value="style7">No Icon</option>' +
  '</select>' +
  '</li>' +
  '</ul>' +
  '</div>' +
  '<ul>' +
  '<li>' +
  '<p class="selector-title">Header color</p>' +
  '</li>' +
  '<li class="theme-option">' +
  '<div class="theme-color">' +
  '<a href="#" class="header-theme waves-effect waves-light" header-theme="theme1" active-item-color="theme1"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" class="header-theme waves-effect waves-light" header-theme="theme2" active-item-color="theme2"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" class="header-theme waves-effect waves-light" header-theme="theme3" active-item-color="theme3"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" class="header-theme waves-effect waves-light" header-theme="theme4" active-item-color="theme4"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" class="header-theme waves-effect waves-light" header-theme="theme5" active-item-color="theme5"><span class="head"></span><span class="cont"></span></a>' +
  '<a href="#" class="header-theme waves-effect waves-light" header-theme="theme6" active-item-color="theme6"><span class="head"></span><span class="cont"></span></a>' +
  '</div>' +
  '</li>' +
  '<li>' +
  '<p class="selector-title">Active link color</p>' +
  '</li>' +
  '<li class="theme-option">' +
  '<div class="theme-color">' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme1">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme2">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme3">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme4">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme5">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme6">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme7">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme8">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme9">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme10">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme11">&nbsp;</a>' +
  '<a href="#" class="active-item-theme small waves-effect waves-light" active-item-theme="theme12">&nbsp;</a>' +
  '</div>' +
  '</li>' +
  '<li>' +
  '<p class="selector-title">Menu Caption Color</p>' +
  '</li>' +
  '<li class="theme-option">' +
  '<div class="theme-color">' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme1">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme2">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme3">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme4">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme5">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme6">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme7">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme8">&nbsp;</a>' +
  '<a href="#" class="leftheader-theme small waves-effect waves-light" menu-caption="theme9">&nbsp;</a>' +
  '</div>' +
  '</li>' +
  '</ul>' +
  '</div>' +
  '</div>' +
  '<ul>' +
  '<li>' +
  '<a href="#" class="btn btn-success btn-block m-r-15 m-t-10 m-b-10 waves-effect waves-light">Profile</a>' +
  '<a href="http://ableproadmin.com/doc-7.0/" target="_blank" class="btn btn-primary btn-block m-r-15 m-t-5 m-b-10 waves-effect waves-light">Online Documentation</a>' +
  '</li>' +
  '<li class="text-center">' +
  '<span class="text-center f-18 m-t-15 m-b-15 d-block">Thank you for sharing !</span>' +
  '<a href="https://www.facebook.com/phoenixcoded" target="_blank" class="btn btn-facebook soc-icon m-b-20 waves-effect waves-light"><i class="feather icon-facebook"></i></a>' +
  '<a href="https://twitter.com/phoenixcoded" target="_blank" class="btn btn-twitter soc-icon m-l-20 m-b-20 waves-effect waves-light"><i class="feather icon-twitter"></i></a>' +
  '</li>' +
  '</ul>' +
  '');
