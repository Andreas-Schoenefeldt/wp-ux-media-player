import Widgets from 'js-widget-hooks';

var playerInitialized = function (player) {
    return player.find('.wp-playlist-tracks').length > 0;
};

var executeOnInitialized = function (player, callback) {
    if (!playerInitialized(player)) {
        window.setTimeout(executeOnInitialized.bind(null, player, callback), 100);
    } else {
        callback();
    }
};

(function($) {

    Widgets.register('sh-ux-media-player', function (elem) {
        var expanded = false;
        var el = $(elem);
        var more = el.find('.trigger--more');
        var moreText = more.text();
        var lessText = more.data('view_less');
        var downloadText = el.data('download_text');
        var shareText = el.data('share_text');
        var itemCount = el.data('item_count');

        more.click(function (e) {
            // tracklist is initialized later on via js, so we reinit it here
            var trackList = el.find('.wp-playlist-tracks');
            var itemHeight = $(trackList.children()[0]).outerHeight();

            if (expanded) {
                more.text(moreText);
                trackList.css({maxHeight: (2 * itemHeight) + 'px'});
            } else {
                more.text(lessText);
                trackList.css({maxHeight: (itemCount * itemHeight) + 'px'});
            }

            expanded = !expanded;
        });

        executeOnInitialized(el, function () {
            el.find('.wp-playlist-item').each(function () {
                var item = $(this);
                var labelEl = item.find('a');
                var url = labelEl.attr('href');
                var buttons = $('<div class="sh-player__buttons"></div>');
                var btn = $('<span class="sh-player__icon-wrap" data-tooltip="' + downloadText + '"><button type="button" class="sh-player__icon sh-player__download">download</button></span>');
                var btn2 = $('<span class="sh-player__icon-wrap" data-tooltip="' + shareText + '"><button type="button" class="sh-player__icon sh-player__share">download</button></span>');

                btn.click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // thx to https://stackoverflow.com/a/37673039/2776727 - this allows to directly download this for modern browsers
                    var anchor = document.createElement('a');
                    anchor.href = url;
                    anchor.target = '_blank';
                    anchor.download = url.split(/\//gi).pop();
                    anchor.click();
                });

                btn2.click(function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var anchor = document.createElement('a');
                    anchor.href = 'mailto:?subject=' + labelEl.text() +  '&body=' + url;
                    anchor.click();
                });

                buttons.append(btn2);
                buttons.append(btn);

                item.append(buttons);
            });
        });

    });

})(jQuery);



// -------------------------------------------------------------------------------------------
//
// SH Player, based on Avia Player
//
// -------------------------------------------------------------------------------------------
// (function($) {
//     "use strict";
//
//     var autostarted = false,
//         container = null,
//
//         monitorStart = function( container )
//         {
//             var play_pause	= container.find('.sh-player-player-container .mejs-playpause-button');
//
//             if( play_pause.length == 0 )
//             {
//                 setTimeout( function(){
//                     monitorStart( container );
//                 }, 200 );
//             }
//
//             if( ! play_pause.hasClass('mejs-pause') )
//             {
//                 play_pause.trigger( 'click' );
//             }
//
//         };
//
//     $.fn.aviaPlayer = function( options ) {
//
//         console.log('SH PLAYER');
//
//         if( ! this.length ) return;
//
//         return this.each(function() {
//             var _self 			= {};
//
//             _self.container		= $( this );
//             _self.stopLoop		= false;
//
//
//             console.log(_self.container);
//
//             _self.container.find('audio').on('play', function() {
//                 if( _self.stopLoop )
//                 {
//                     this.pause();
//                     _self.stopLoop = false;
//                 }
//
//             });
//
//             if( _self.container.hasClass( 'avia-playlist-no-loop' ) )
//             {
//                 _self.container.find('audio').on('ended', function() {
//                     //	find the last track in the playlist so that when the last track ends we can pause the audio object
//                     var lastTrack	= _self.container.find('.wp-playlist-tracks .wp-playlist-item:last a');
//
//                     if ( this.currentSrc === lastTrack.attr('href') ) {
//                         _self.stopLoop = true;
//                     }
//
//                 });
//             }
//
//             /**
//              * Limit autostart to the first player with this option set only
//              *
//              * DOM is not loaded completely and we have no event when player is loaded.
//              * We check for play button and perform a click
//              */
// if( _self.container.hasClass( 'avia-playlist-autoplay' ) && ! autostarted )
// {
//     if( ( _self.container.css('display') == 'none') || ( _self.container.css("visibility") == "hidden" ) )
//     {
//         return;
//     }
//
//     autostarted = true;
//     setTimeout( function(){
//         monitorStart( _self.container, _self );
//     }, 200 );
// }
//
// });
// };
// }(jQuery));