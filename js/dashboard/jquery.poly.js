/*
 *  Polygon Click Plugin
 *  Author: Azeem Arshad (azeem.arshadt@gmail.com)
 *
 *  Usage
 *  -----
 *
 *  HTML:
 *  <a id="test" style="height:100px;width:100px;background-image:url(../images/kite_map.png);background-repeat:no-repeat;" href="#test" poly="0,0,10,0,10,10,0,10">Test</a>
 *
 *  Javascript:
 *  $('#test').clickpoly(function() { alert('clicked poly'); });
 *
 */

(function($) {

    function inside(poly, x, y){
        for(var c = false, i = -1, l = poly.length, j = l - 1; ++i < l; j = i) {
            ((poly[i][1] <= y && y < poly[j][1]) || (poly[j][1] <= y && y < poly[i][1]))
            && (x < (poly[j][0] - poly[i][0]) * (y - poly[i][1]) / (poly[j][1] - poly[i][1]) + poly[i][0])
            && (c = !c);
        }
        return c;
    }

    function get_pos(event, obj) {
        var offset = obj.offset(),
            x = event.pageX - offset.left,
            y = event.pageY - offset.top;
        return {x:x, y:y};
    }

    $.fn.poly = function() {
        return this.each(function() {
            var obj, orig_h, orig_w, coords, attr, over = false;
            obj = $(this);
            orig_h = obj.height();
            orig_w = obj.width();
            attr = obj.attr('poly').split(',');

            function compute_coords() {
                var w, h;
                w = obj.width(), h = obj.height();
                coords = [];
                for(var i = 0;i < attr.length;i+=2) {
                    var x = (parseInt(attr[i])/orig_w)*w,
                        y = (parseInt(attr[i+1])/orig_h)*h;
                    coords.push([x, y]);
                }
            }

            function move(event) {
                var pos = get_pos(event, obj);
                if(inside(coords, pos.x, pos.y)) {
                    obj.css('cursor', 'pointer');
                    if(!over) {
                        over = true;
                        return obj.triggerHandler('mouseenterpoly');
                    }
                }
                else {
                    obj.css('cursor', 'default');
                    if(over) {
                        over = false;
                        return obj.triggerHandler('mouseleavepoly');
                    }
                }
            }
            function leave(event) {
                if(over) {
                    over = false;
                    return obj.triggerHandler('mouseleavepoly');
                }
            }

            function click(event) {
                var pos = get_pos(event, obj);
                if(inside(coords, pos.x, pos.y)) {
                    return obj.triggerHandler('clickpoly');
                }
                return false;
            }

            compute_coords();
            obj.css('cursor', 'default');
            obj.mousemove(move);
            obj.mouseleave(leave);
            obj.click(click);
        });
    }

    $.fn.clickpoly = function(callback) {
        return this.bind('clickpoly', callback);
    }

    $.fn.mouseenterpoly = function(callback) {
        return this.bind('mouseenterpoly', callback);
    }

    $.fn.mouseleavepoly = function(callback) {
        return this.bind('mouseleavepoly', callback);
    }

})(jQuery);
