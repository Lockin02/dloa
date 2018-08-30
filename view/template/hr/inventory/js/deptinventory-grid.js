var show_page = function(page) {
	$("#deptinventoryGrid").yxgrid("reload");
};
$(function() {

	$("#deptinventoryGrid").yxgrid({

		model : 'hr_inventory_stageinventory&action=listJson',
		param : {
			isUse : '1'
		},
		title : '�׶��̵�',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		formWidth : 800,
		formHeight : 800,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'inventoryName',
					display : '�̵�����',
					sortable : true,
					width : 250
				}, {
					name : 'inventoryTypeCode',
					display : '�̵�����',
					datacode : 'JDPD',
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 250
				}],
		menusEx : [{
			text : '��д�̵���Ϣ',
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
			text : '��д�ܽ���Ϣ',
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
					display : "ģ������",
					name : 'templateName'
				}],
		sortname : "id",
		// Ĭ������˳��
		sortorder : "DESC"

	});
});