//���㷽��

function countAll() {
	if ($("#number").val() == "" || $("#price_v").val() == "") {
		return false;
	} else {
		//��ȡ��ǰ��
		var thisNumber = $("#number").val();

		//��ȡ��ǰ����
		var thisPrice = $("#price_v").val();

		//���㱾�н�� - ����˰
		var thisMoney = accMul(thisNumber, thisPrice, 2);
		setMoney("money", thisMoney);
	}
}


//������һ��
function toNext() {
	var isMoney = $("#isMoney").val();
	//����
	var thisNumber = $("#number").val();
	if (thisNumber == "" || thisNumber * 1 == 0) {
		alert('��������Ϊ�ջ�0');
		return false;
	}
	if (isMoney != '1') {
		//�Ƿ��ܺ�ͬ���������ͬ�ǿ�ܺ�ͬ�����ͬ���Ϊ0
		var isFrame = $("#isFrame").val();
		//����
		var thisPrice = $("#price").val();
		if (thisPrice == "" || (thisPrice * 1 == 0 && isFrame == '0')) {
			alert('���۲���Ϊ�ջ�0');
			return false;
		}

		//���
		var thisMoney = $("#money").val();
		if (thisMoney == "" || (thisMoney * 1 == 0 && isFrame == '0')) {
			alert('����Ϊ�ջ�0');
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

//iframe���ط���
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