var show_page = function(page) {
	$("#DetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#DetailGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'financialStatisticspageJson',
		title : '财务导入金额统计表',
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
					name : 'contractCode',
					display : '合同编号',
					sortable : true,
					width : 100
				},{
					name : 'moneyType',
					display : '金额类别',
					sortable : true,
					width : 80,
					process :　function(v,row){
					   if(v=="deductMoney"){
					       return "扣款金额";
					   }else if(v=="financeconfirmMoney"){
					       return "财务确认总成本";
					   }else if(v=="serviceconfirmMoney"){
					       return "服务确认收入";
					   }
					}
				},{
					name : 'year',
					display : '年份',
					sortable : true,
					width : 60
				}, {
					name : 'initMoney',
					display : '初始化金额',
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
		comboEx : [{
			text : '金额类型',
			key : 'moneyType',
			data : [{
				text : '扣款金额',
				value : 'deductMoney'
			}, {
				text : '财务确认总成本',
				value : 'financeconfirmMoney'
			}, {
				text : '服务确认收入',
				value : 'serviceconfirmMoney'
			}]
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}],
		sortname : "year",
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