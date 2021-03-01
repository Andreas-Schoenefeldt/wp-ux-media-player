import Widgets from 'js-widget-hooks';
import './components/player'

window.onload = function () {
    Widgets.init(null, {
        widgetClass: 'js-sh-widget'
    });

    // vimeo stuff


    window.setTimeout(function () {
        var frames = document.querySelectorAll('iframe');
        var players = [];

        var id = 0;
        for (var i = 0; i < frames.length; i++) {
            var iframe = frames[i];
            iframe.id = 'id-' + id;
            var player = new Vimeo.Player(iframe);

            player.on('play', function() {

                var currentId = parseInt(this.id.substr(3));
                console.log(currentId);

                console.log('played the video!', this);

                players.forEach(function (p, index) {
                    if (currentId !== index) {
                        p.pause();
                    }
                })

            }.bind(iframe));

            players.push(player);

            id++;
        }


    }, 1000);

};
