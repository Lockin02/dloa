
/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
/*
 * ˫���鿴��Ʒ�嵥 ���� ������Ϣ
 */
function conInfo(proId){
	    if (proId == ''){
	        alert("����ѡ�����ϡ�");
	    }else {
	    	 showThickboxWin('?model=stock_productinfo_configuration&action=viewConfig&productId='
                      + proId
                      +'&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=600');
	    }

	}
