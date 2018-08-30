//初始化单据
$(function(){
	//中文金额
	var chinseMoneyObj  = $(".chinseMoney");
	chinseMoneyObj.html(toChinseMoney(chinseMoneyObj.attr('title')*1));

	//非第一次打印设置
	var printCount = $("#printCount").val();
	// if(printCount > 0){
		var idStr = $("#isReprint").prev("span").text();
        var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "打印日期: "+$("#todayStr").val()+" " : '';
		// $("#isReprint").html('注意：重复打印单据 ');

		$("#isReprint").html(todayStr+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 付款单号：'+idStr+' 第 '+(Number(printCount)+1)+' 次打印 ');
		$("#isReprint").prev("span").hide();
	// }
});


//打印事件
function changePrintCount(id){
	var printCount = $("#printCount").val();
	if(printCount > 0){
		var idStr = $("#isReprint").prev("span").text();
        var todayStr = ($("#todayStr").val() != undefined && $("#todayStr").val() != '')? "打印日期: "+$("#todayStr").val()+" " : '';
		// $("#isReprint").html('注意：重复打印单据 ');

		$("#isReprint").html(todayStr+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 付款单号：'+idStr+' 第 '+(Number(printCount)+1)+' 次打印 ');
		$("#isReprint").prev("span").hide();

		if(confirm('单据已经打印过，是否继续打印？')){
			var printTimes = prn_preview('payablesapply','付款申请');

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
		var printTimes = prn_preview('payablesapply','付款申请');

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