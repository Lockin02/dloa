/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function findposx(obj) {
	var curleft = 0;
	if (document.getElementById || document.all) {

		while (obj.offsetParent) {

			curleft += obj.offsetLeft

			obj = obj.offsetParent;
		}

	} else if (document.layers) {

		curleft += obj.x;

	}
	return curleft;

}

function findposy(obj) {
	var curtop = 0;
	if (document.getElementById || document.all) {

		while (obj.offsetParent) {

			curtop += obj.offsetTop

			obj = obj.offsetParent;

		}

	} else if (document.layers) {

		curtop += obj.y;

	}
	return curtop;

}

