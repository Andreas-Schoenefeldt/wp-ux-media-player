!function(e){var t={};function i(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,i),o.l=!0,o.exports}i.m=e,i.c=t,i.d=function(e,t,r){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},i.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(i.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)i.d(r,o,function(t){return e[t]}.bind(null,o));return r},i.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="",i(i.s=1)}([function(e,t,i){"use strict";var r,o,n,s;s=function(){var e=function(){"object"==typeof console&&(arguments[0]="[js-widget-hooks] "+arguments[0],console.error.apply(console,arguments))};return{widgetClass:"widget",widgetDataName:"widgets",scriptClass:"dyn-script",vars:{},registered:{},registeredFinalls:{},register:function(t,i,r){this.registered[t]&&e("[WARNING] Widget "+t+" was already registered: Override."),this.registered[t]=[i,r]},setOptions:function(e){e&&["widgetClass","scriptClass","widgetDataName"].forEach(function(t){void 0!==e[t]&&(this[t]=e[t])}.bind(this))},init:function(t,i){var r,o,n=[],s={},a=[],d=this;for(r in this.setOptions(i),this.registered)n[n.length]=[r,this.registered[r][1]],s[r]=[];for(o in n.sort((function(e,t){return void 0!==e[1]&&void 0===t[1]?-1:void 0===e[1]&&void 0!==t[1]||e[1]<t[1]?1:e[1]>t[1]?-1:0})),t||(t=document.querySelector("body")),t||e("No root element could be found - is the DOM loaded already?"),t.querySelectorAll("."+d.widgetClass).forEach((function(t){var i=d.widgetDataName,r=t.dataset[i],o=0;r?r.split(" ").forEach((function(i){void 0!==s[i]?(s[i].push(t),o++):(e("No method for widget "+i+" provided on %o",t),t.classList.add(d.widgetClass+"-config-error"))})):(e("missing data-"+i+" attribute on %o",t),t.classList.add(d.widgetClass+"-config-error")),o||t.classList.remove(d.widgetClass)})),n.forEach((function(e){var t=e[0];s[t].forEach((function(e){a[a.length]=e,d.initSpecific(e,t)})),d.registeredFinalls[t]&&d.registeredFinalls[t](),delete s[t]})),a)a[o].classList.remove(this.widgetClass)},initSpecific:function(t,i,r){try{(t.classList.contains(this.widgetClass)||r)&&(this.registered[i][0](t),t.classList.add(this.widgetClass+"-initialized"))}catch(r){return e("Error during execution of widget %o at %o:\n%o",i,t,r),t.classList.add(this.widgetClass+"-error"),!1}return!0}}},e.exports?e.exports=s():(o=[],void 0===(n="function"==typeof(r=s)?r.apply(t,o):r)||(e.exports=n))},function(e,t,i){"use strict";i.r(t);var r,o=i(0),n=i.n(o);r=jQuery,n.a.register("sh-ux-media-player",(function(e){var t=!1,i=r(e),o=i.find(".trigger--more"),n=o.text(),s=o.data("view_less"),a=i.data("item_count");console.log(a),o.click((function(e){var d=i.find(".wp-playlist-tracks"),c=r(d.children()[0]).outerHeight();t?(o.text(n),d.css({maxHeight:2*c+"px"})):(o.text(s),d.css({maxHeight:a*c+"px"})),t=!t}))})),window.onload=function(){n.a.init(null,{widgetClass:"js-sh-widget"})}}]);
//# sourceMappingURL=audio-player.js.map