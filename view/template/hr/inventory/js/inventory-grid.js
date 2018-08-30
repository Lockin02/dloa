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
		title : '部门盘点信息',

		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'deptName',
					display : '部门名称',
					sortable : true,
					width : 150
				}, {
					name : 'inventoryName',
					display : '盘点名称',
					width : 250,
					sortable : true
				}, {
					name : 'createName',
					display : '创建人',
					width : 150,
					sortable : true
				}, {
					name : 'remark',
					display : '备注',
					width : 250,
					sortable : true
				}],
		searchitems : [{
					display : "部门名称",
					name : 'deptName'
				}, {
					display : "盘点名称",
					name : 'inventoryName'
				}],
		buttonsEx : [{
					name : 'back',
					text : "返回",
					icon : 'back',
					action : function(row) {
						history.back();
					}
				}],
		menusEx:[
			{  text:'查看盘点信息',
			   icon:'view',
			   action:function(row){
			   		if(row){
						 showModalWin("?model=hr_inventory_inventory&action=init&perm=view&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			   		}
			   }
			},{  text:'查看部门总结',
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
		// 默认搜索顺序
		sortorder : "DESC"

	});
});