!function(t){var e={};function i(n){if(e[n])return e[n].exports;var r=e[n]={i:n,l:!1,exports:{}};return t[n].call(r.exports,r,r.exports,i),r.l=!0,r.exports}i.m=t,i.c=e,i.d=function(t,e,n){i.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:n})},i.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},i.t=function(t,e){if(1&e&&(t=i(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(i.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)i.d(n,r,function(e){return t[e]}.bind(null,r));return n},i.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return i.d(e,"a",e),e},i.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},i.p="",i(i.s=1)}([function(t,e,i){"use strict";var n,r,o,s;s=function(){var t=function(){"object"==typeof console&&(arguments[0]="[js-widget-hooks] "+arguments[0],console.error.apply(console,arguments))};return{widgetClass:"widget",widgetDataName:"widgets",scriptClass:"dyn-script",vars:{},registered:{},registeredFinalls:{},register:function(e,i,n){this.registered[e]&&t("[WARNING] Widget "+e+" was already registered: Override."),this.registered[e]=[i,n]},setOptions:function(t){t&&["widgetClass","scriptClass","widgetDataName"].forEach(function(e){void 0!==t[e]&&(this[e]=t[e])}.bind(this))},init:function(e,i){var n,r,o=[],s={},a=[],d=this;for(n in this.setOptions(i),this.registered)o[o.length]=[n,this.registered[n][1]],s[n]=[];for(r in o.sort((function(t,e){return void 0!==t[1]&&void 0===e[1]?-1:void 0===t[1]&&void 0!==e[1]||t[1]<e[1]?1:t[1]>e[1]?-1:0})),e||(e=document.querySelector("body")),e||t("No root element could be found - is the DOM loaded already?"),e.querySelectorAll("."+d.widgetClass).forEach((function(e){var i=d.widgetDataName,n=e.dataset[i],r=0;n?n.split(" ").forEach((function(i){void 0!==s[i]?(s[i].push(e),r++):(t("No method for widget "+i+" provided on %o",e),e.classList.add(d.widgetClass+"-config-error"))})):(t("missing data-"+i+" attribute on %o",e),e.classList.add(d.widgetClass+"-config-error")),r||e.classList.remove(d.widgetClass)})),o.forEach((function(t){var e=t[0];s[e].forEach((function(t){a[a.length]=t,d.initSpecific(t,e)})),d.registeredFinalls[e]&&d.registeredFinalls[e](),delete s[e]})),a)a[r].classList.remove(this.widgetClass)},initSpecific:function(e,i,n){try{(e.classList.contains(this.widgetClass)||n)&&(this.registered[i][0](e),e.classList.add(this.widgetClass+"-initialized"))}catch(n){return t("Error during execution of widget %o at %o:\n%o",i,e,n),e.classList.add(this.widgetClass+"-error"),!1}return!0}}},t.exports?t.exports=s():(r=[],void 0===(o="function"==typeof(n=s)?n.apply(e,r):n)||(t.exports=o))},function(t,e,i){"use strict";i.r(e);var n,r=i(0),o=i.n(r);n=jQuery,o.a.register("sh-ux-media-player",(function(t){var e=!1,i=n(t),r=i.find(".trigger--more"),o=r.find(".target--text"),s=o.text(),a=r.data("view_less"),d=i.data("download_text"),l=i.data("share_text"),c=i.data("item_count");r.click((function(t){var r=i.find(".wp-playlist-tracks"),d=n(r.children()[0]).outerHeight();e?(i.removeClass("sh-player--expanded"),o.text(s),r.css({maxHeight:2*d+"px"})):(i.addClass("sh-player--expanded"),o.text(a),r.css({maxHeight:c*d+"px"})),e=!e})),function t(e,i){!function(t){return t.find(".wp-playlist-tracks").length>0}(e)?window.setTimeout(t.bind(null,e,i),100):i()}(i,(function(){i.find(".wp-playlist-item").each((function(){var t=n(this),e=t.find("a"),i=e.attr("href"),r=n('<div class="sh-player__buttons"></div>'),o=n('<span class="sh-player__icon-wrap" data-tooltip="'+d+'"><button type="button" class="sh-player__icon sh-player__download">download</button></span>'),s=n('<span class="sh-player__icon-wrap" data-tooltip="'+l+'"><button type="button" class="sh-player__icon sh-player__share">download</button></span>');o.click((function(t){t.preventDefault(),t.stopPropagation();var e=document.createElement("a");e.href=i,e.target="_blank",e.download=i.split(/\//gi).pop(),e.click()})),s.click((function(t){t.preventDefault(),t.stopPropagation();var n=document.createElement("a");n.href="mailto:?subject="+e.text()+"&body="+i,n.click()})),r.append(s),r.append(o),t.append(r)}))}))})),window.onload=function(){o.a.init(null,{widgetClass:"js-sh-widget"})}}]);
//# sourceMappingURL=audio-player.js.map