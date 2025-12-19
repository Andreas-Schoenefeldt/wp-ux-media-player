import Widgets from 'js-widget-hooks';

var playerInitialized = function (player) {
    return player.find('.wp-playlist-tracks').length > 0;
};

var executeOnInitialized = function (player, callback) {
    if (!playerInitialized(player)) {
        window.setTimeout(executeOnInitialized.bind(null, player, callback), 50);
    } else {
        callback();
    }
};

(function($) {

    Widgets.register('sh-ux-media-player', function (elem) {
        var expanded = false;
        var el = $(elem);
        var more = el.find('.trigger--more');
        var moreTextEl = more.find('.target--text');
        var moreText = moreTextEl.text();
        var lessText = more.data('view_less');
        var downloadText = el.data('download_text');
        var shareText = el.data('share_text');
        var itemCount = el.data('item_count');
        var showCount = el.data('show_count');

        more.click(function (e) {
            // tracklist is initialized later on via js, so we reinit it here
            var trackList = el.find('.wp-playlist-tracks');
            var itemHeight = $(trackList.children()[0]).outerHeight();

            if (expanded) {
                el.removeClass('sh-player--expanded');
                moreTextEl.text(moreText);
                trackList.css({maxHeight: (showCount * itemHeight) + 'px'});
            } else {
                el.addClass('sh-player--expanded');
                moreTextEl.text(lessText);
                trackList.css({maxHeight: (itemCount * itemHeight + 1) + 'px'});
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

                var trackList = el.find('.wp-playlist-tracks');
                var itemHeight = $(trackList.children()[0]).outerHeight();

                // set the inital height
                trackList.css({maxHeight: (showCount * itemHeight + 1) + 'px'});

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