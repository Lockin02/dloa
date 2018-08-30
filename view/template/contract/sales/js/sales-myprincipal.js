var show_page = function(page) {
	$("#MyPrincipal").yxgrid("reload");
};
$(function() {
	$("#MyPrincipal").yxgrid({
		model : 'contract_sales_sales',
		action : 'myprincipalPageJson',
		/**
		 * 是否显示查看按钮/菜单
		 */
		isViewAction : false,
		/**
		 * 是否显示修改按钮/菜单
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 */
		isDelAction : false,
		// 是否显示新增按钮
		isAddAction : false,
		// 是否显示工具栏
		isToolBar : false,
		//是否显示checkbox
	    showcheckbox : false,
		// 扩展右键菜单

		menusEx : [
		{
			text : '合同信息',
			icon : 'view',
			action : function(row) {
				if (row.ExaStatus == '完成'){
				    showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
					);
				} else {
				    showOpenWin('?model=contract_sales_sales&action=readDetailedInfoNoedit&id='
						+ row.id );
				}
			}
		}, {
			text : '合同历史',
			icon : 'view',

			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=versionShow&contNumber='
						+ row.contNumber
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '启动执行',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.contStatus == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=contractBeginAction&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500');
			}
		}, {
			text : '申请变更',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '完成' && row.changeStatus == '0' && (row.contStatus == '0'
						|| row.contStatus == '1')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				toUrl('?model=contract_sales_sales&action=readInfoForChange&id='
						+ row.id);
			}
//		}, {
//			text : '申请发货',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.changeStatus == '0' && row.contStatus == '1') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showThickboxWin('?model=stock_shipapply_shipapply&action=toAdd'
//					+ '&shipapply[objId]=' + row.id
//					+ '&shipapply[objCode]=' + row.contNumber
//					+ '&shipapply[objName]=' + row.contName
//					+ '&shipapply[objType]=KPRK-XSHT'
//					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000'
//				);
//			}
		}, {
			text : '合同签约状态',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.contStatus == '1' && row.changeStatus == '0' && row.signStatus != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_sales_sales&action=toSign&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=600');
			}
		}, {
			text : '关闭合同',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.changeStatus == '0' &&row.ExaStatus != '部门审批' && row.ExaStatus != '待审批' && row.contStatus != 9) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=contract_common_bcinfo&action=toClose&id='
						+ row.id
						+ '&contNumber='
						+ row.contNumber
						+ '&customerContNum'
						+ row.customerContNum
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800');
			}
		}],

		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '鼎利合同号',
			name : 'contNumber',
			sortable : true,
			width : 150
		}, {
			display : '合同名称',
			name : 'contName',
			sortable : true,
			width : 150
		}, {
			display : '客户名称',
			name : 'customerName',
			sortable : true,
			width : 100
		}, {
			display : '客户合同号',
			name : 'customerContNum',
			sortable : true,
			width : 100
		}, {
			display : '客户类型',
			name : 'customerType',
			datacode : 'KHLX',
			sortable : true,
			width : 100
		}, {
			display : '客户所属省份',
			name : 'province',
			sortable : true,
			width : 100
		}, {
			display : '负责人名称',
			name : 'principalName',
			sortable : true,
			width : 150
		}, {
			display : '合同状态',
			name : 'contStatus',
			sortable : true,
			process : function(v,row){
				if(row.changeStatus == '1'){
					return '变更中';
				}
				switch (v) {
					case '': return '未启动';break;
					case '0': return '未启动';break;
					case '1': return '正执行';break;
					case '2': return '变更待审批';break;
					case '3': return '变更中';break;
					case '4': return '打回关闭';break;
					case '5': return '保留删除';break;
					case '6': return '变更后关闭';break;
					case '9': return '已关闭';break;
					default : return '未启动';break;
				}
			}
		}, {
			display : '审批状态',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '签约状态',
			name : 'signStatus',
			sortable : true,
			process : function(v){
				switch (v) {
					case '0': return '未签约';break;
					case '1': return '已签约';break;
					case '2': return '已拿到纸质合同';break;
					case '3': return '已提交纸质合同';break;
					case '4': return '财务已签收';break;
					default : return '未签约';break;
				}
			}
		}],
		comboEx : [{
			text : '合同状态',
			key : 'contStatus',
			data :[
				{
					text : '未启动',
					value : 0
				},{
					text : '正执行&变更中',
					value : 1
				},{
					text : '已关闭',
					value : 9
				}
			]
		},{
			text : '审批状态',
			key : 'ExaStatus',
			type : 'workFlow'
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同名称',
			name : 'contName'
		}],
		sortorder : "DESC",
		title : '我负责的合同'
	});
});