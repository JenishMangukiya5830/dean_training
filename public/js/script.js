$("img").unveil(200);
$.validate();

// Non-refundable payment notice — only on book-now page
if ($('#nonRefundableModal').length) {
    $('#nonRefundableModal').modal('show');
    $('#nonRefundableConfirm').on('click', function () {
        $('#nonRefundableModal').modal('hide');
    });
}

function loadtocourse() {
    if ($("#course_select").val() == 'Select Course') {
        return alert("Please select the course to find the details.");
    }
    window.location = SITE_URL + "/course/detail/course/" + $("#course_select").val();
}

$('.btn-view-more').on('click', function (e) {
    e.preventDefault();
    var $this = $(this);
    var $collapse = $this.closest('div').find('.collapse');
    $collapse.collapse('toggle');
    if ($this.html() == "View details »") {
        $this.html("&laquo; Hide details");
    } else {
        $this.html("View details &raquo;");
    }
});

/*! jquery.cookie v1.4.1 | MIT */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?a(require("jquery")):a(jQuery)}(function(a){function b(a){return h.raw?a:encodeURIComponent(a)}function c(a){return h.raw?a:decodeURIComponent(a)}function d(a){return b(h.json?JSON.stringify(a):String(a))}function e(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(g," ")),h.json?JSON.parse(a):a}catch(b){}}function f(b,c){var d=h.raw?b:e(b);return a.isFunction(c)?c(d):d}var g=/\+/g,h=a.cookie=function(e,g,i){if(void 0!==g&&!a.isFunction(g)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setTime(+k+864e5*j)}return document.cookie=[b(e),"=",d(g),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=e?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;o>n;n++){var p=m[n].split("="),q=c(p.shift()),r=p.join("=");if(e&&e===q){l=f(r,g);break}e||void 0===(r=f(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return void 0===a.cookie(b)?!1:(a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b))}});

// function covoidNoticeClose() {
//     $.cookie('covid-alert-box', 'closed', {path: '/'});
// }
//
// if ($.cookie('covid-alert-box') !== 'closed') {
//     $('#covid19Notice').modal('show');
// }


// function covoidNotice2Close() {
//     $.cookie('covid-alert-2-box', 'closed', {path: '/'});
// }
//
// if ($.cookie('covid-alert-2-box') !== 'closed') {
//     $('#covid19Notice2').modal('show');
// }

// var leavingModel = ouibounce(document.getElementById('ouibounce-modal'), {
//     sitewide: true,
//     cookieName: 'leaving-customer-popup',
//     cookieDomain: '.deantraining.co.uk',
//     timer: 0
// });


