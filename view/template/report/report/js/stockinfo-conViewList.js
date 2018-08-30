var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};
$(function() {
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		title : '合同主表',
		param : {'ids' : $("#cids").val()},
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		// showcheckbox : false,
		isAddAction : false,
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			showMenuFn : function(row) {
				if (row) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}
		}],
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'contractType',
					display : "合同类型",
					sortable : true,
					datacode : 'HTLX',
					width : 60
				}, {
					name : 'contractCode',
					display : '合同编号',
					sortable : true,
					width : 180,
					process : function(v, row) {
						return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
								+ row.id
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
								+ "<font color = '#4169E1'>"
								+ v
								+ "</font>"
								+ '</a>';
					}
				}, {
					name : 'contractProvince',
					display : '省份',
					sortable : true,
					width : 60
				}, {
					name : 'contractCity',
					display : '城市',
					sortable : true,
					width : 60
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true,
					width : 100
				}, {
					name : 'customerType',
					display : '客户类型',
					sortable : true,
					datacode : 'KHLX',
					width : 70
				}, {
					name : 'contractName',
					display : '合同名称',
					sortable : true,
					width : 150
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					sortable : true,
					width : 60
				}, {
					name : 'winRate',
					display : '合同赢率',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '归属区域',
					sortable : true,
					width : 60
				}, {
					name : 'areaPrincipal',
					display : '区域负责人',
					sortable : true
				}, {
					name : 'prinvipalName',
					display : '合同负责人',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '合同状态',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "未提交";
						} else if (v == '1') {
							return "审批中";
						} else if (v == '2') {
							return "执行中";
						} else if (v == '3') {
							return "已关闭";
						} else if (v == '4') {
							return "已完成";
						} else if (v == '5') {
							return "已合并";
						} else if (v == '6') {
							return "已拆分";
						} else if (v == '7') {
							return "异常关闭";
						}
					},
					width : 60
				}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=contract_contract_equ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'contractId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
						name : 'productCode',
						width : 200,
						display : '产品编号'
					},{
						name : 'productName',
						width : 200,
						display : '产品名称'
					}, {
					    name : 'number',
					    display : '需求数量',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '已执行数量',
						width : 80
					}, {
					    name : 'executedNum',
					    display : '已执行数量',
						width : 80
					}]
		},
		comboEx : [{
					text : '类型',
					key : 'contractType',
					data : [{
								text : '销售合同',
								value : 'HTLX-XSHT'
							}, {
								text : '服务合同',
								value : 'HTLX-FWHT'
							}, {
								text : '租赁合同',
								value : 'HTLX-ZLHT'
							}, {
								text : '研发合同',
								value : 'HTLX-YFHT'
							}]
				}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '合同编号',
					name : 'contractCode'
				}, {
					display : '合同名称',
					name : 'contractName'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '业务编号',
					name : 'objCode'
				}, {
					display : '产品名称',
					name : 'conProductName'
				},{
				    display : '试用项目',
				    name : 'trialprojectCode'
				}],
		sortname : "createTime"

	});

});
