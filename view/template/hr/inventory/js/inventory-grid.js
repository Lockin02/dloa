var show_page = function(page) {
	$("#inventoryGrid").yxgrid("reload");
};
$(function() {

	$("#inventoryGrid").yxgrid({

		model : 'hr_inventory_inventory&action=listJson',
		param : {
			stageId : $("#stageId").val()
		},
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		title : '�����̵���Ϣ',

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'deptName',
					display : '��������',
					sortable : true,
					width : 150
				}, {
					name : 'inventoryName',
					display : '�̵�����',
					width : 250,
					sortable : true
				}, {
					name : 'createName',
					display : '������',
					width : 150,
					sortable : true
				}, {
					name : 'remark',
					display : '��ע',
					width : 250,
					sortable : true
				}],
		searchitems : [{
					display : "��������",
					name : 'deptName'
				}, {
					display : "�̵�����",
					name : 'inventoryName'
				}],
		buttonsEx : [{
					name : 'back',
					text : "����",
					icon : 'back',
					action : function(row) {
						history.back();
					}
				}],
		menusEx:[
			{  text:'�鿴�̵���Ϣ',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 showModalWin("?model=hr_inventory_inventory&action=init&perm=view&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			   		}
			   }
			},{  text:'�鿴�����ܽ�',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 showModalWin("?model=hr_inventory_inventory&action=viewSummaryInfo&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			   		}
			   }
			}],
		sortname : "id",
		// Ĭ������˳��
		sortorder : "DESC"

	});
});