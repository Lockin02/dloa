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
				alert('录入失败,请确认当前录入日期是否在初始财务期内或者当前财务期是否初始财务期!');
				return false;
			}
			if($("#clearingNum").val() == ""){
				alert("结算数量需要填写");
				return false;
			}
			if($("#balanceAmount").val() == ""){
				alert("结存金额需要填写");
				return false;
			}

			if (confirm("你输入成功,确定提交吗?")) {
				return true;
			} else {
				return false;
			}
		}
	});

	/** 仓库验证* */
	$("#stockName").formValidator({
		empty : false,
		onshow : "请选择仓库",
		onfocus : "选择仓库",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "请选择仓库"
	});

	/** 日期验证* */
	$("#thisDate").formValidator({
		empty : false,
		onshow : "请选择日期",
		onfocus : "选择日期",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "请选择日期"
	});

	/** 物料验证* */
	$("#productNo").formValidator({
		empty : false,
		onshow : "请选择物料",
		onfocus : "选择物料",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "请选择物料"
	});
});