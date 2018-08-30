//计算方法

function countAll() {
	if ($("#number").val() == "" || $("#price_v").val() == "") {
		return false;
	} else {
		//获取当前数
		var thisNumber = $("#number").val();

		//获取当前单价
		var thisPrice = $("#price_v").val();

		//计算本行金额 - 不含税
		var thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney("money", thisMoney);
	}
}


//跳到下一步
function toNext() {
	var isMoney = $("#isMoney").val();
	//数量
	var thisNumber = $("#number").val();
	if (thisNumber == "" || thisNumber * 1 == 0) {
		alert('数量不能为空或0');
		return false;
	}
	if (isMoney != '1') {
		//是否框架合同，销售类合同是框架合同允许合同金额为0
		var isFrame = $("#isFrame").val();
		//单价
		var thisPrice = $("#price").val();
		if (thisPrice == "" || (thisPrice * 1 == 0 && isFrame == '0')) {
			alert('单价不能为空或0');
			return false;
		}

		//金额
		var thisMoney = $("#money").val();
		if (thisMoney == "" || (thisMoney * 1 == 0 && isFrame == '0')) {
			alert('金额不能为空或0');
			return false;
		}
	}

	window.location = '?model=goods_goods_properties&action=toChooseStep'
	+ '&goodsId=' + $("#goodsId").val()
	+ '&goodsName=' + $("#goodsName").val()
	+ '&isEncrypt=' + $("#isEncrypt").val()
	+ '&cacheId=' + $("#cacheId").val()
	+ '&number=' + thisNumber
	+ '&price=' + thisPrice
	+ '&money=' + thisMoney
	+ '&warrantyPeriod='
	+ '&isMoney='
	+ isMoney
	+ '&isCon='
	+ $("#isCon").val()
    + "&typeId="
    + $("#typeId").val()
    + "&notEquSlt="
    + $("#notEquSlt").val()
	+ "&rowNum="
	+ $("#rowNum").val()
	+ "&componentId="
	+ $("#componentId").val()
	+ "&exeDeptName="
	+ $("#exeDeptName").val()
	+ "&exeDeptCode="
	+ $("#exeDeptCode").val()
	+ "&auditDeptCode="
	+ $("#auditDeptCode").val()
	+ "&auditDeptName="
	+ $("#auditDeptName").val();
}

//iframe返回方法
function back() {
	window.location = '?model=goods_goods_goodsbaseinfo&action=toChoosePage&isMoney=' + $("#isMoney").val()
	+ '&isSale=' + $("#isSale").val()
	+ '&isCon=' + $("#isCon").val()
	+ "&rowNum="
	+ $("#rowNum").val()
    + "&typeId="
    + $("#typeId").val()
		+ "&notEquSlt="
		+ $("#notEquSlt").val()
	+ "&componentId="
	+ $("#componentId").val()
	+ "&exeDeptName="
	+ $("#exeDeptName").val()
	+ "&exeDeptCode="
	+ $("#exeDeptCode").val()
	+ "&auditDeptCode="
	+ $("#auditDeptCode").val()
	+ "&auditDeptName="
	+ $("#auditDeptName").val();
}

$(function() {
	var isMoney = $("#isMoney").val();
	if (isMoney == '1') {
		$("#isMoneyShow").hide();
	} else {
		$("#isMoneyShow").show();
	}
});