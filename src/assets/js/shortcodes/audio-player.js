import Widgets from 'js-widget-hooks';
import './components/player'

window.onload = function () {
    Widgets.init(null, {
        widgetClass: 'js-sh-widget'
    });

    // vimeo stuff - froogaloop api
    // https://stackoverflow.com/questions/6537415/vimeo-froogaloop-api-not-recognizing-an-event
    window.setTimeout(function () {
        var frames = document.querySelectorAll('iframe');
        var players = [];

        var id = 0;
        for (var i = 0; i < frames.length; i++) {
            var iframe = frames[i];
            var playerId = 'id-' + id;
            iframe.id = playerId;
            iframe.src = iframe.src + '&player_id=' + playerId;

            var player = Froogaloop(playerId);

            player.addEvent('play', function() {
                var currentId = parseInt(this.id.substr(3));

                console.log('playing ' + currentId);

                players.forEach(function (p, index) {
                    if (currentId !== index) {
                        p.api('pause');
                    }
                })
            }.bind(iframe));
            players.push(player);
            id++;
        }


    }, 500);

};
