
/** *****************隐藏计划******************************* */
function dis(name) {
	var temp = document.getElementById(name);
	if (temp.style.display == '')
		temp.style.display = "none";
	else if (temp.style.display == "none")
		temp.style.display = '';
}
/*
 * 双击查看产品清单 物料 配置信息
 */
function conInfo(proId, orderId) {
	var type = $("#type").val();
	if (proId == '') {
		alert("【请选择物料】");
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
// 查看物料基础信息内的 保质期
function warranty(warr) {
	alert("物料信息默认保质期为 【 " + warr + " 】")
}
$(function() {
			var currency = $("#currency").html();
			if (currency != '人民币' && currency != '') {
				document.getElementById("currencyRate").style.display = "";
				$("#cur").html("(" + currency + ")");
				$("#cur1").html("(" + currency + ")");

			}
		});
// 查看变更信息
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

//合同签收类型
$(function(){
     var type = $("#signinType").html();
     switch(type){
         case 'order' : $("#signinType").html("销售类");break;
         case 'service' : $("#signinType").html("服务类");break;
         case 'lease' : $("#signinType").html("租赁类");break;
         case 'rdproject' : $("#signinType").html("研发类");break;
     }

});
