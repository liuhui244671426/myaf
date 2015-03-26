/**
 * Created by huiliu on 14-10-14.
 * @Des:
 */
define(function (require) {
    var Swipe = require('/scripts/page/show/hhSwipe.js').swipe;

    var bullets = document.getElementById('scroll_position').getElementsByTagName('li');
    var slider = Swipe(document.getElementById('scroll_img'), {
        auto: 3000,
        continuous: true,
        callback: function(pos) {
            var i = bullets.length;
            while (i--) {
                bullets[i].className = ' ';
            }
            bullets[pos].className = 'on';
        }
    });
});