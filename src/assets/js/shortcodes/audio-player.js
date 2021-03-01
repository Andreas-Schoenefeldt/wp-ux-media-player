import Widgets from 'js-widget-hooks';
import './components/player'

window.onload = function () {
    Widgets.init(null, {
        widgetClass: 'js-sh-widget'
    });

    // vimeo stuff


    window.setTimeout(function () {
        var frames = document.querySelectorAll('iframe');

        console.log(frames);

        for (var i = 0; i < frames.length; i++) {
            var ifram = frames[i];
            var player = new Player(iframe);

            player.on('play', function() {
                console.log('played the video!');
            });
        }


    }, 1000);

};
