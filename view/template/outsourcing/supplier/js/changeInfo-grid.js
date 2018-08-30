var show_page = function(page) {
	$("#changeInfoGrid").yxgrid("reload");
};
$(function() {
	var suppId=$("#suppId").val();
	$("#changeInfoGrid").yxgrid({
		model : 'outsourcing_supplier_changeInfo',
		title : '等级变更记录',
		param:{'suppId':suppId},
		bodyAlign:'center',
		isAddAction:false,
		isViewAction:false,
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
			name : 'suppGradeOld',
			display : '原认证等级',
			sortable : true,
			width:70,
			process:function(v){
					if(v=="1"){
						return "金 ";
					}else if(v=="2"){
						return "银";
					}else if(v=="3"){
						return "铜";
					}else if(v=="4"){
						return "黑名单";
					}
			}
		}, {
			name : 'suppGrade',
			display : '变更后级别',
			sortable : true,
			width:70,
			process:function(v){
					if(v=="1"){
						return "金 ";
					}else if(v=="2"){
						return "银";
					}else if(v=="3"){
						return "铜";
					}else if(v=="4"){
						return "黑名单";
					}
			}
		}, {
			name : 'remark',
			display : '变更原因',
			width:420,
			align:'left',
			sortable : true
		}, {
			name : 'createName',
			display : '变更申请人',
			width:70,
			sortable : true
		} ,{
			name : 'createTime',
			display : '变更申请时间',
			width:120,
			sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=outsourcing_supplier_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '从表字段'
			}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "搜索字段",
			name : 'XXX'
		}]
	});
});