
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
function conInfo(proId, orderId) {
	var type = $("#type").val();
	if (proId == '') {
		alert("����ѡ�����ϡ�");
	} else {
		showThickboxWin('?model=projectmanagent_order_order&action=proinfotab&productId='
				+ proId
				+ "&orderId="
				+ orderId
				+ "&type="
				+ type
				+ "&view=view"
				+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700');
	}

}
// �鿴���ϻ�����Ϣ�ڵ� ������
function warranty(warr) {
	alert("������ϢĬ�ϱ�����Ϊ �� " + warr + " ��")
}
$(function() {
			var currency = $("#currency").html();
			if (currency != '�����' && currency != '') {
				document.getElementById("currencyRate").style.display = "";
				$("#cur").html("(" + currency + ")");
				$("#cur1").html("(" + currency + ")");

			}
		});
// �鿴�����Ϣ
function beColor(type, equId, objId) {
	var detailType = "";
	switch (type) {
		case "order" :
			detailType = 'orderequ';
			break;
		case "service" :
			detailType = 'serviceequ';
			break;
		case "lease" :
			detailType = 'rentalcont';
			type="rentalcontract";
			break;
		case "rdproject" :
			detailType = 'rdprojectequ';
			break;
	}
	showThickboxWin("?model=common_changeLog&action=toView&logObj="
			+ type
			+ "&detailType="
			+ detailType
			+ "&objId="
			+ objId
			+ "&detailId="
			+ equId
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
}

//��ͬǩ������
$(function(){
     var type = $("#signinType").html();
     switch(type){
         case 'order' : $("#signinType").html("������");break;
         case 'service' : $("#signinType").html("������");break;
         case 'lease' : $("#signinType").html("������");break;
         case 'rdproject' : $("#signinType").html("�з���");break;
     }

});
