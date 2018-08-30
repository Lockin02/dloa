var show_page = function(page) {
	$("#deptinventoryGrid").yxgrid("reload");
};
$(function() {

	$("#deptinventoryGrid").yxgrid({

		model : 'hr_inventory_stageinventory&action=listJson',
		param : {
			isUse : '1'
		},
		title : '阶段盘点',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		formWidth : 800,
		formHeight : 800,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'inventoryName',
					display : '盘点名称',
					sortable : true,
					width : 250
				}, {
					name : 'inventoryTypeCode',
					display : '盘点类型',
					datacode : 'JDPD',
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					sortable : true,
					width : 250
				}],
		menusEx : [{
			text : '填写盘点信息',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_inventory_inventory&action=init&stageId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			},
			showMenuFn : function(row) {
				if (row.isUse == 1) {
					return true;
				}
				return false;
			}

		},{
			text : '填写总结信息',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=hr_inventory_inventory&action=toeditsummary&stageId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			},
			showMenuFn : function(row) {
				var show=false;
				$.ajax({
					url : '?model=hr_inventory_inventory&action=getState',
					type : 'POST',
					async : false,
					data : {
						"id" : row.id
					},
					success : function(data){
						if(data==0){
							show=true;
						}
					}
				});
				return show;
			}

		}],
		searchitems : [{
					display : "模板名称",
					name : 'templateName'
				}],
		sortname : "id",
		// 默认搜索顺序
		sortorder : "DESC"

	});
});