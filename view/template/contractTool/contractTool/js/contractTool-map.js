
	var Line = function() {
    var color = "#808080";
    var pointArray = [];
    var idocument = document.getElementById("tb");
    var draw = {
        // 画出最基本的图像，点，和垂直直线或水平直线
        drawBase : function(x1, y1, w, h) {
            try {
                var span = idocument.createElement("span");
                span.style.position = 'absolute';
                span.style.left = x1;
                span.style.top = y1;
                span.style.height = h;
                span.style.width = w;
                span.style.background = color;
                span.className = 'draw-line';
                idocument.body.appendChild(span);
                pointArray.push(span);
            } catch (e) {
            }
        },
        drawLine : function(x1, y1, x2, y2) {
            var w = (((x2 - x1) == 0 ? 1 : (x2 - x1)));
            var h = (((y2 - y1) == 0 ? 1 : (y2 - y1)));
            if (x1 == x2 || y1 == y2) {
                // 水平或垂直
                var minX = (x1 < x2 ? x1 : x2);
                var minY = (y1 < y2 ? y1 : y2);
                draw.drawBase(minX, minY, Math.abs(w), Math.abs(h));
            } else {// 斜线
                var oldX = -1;
                var oldY = -1;
                var newX = 0;
                var newY = 0;
                if (Math.abs(w) > Math.abs(h)) {
                    for (var i = 0; i < Math.abs(w); i++) {
                        newX = (x1 + (w > 0 ? 1 : -1) * i);
                        newY = (y1 + (h > 0 ? 1 : -1) * Math.abs(i * h / w));
                        if (oldX != newX && oldY != newY) {
                            oldX = newX;
                            oldY = newY;
                            draw.drawBase(oldX, oldY, 1, 1)
                        }
                    }
                } else {
                    for (var i = 0; i < Math.abs(h); i++) {
                        newX = (x1 + (w > 0 ? 1 : -1) * Math.abs(i * w / h));
                        newY = (y1 + (h > 0 ? 1 : -1) * i);
                        if (oldX != newX && oldY != newY) {
                            oldX = newX;
                            oldY = newY;
                            draw.drawBase(oldX, oldY, 1, 1)
                        }
                    }
                }
            }
        },
        drawArrowheaded : function(x0, y0, x1, y1) {// 箭头
            var w = (((x1 - x0) == 0 ? 1 : (x1 - x0)));
            var h = (((y1 - y0) == 0 ? 1 : (y1 - y0)));

            var d = Math.sqrt((y1 - y0) * (y1 - y0) + (x1 - x0) * (x1 - x0));
            var Xa = x1 + 10 * ((x0 - x1) + (y0 - y1) / 2) / d;
            var Ya = y1 + 10 * ((y0 - y1) - (x0 - x1) / 2) / d;
            var Xb = x1 + 10 * ((x0 - x1) - (y0 - y1) / 2) / d;
            var Yb = y1 + 10 * ((y0 - y1) + (x0 - x1) / 2) / d;

            draw.drawLine(x1, y1, Xa, Ya);
            draw.drawLine(x1, y1, Xb, Yb);
        },
        drawArrowheadedLine : function(x1, y1, x2, y2) {
            // 直线
            draw.drawLine(x1, y1, x2, y2);
            // 箭头
            draw.drawArrowheaded(x1, y1, x2, y2);
        }
    }

    Line.prototype.drawArrowLine = function(x1, y1, x2, y2) {
        draw.drawArrowheadedLine(x1, y1, x2, y2);
    }

    Line.prototype.remove = function() {// 删除画出的线
        var len = pointArray.length;
        for (var i = 0; i < len; i++) {
            idocument.body.removeChild(pointArray[i]);
        }
        pointArray = null;
        pointArray = [];
    }

    Line.prototype.setDocument = function(idoc) {
        idocument = idoc;
    }

    Line.prototype.setColor = function(newColor) {// 设置线条颜色
        color = newColor;
    }
}

var line = new Line();
line.drawArrowLine(181, 226, 292, 130);

function mouseMove(ev)
{
Ev= ev || window.event;
var mousePos = mouseCoords(ev);
document.getElementById("xxx").value = mousePos.x;
document.getElementById("yyy").value = mousePos.y;
}
function mouseCoords(ev)
{
if(ev.pageX || ev.pageY){
return {x:ev.pageX, y:ev.pageY};
}
return{
x:ev.clientX + document.body.scrollLeft - document.body.clientLeft,
y:ev.clientY + document.body.scrollTop - document.body.clientTop
};
}
document.onmousemove = mouseMove;