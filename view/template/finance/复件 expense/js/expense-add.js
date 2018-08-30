//附件的全局变量
var uploadfile;

$(function() {
	//费用归属
	$("#costbelong").costbelong({
		objName : 'expense'
	});

	//回到顶部
	$.scrolltotop({className: 'totop'});

	// 发票类型缓存
	billTypeArr = getBillType();

	// 模版 -- 初始化载入
	var modelType = $("#modelType").val();
	if(modelType != "" && modelType != "0"){
		initTemplate(modelType);
		$("#imgLoading").hide();
		$("#feeTbl").show();
	}

	// 初始话部门检查 - 提交审批按钮
	initSubButton();
});

// 初始渲染模板 - 新增时用
function initTemplate(modelType){
	// 后台整合模板页面
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=initTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#invbody").html(data);
			// 金额 千分位处理
			formateMoney();
			resetCustomCostType();
		}
	});
}