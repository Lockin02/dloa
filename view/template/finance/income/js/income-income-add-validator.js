$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			if($("#incomeUnitId").val() == ""){
				alert("��ͨ�����������ȷѡ�񵽿λ������������в�ѯ�����Ŀͻ����ƣ�����'+'��ťֱ������");
				return false;
			}
			if($("#incomeMoney").val() == "" || $("#incomeMoney").val()*1 == 0 ){
				alert("�������Ϊ0���߿�");
				return false;
			}
			if (confirm("������ɹ�,ȷ���ύ��?")) {
				$('#allotAble').val($('#incomeMoney').val());
				return true;
			} else {
				return false;
			}
		}
	});

	/** ���λ��֤* */
	$("#incomeUnitName").formValidator({
		empty : false,
		onshow : "��ѡ�񵽿λ",
		onfocus : "ѡ�񵽿λ",
		oncorrect : "��ѡ���˵��λ"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ�񵽿λ"
	});

});