$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ���ύ��?")) {
				if($("input[id^='money']").length == 0){
					$("#allotAble").val($("#incomeMoney").val());
				}
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