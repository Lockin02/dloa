$(function() {
	// �ͻ�
	$("#customerName").yxcombogrid_customer({
		hiddenId : 'customerId',
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
				}
			}
		}
	});
});

/**
 * license
 * @type
 */

function License(licenseId){
	var licenseVal = $("#" + licenseId ) .val();
	if( licenseVal == ""){
		//���Ϊ��,�򲻴�ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}else{
		//��Ϊ����ֵ
		showThickboxWin('?model=yxlicense_license_tempKey&action=toSelect'
		    + '&focusId=' + licenseId
			+ '&licenseId=' + licenseVal
			+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900');
	}
}

//��дid
function setLicenseId(licenseId,thisVal){
	$('#' + licenseId ).val(thisVal);
}


function issuedFun(){
	document.getElementById('form1').action="?model=produce_protask_protask&action=edit&issued=true&msg=�´�ɹ�";
}

function referDateFun(){
	temp = $('#rowNum').val();
	for(var i=1;i<=temp;i++){
		if( $('#referDate' + i).val() == '' || $('#referDate' + i).val() == '0000-00-00' )
			$('#referDate' + i).val( $('#referDate').val() );
	}
}



/** *****************���ؼƻ�******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
/** ********************ɾ����̬��************************ */
function mydel(obj, mytable) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.parentNode.rowIndex * 1 - 1;
		var mytable = document.getElementById(mytable);
		mytable.deleteRow(rowNo);
		var myrows = mytable.rows.length;
		for (i = 1; i < myrows; i++) {
			mytable.rows[i].childNodes[0].innerHTML = i;
		}
	}
}
function checkThis( obj ){
	if( $('#number'+obj).val()*1 > $('#contRemain' + obj).val()*1 ){
		alert( '�˴η�������������ͬʣ�����������������룡' );
		$('#number' + obj).val('');
		$('#number' + obj+ '_v').val('');
	}
}