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
	
	// 所属板块缓存
	moduleArr = getData('HTBK');

	// 模版 -- 初始化载入
	var modelType = $("#modelType").val();
	if(modelType != "" && modelType != "0"){
		setTimeout(function(){
			initTemplate(modelType);
			initCostshareTemplate(modelType);
			$("#imgLoading").hide();
			$("#feeTbl").show();
			$("#costshareImgLoading").hide();
			$("#costshare").show();
		},500);
	}

	// 初始话部门检查 - 提交审批按钮
	initSubButton();
});

// 初始渲染费用信息模板 - 新增时用
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

//初始渲染分摊信息模板 - 新增时用
function initCostshareTemplate(modelType){
	// 后台整合模板页面
	$.ajax({
	    type: "POST",
	    url: "?model=finance_expense_expense&action=initCostshareTempAdd",
	    data: {"modelType" : modelType},
	    async: false,
	    success: function(data){
			$("#costsharebody").html(data);
			// 金额 千分位处理
			formateMoney();
		}
	});
}