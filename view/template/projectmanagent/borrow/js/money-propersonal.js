var show_page = function(page) {
	$("#propersonalGrid").yxgrid("reload");
};
$(function() {
	$("#propersonalGrid").yxgrid({
		model : 'projectmanagent_borrow_money',
//		action : 'MyBorrowPageJson',
		param : {
			'borrowType' : '员工',
			'controlType' : 'user'
		},
		title : '员工（个人）借试用金额控制',
		//按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		//isDelAction : false,
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showThickboxWin('?model=projectmanagent_borrow_money&action=toProAdd&type=person'
				                   + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
			}
		}],
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : '人员名称',
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
					showThickboxWin("?model=projectmanagent_borrow_money&action=proeditMoney&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600")
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});

});