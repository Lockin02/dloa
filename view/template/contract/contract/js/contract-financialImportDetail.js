var show_page = function(page) {
	$("#financialImportDetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#financialImportDetailGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'financialImportDetailpageJson&id='+$("#conId").val()+'&tablename='+$("#tablename").val()+'&moneyType='+$("#moneyType").val(),
		title : '导入明细',
		isDelAction : false,
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
		isOpButton:false,
		customCode : 'financialdetail',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'improtMonth',
					display : '导入月份',
					sortable : true,
					width : 100
				}, {
					name : 'moneyType',
					display : '金额类别',
					sortable : true,
					width : 100
				}, {
					name : 'moneyNum',
					display : '导入金额',
					sortable : true,
					width : 100,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'importName',
					display : '导入人',
					sortable : true,
					width : 100
				}, {
					name : 'importDate',
					display : '导入时间',
					sortable : true,
					width : 100
				}, {
					name : 'isUse',
					display : '是否生效',
					sortable : true,
					width : 60,
					process : function(v,row){
					   if(v == '0'){
					      return "√";
					   }else{
					      return "×";
					   }
					}
				}],
		sortname : "createTime",
		// 设置新增页面宽度
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		// 设置编辑页面宽度
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		// 设置查看页面宽度
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});

});