var show_page = function(page) {
	$("#myequlistGrid").yxsubgrid("reload");
};
$(function() {
	$("#myequlistGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrowequ',
		action : 'borrowEquPageJson',
		param : {
			'borrowLimits' : '客户'
		},
		title : '借用设备清单',
		//按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : 'myborrowequlist',
		//列信息
		colModel : [
	    {
			name : 'productId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'productNo',
			display : '物料编号',
			sortable : true,
			width : 150
		},{
			name : 'productName',
			display : '物料名称',
			sortable : true,
			width : 200
		}, {
			name : 'productModel',
			display : '物料型号',
			sortable : true,
			width : 200
		}, {
		    name : 'number',
		    display : '申请数量',
			width : 80
		}, {
		    name : 'executedNum',
		    display : '已执行数量',
			width : 80
		}, {
		    name : 'applyBackNum',
		    display : '已申请归还数量'
		}, {
		    name : 'backNum',
		    display : '已归还数量',
			width : 80
		}],
		comboEx : [{
			text : '审批状态',
			key : 'borrowExaStatus',
			data : [{
				text : '未审批',
				value : '未审批'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '完成',
				value : '完成'
			}]
		},{
			text : '状态',
			key : 'borrowStatus',
			data : [{
				text : '正常',
				value : '0'
			}, {
				text : '关闭',
				value : '2'
			}, {
				text : '退回',
				value : '3'
			}, {
				text : '续借申请中',
				value : '4'
			}, {
				text : '转至执行部',
				value : '5'
			}, {
				text : '转借确认中',
				value : '6'
			}]
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrow&action=listPageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'ids',// 传递给后台的参数名称
				colId : 'borrowIdArr'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [
			{
				name : 'Code',
				display : '借用单编号',
				width : 150,
				process : function(v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_borrow_borrow&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
			},
			{
			    name : 'Type',
			    display : '类型',
				width : 80
			},
			{
			    name : 'customerName',
			    display : '客户名称',
				width : 200
			},
			{
			    name : 'beginTime',
			    display : '开始日期',
				width : 80
			},
			{
			    name : 'closeTime',
			    display : '截止日期',
				width : 80
			}]
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '物料编号',
			name : 'productNo'
		},{
			display : '物料名称',
			name : 'productName'
		},{
			display : '序列号',
			name : 'serialName2'
		}]
	});

});