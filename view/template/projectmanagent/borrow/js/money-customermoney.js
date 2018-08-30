var show_page = function(page) {
	$("#cusMoneyGrid").yxgrid("reload");
};
$(function() {
	$("#cusMoneyGrid").yxgrid({
		model : 'projectmanagent_borrow_money',
//		action : 'MyBorrowPageJson',
		param : {
			'borrowType' : '客户',
			'controlType' : 'area'
		},
		title : '客户借试用金额控制',
		//按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		//isDelAction : false,
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "初始化",
			icon : 'add',

			action : function(row) {
				showOpenWin('?model=projectmanagent_borrow_money&action=toAdd');
			}
		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'areaName',
			display : '区域名称',
			sortable : true,
			width : 150
		}, {
			name : 'maxMoney',
			display : '最大借用金额',
			sortable : true,
			process : function(v) {
						return moneyFormat2(v);
					},
		    width : 150
		}],

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '区域',
			name : 'areaName'
		}],
		// 扩展右键菜单
		menusEx : [ {
			text : '修改金额',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=projectmanagent_borrow_money&action=editMoney&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600")
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});

});