var show_page = function(page) {
	$("#financialdetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#financialdetailGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'financialdetailpageJson&id='+$("#conId").val()+'&tablename='+$("#tablename").val()+'&moneyType='+$("#moneyType").val(),
		title : '金额明细',
		isDelAction : false,
		isToolBar : false, // 是否显示工具栏
		showcheckbox : false,
		customCode : 'financialdetail',
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'year',
					display : '年份',
					sortable : true,
					width : 60
				}, {
					name : 'January',
					display : '一月份',
					sortable : true,
					width : 60
				}, {
					name : 'February',
					display : '二月份',
					sortable : true,
					width : 60
				}, {
					name : 'March',
					display : '三月份',
					sortable : true,
					width : 60
				}, {
					name : 'April',
					display : '四月份',
					sortable : true,
					width : 60
				}, {
					name : 'May',
					display : '五月份',
					sortable : true,
					width : 60
				}, {
					name : 'June',
					display : '六月份',
					sortable : true,
					width : 60
				}, {
					name : 'July',
					display : '七月份',
					width : 60
				}, {
					name : 'August',
					display : '八月份',
					width : 60
				}, {
					name : 'September',
					display : '九月份',
					sortable : true,
					width : 60
				}, {
					name : 'October',
					display : '十月份',
					sortable : true,
					width : 60
				}, {
					name : 'November',
					display : '十一月份',
					sortable : true,
					width : 60
				}, {
					name : 'December',
					display : '十二月份',
					sortable : true,
					width : 60
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