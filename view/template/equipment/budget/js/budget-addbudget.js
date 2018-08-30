function chooseEqu(equId){
	document.getElementById("bydgetlist").src='?model=equipment_budget_budget&action=toAdd&equId='+ equId;
	$("#bydgetlist").reload()
}
var show_page = function(page) {
	$("#budgetbaseinfoGrid").yxgrid("reload");
};
$(function() {
	$("#budgetTypeTree").yxtree({
		url : '?model=equipment_budget_budgetType&action=getTreeData',
		event : {
			"node_click" : function(event, treeId, treeNode) {
//				var goodsbaseinfoGrid = $("#budgetbaseinfoGrid").data('yxgrid');
//				goodsbaseinfoGrid.options.param['budgetTypeId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);

				document.getElementById("budgetTypeChoose").src='?model=equipment_budget_budgetbaseinfo&action=budgetChooseiframe&budgetTypeId='+ treeNode.id;
				$("#budgetTypeChoose").reload()
//				goodsbaseinfoGrid.reload();
			}
		}
	});
	$("#budgetbaseinfoGrid").yxgrid({
		model : 'equipment_budget_budgetbaseinfo',
		param : {
			goodsTypeId : -1
		},
		event : {
			'row_dblclick' : function(e, row, data) {
				alert(123);
//				showModalWin("?model=equipment_budget_budget&action=toView&id="
//						+ data.id
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		showcheckbox : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		title : '设备管理',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetTypeName',
			display : '所属分类',
			sortable : true,
			width : 200
		}, {
			name : 'equName',
			display : '设备名称',
			sortable : true,
			width : 200
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 400
		}],
		menusEx : [{
			text : '配置预览',
			icon : 'view',
			action : function(row, rows, grid) {
				showModalWin("?model=equipment_budget_deploy&action=toViewConfig&equId="
						+ row.id
						// + "&skey="
						// + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}, {
			text : '填写预算表',
			icon : 'add',
			action : function(row, rows, grid) {
				showModalWin("?model=equipment_budget_budget&action=toAdd&equId="
						+ row.id
						// + "&skey="
						// + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=equipment_budget_budgetbaseinfo&action=toAdd&parentName="
						+ $("#parentName").val()
						+ "&parentId="
						+ $("#parentId").val()

						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
			}
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 750
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 750
		},
		searchitems : [{
			display : "所属分类",
			name : 'budgetTypeName'
		}, {
			display : "设备名称",
			name : 'equName'
		}]
	});
});