/*
 * wishingWall 0.0.3 - Javascript
 *
 * Copyright (c) 2008 DeltaCat (http://www.zu14.cn)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * $Date: 2008-12-6 14:22:17 -0400 (Sat, 6 Dec 2008) $
 * $Rev: 3 $
 * Requires jQuery 1.1.3+, jqDnR.js
 * Optional dimensions.js
 */
var wishingWall = {
	lastPad : null,
	lastPadZIndex : 0,
	padCount : 0
};
wishingWall.closePad = function(objPad) {

	$(objPad.parentNode.parentNode).remove();
	//document.body.removeChild(objPad.parentNode.parentNode);
};
wishingWall.resort = function(objPad) {
   if (this.lastPad) {
		this.lastPad.style.zIndex = this.lastPadZIndex;
	}
	this.lastPad = objPad;
	this.lastPadZIndex = objPad.style.zIndex;
	objPad.style.zIndex = this.padCount + 1;
};
wishingWall.search = function(flag) {
	var u = $('#wName').val();
	if (u == '' || u == 'ÄãµÄÃû×Ö')
		return;
	if (flag == 0) {
		u += ':';
		$('.wt').each(function() {
			if ($(this).text() == u)
				this.parentNode.parentNode.style.display = '';
			else
				this.parentNode.parentNode.style.display = 'none';
		});
	} else {
		$('.inline').each(function() {
			if ($(this).text() == u)
				this.parentNode.parentNode.style.display = '';
			else
				this.parentNode.parentNode.style.display = 'none';
		});
	}
}
wishingWall.init = function() {
	this.padCount = $('.wall').length;
	$(".wall").bind("mousedown", function(e){
					wishingWall.resort(this);
          });
/*
	$('.wall').mousedown(function() {
			alert('123');
		wishingWall.resort(this);
	});
*/	
	$('.wall').each(function() {
		$(this).jqDrag();
	});
	$(".x").bind("click", function(e){
			wishingWall.closePad(this);
          });
/*
	$('.x').click(function() {
		wishingWall.closePad(this);
	});
*/	
};
$(document).ready(function() {
	wishingWall.init();
});