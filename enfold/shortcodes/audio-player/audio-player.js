!function(t){var n={};function e(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,e),r.l=!0,r.exports}e.m=t,e.c=n,e.d=function(t,n,o){e.o(t,n)||Object.defineProperty(t,n,{enumerable:!0,get:o})},e.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},e.t=function(t,n){if(1&n&&(t=e(t)),8&n)return t;if(4&n&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(e.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&n&&"string"!=typeof t)for(var r in t)e.d(o,r,function(n){return t[n]}.bind(null,r));return o},e.n=function(t){var n=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(n,"a",n),n},e.o=function(t,n){return Object.prototype.hasOwnProperty.call(t,n)},e.p="",e(e.s=0)}([function(t,n){!function(t){"use strict";var n=!1;t.fn.aviaPlayer=function(e){if(this.length)return this.each((function(){var e={};if(e.container=t(this),e.stopLoop=!1,e.container.find("audio").on("play",(function(){e.stopLoop&&(this.pause(),e.stopLoop=!1)})),e.container.hasClass("avia-playlist-no-loop")&&e.container.find("audio").on("ended",(function(){var t=e.container.find(".wp-playlist-tracks .wp-playlist-item:last a");this.currentSrc===t.attr("href")&&(e.stopLoop=!0)})),e.container.hasClass("avia-playlist-autoplay")&&!n){if("none"==e.container.css("display")||"hidden"==e.container.css("visibility"))return;n=!0,setTimeout((function(){!function t(n){var e=n.find(".av-player-player-container .mejs-playpause-button");0==e.length&&setTimeout((function(){t(n)}),200),e.hasClass("mejs-pause")||e.trigger("click")}(e.container)}),200)}}))}}(jQuery)}]);
//# sourceMappingURL=audio-player.js.map