$(function() {

    var SliderModule = (function() {
        var pb = {};
        pb.el = $('#slider');
        pb.items = {
            panels: pb.el.find('.slider-wrapper > li'),
        }

        var SliderInterval,
            currentSlider = 0,
            nextSlider = 1,
            lengthSlider = pb.items.panels.length;

        pb.init = function(settings) {
            this.settings = settings || {duration: 8000};
            var items = this.items,
                lengthPanels = items.panels.length,
                output = '';

            for(var i = 0; i < lengthPanels; i++) {
                if(i == 0) {
                    output += '<li class="active"></li>';
                } else {
                    output += '<li></li>';
                }
            }

            $('#control-buttons').html(output);

            activateSlider();

            $('#control-buttons').on('click', 'li', function(e) {
                var $this = $(this);
                if(!(currentSlider === $this.index())) {
                    changePanel($this.index());
                }
            });

        }

        var activateSlider = function() {
            SliderInterval = setInterval(pb.startSlider, pb.settings.duration);
        }

        pb.startSlider = function() {
            var items = pb.items,
                controls = $('#control-buttons li');

            if(nextSlider >= lengthSlider) {
                nextSlider = 0;
                currentSlider = lengthSlider-1;
            }

            controls.removeClass('active').eq(nextSlider).addClass('active');
            items.panels.eq(currentSlider).fadeOut('slow');
            items.panels.eq(nextSlider).fadeIn('slow');

            currentSlider = nextSlider;
            nextSlider += 1;
        }

        var changePanel = function(id) {
            clearInterval(SliderInterval);
            var items = pb.items,
                controls = $('#control-buttons li');

            if(id >= lengthSlider) {
                id = 0;
            } else if(id < 0) {
                id = lengthSlider-1;
            }

            controls.removeClass('active').eq(id).addClass('active');
            items.panels.eq(currentSlider).fadeOut('slow');
            items.panels.eq(id).fadeIn('slow');

            currentSlider = id;
            nextSlider = id+1;

            activateSlider();
        }

        return pb;
    }());

    SliderModule.init({duration: 4000});

});
