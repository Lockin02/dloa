//��ʼ������
$(function(){
	//���Ľ��
	var chinseMoneyObj  = $(".chinseMoney");
	chinseMoneyObj.html(toChinseMoney(chinseMoneyObj.attr('title')*1));

	//�ǵ�һ�δ�ӡ����
	var printCount = $("#printCount").val();
	// if(printCount > 0){
		var idStr = $("#isReprint").prev("span").text();
        var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "��ӡ����: "+$("#todayStr").val()+" " : '';
		// $("#isReprint").html('ע�⣺�ظ���ӡ���� ');

		$("#isReprint").html(todayStr+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����ţ�'+idStr+' �� '+(Number(printCount)+1)+' �δ�ӡ ');
		$("#isReprint").prev("span").hide();
	// }
});


//��ӡ�¼�
function changePrintCount(id){
	var printCount = $("#printCount").val();
	if(printCount > 0){
		var idStr = $("#isReprint").prev("span").text();
        var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "��ӡ����: "+$("#todayStr").val()+" " : '';
		// $("#isReprint").html('ע�⣺�ظ���ӡ���� ');

		$("#isReprint").html(todayStr+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����ţ�'+idStr+' �� '+(Number(printCount)+1)+' �δ�ӡ ');
		$("#isReprint").prev("span").hide();

		if(confirm('�����Ѿ���ӡ�����Ƿ������ӡ��')){
			var printTimes = prn_preview('payablesapply','��������');

			if(printTimes > 0){
				$.ajax({
				    type: "POST",
				    url: "?model=finance_payablesapply_payablesapply&action=changePrintCount",
				    data: {"id" : id ,'printTimes' : printTimes },
				    async: false,
				    success: function(data){
				    	$("#printCount").val(data)

				   		if(window.opener != undefined){
					    	window.opener.show_page();
					    }
					}
				});
			}
		}
	}else{
		var printTimes = prn_preview('payablesapply','��������');

		if(printTimes > 0){
			$.ajax({
			    type: "POST",
			    url: "?model=finance_payablesapply_payablesapply&action=changePrintCount",
			    data: {"id" : id ,'printTimes' : printTimes },
			    async: false,
			    success: function(data){
			    	$("#printCount").val(data)

			   		if(window.opener != undefined){
				    	window.opener.show_page();
				    }
				}
			});
		}
	}
}