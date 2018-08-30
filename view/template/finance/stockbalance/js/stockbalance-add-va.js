var isTrue = 0;

$(document).ready(function() {
	$.formValidator.initConfig({
		formid : "form1",
		onsuccess : function() {
			$.ajax({
			    type: "POST",
			    url: "?model=finance_period_period&action=isFirst",
			    data: {"year" : $("#thisYear").val() , 'month' : $("#thisMonth").val() },
			    async: false,
			    success: function(data){
			   	   if(data == 1){
			   	   		isTrue = 1;
						return false;
					}else{
						isTrue = 0;
						return false;
					}
				}
			});
			if(isTrue == 0){
				alert('¼��ʧ��,��ȷ�ϵ�ǰ¼�������Ƿ��ڳ�ʼ�������ڻ��ߵ�ǰ�������Ƿ��ʼ������!');
				return false;
			}
			if($("#clearingNum").val() == ""){
				alert("����������Ҫ��д");
				return false;
			}
			if($("#balanceAmount").val() == ""){
				alert("�������Ҫ��д");
				return false;
			}

			if (confirm("������ɹ�,ȷ���ύ��?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	/** �ֿ���֤* */
	$("#stockName").formValidator({
		empty : false,
		onshow : "��ѡ��ֿ�",
		onfocus : "ѡ��ֿ�",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ��ֿ�"
	});

	/** ������֤* */
	$("#thisDate").formValidator({
		empty : false,
		onshow : "��ѡ������",
		onfocus : "ѡ������",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ������"
	});

	/** ������֤* */
	$("#productNo").formValidator({
		empty : false,
		onshow : "��ѡ������",
		onfocus : "ѡ������",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ������"
	});
});