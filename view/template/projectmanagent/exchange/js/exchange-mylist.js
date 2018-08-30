var show_page = function(page) {
	$("#myexchangeGrid").yxsubgrid("reload");
};
$(function() {
	$("#myexchangeGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		param : {'createId' : $("#userId").val(),'toListMy' : 1},
		title : '销售换货',
		isDelAction : false,
		isToolBar : true, //是否显示工具栏
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'exchangeCode',
			display : '换货单编号',
			sortable : true,
			width : 120
		}, {
			name : 'contractCode',
			display : '源单号',
			sortable : true,
			width : 130
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'saleUserName',
			display : '销售负责人',
			sortable : true
		}, {
			name : 'reason',
			display : '换货原因',
			sortable : true,
			width : 300
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true,
			width : 80
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_exchange_exchangebackequ&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'exchangeId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				name : 'productCode',
				width : 200,
				display : '物料编号'
			},{
				name : 'productName',
				width : 200,
				display : '物料名称'
			}, {
			    name : 'number',
			    display : '申请数量',
				width : 80
			}, {
			    name : 'executedNum',
			    display : '已执行数量',
				width : 80
			}]
		},
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=toAdd');

			}
		}],
		menusEx : [

		{
			text : '查看',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=init&id='
						+ row.id
						+ "&skey="+row['skey_']
						+ '&perm=view' );
			}
		},{
			text : '编辑',
			icon : 'edit',
            showMenuFn : function(row){
				if(row.ExaStatus == '未审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchange&action=toEdit&id='
						+ row.id
						+ "&skey="+row['skey_']);
			}
		},{
			text : '提交审核',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '未审批' || row.ExaStatus == '打回'){
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/projectmanagent/exchange/ewf_index2.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}

		},{
				text : '审批情况',
				icon : 'view',
	            showMenuFn : function (row) {
	               if (row.ExaStatus=='保存'){
	                   return false;
	               }
	                   return true;
	            },
				action : function(row) {

					showThickboxWin('controller/projectmanagent/exchange/readview.php?itemtype=oa_contract_exchangeapply&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批' || row.ExaStatus == '打回') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_exchange_exchange&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#myexchangeGrid").yxsubgrid("reload");
							}
						}
					});
				}
			}

		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '换货单编号',
			name : 'exchangeCode'
		}]
	});
});