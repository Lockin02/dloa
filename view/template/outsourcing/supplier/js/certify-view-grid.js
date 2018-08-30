var show_page = function(page) {
	$("#certifyGrid").yxgrid("reload");
};
$(function() {
	var suppId=$("#suppId").val();
	$("#certifyGrid").yxgrid({
		model : 'outsourcing_supplier_certify',
		title : '供应商资质证书',
		param:{'suppId':suppId},
		bodyAlign:'center',
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'suppName',
			display : '外包供应商',
			width:150,
			sortable : true
		}, {
			name : 'typeName',
			display : '类型',
			width:70,
			sortable : true
		}, {
			name : 'certifyName',
			display : '资质名称',
			width:150,
			sortable : true
		}, {
			name : 'certifyLevel',
			display : '资质等级',
			width:50,
			sortable : true,
			process:function(v){
					if(v=="V"){
						return "有 ";
					}else if(v=="X"){
						return "无";
					}else{
						return "";
					}
			}
		}, {
			name : 'certifyCode',
			display : '资质证书编号',
			width:150,
			sortable : true
		}, {
			name : 'beginDate',
			display : '资质有效期(起始日)',
			width:120,
			sortable : true
		}, {
			name : 'endDate',
			display : '资质有效期(终止日)',
			width:120,
			sortable : true
		}, {
			name : 'certifyCompany',
			display : '资质发放单位',
			width:150,
			sortable : true
		}],
		toAddConfig : {
			action : 'toAdd&suppId='+$("#suppId").val(),
			formWidth : 800,
			formHeight : 500
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
						display : "资质名称",
						name : 'certifyName'
					},{
						display : "资质证书编号",
						name : 'certifyCode'
					},{
						display : "资质发放单位",
						name : 'certifyCompany'
					}],

				sortname : 'typeName',
				sortorder : 'ASC'
	});
});