var show_page = function(page) {
	$("#uninvoiceGrid").yxgrid("reload");
};
$(function() {
	$("#uninvoiceGrid").yxgrid({
		model : 'contract_uninvoice_uninvoice',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox : false,
		isOpButton : false,
		param : { "objId" : $("#objId").val(),"objType" : $("#objType").val() },
		title : '合同不开票金额',
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'objCode',
			display : '源单编号',
			sortable : true,
			hide : true
		}, {
			name : 'objType',
			display : '源单类型',
			sortable : true,
			hide : true,
			datacode : 'KPRK'
		}, {
			name : 'isRed',
			display : '是否红字',
			sortable : true,
			hide : true,
			process : function(v){
				if(v == '1'){
					return '是';
				}else{
					return '否';
				}
			}
		}, {
			name : 'money',
			display : '金额',
			sortable : true,
			process : function(v,row){
				if(row.isRed == '1'){
					return "<span class='red'>-" + moneyFormat2(v) + "</span>";
				}else{
					return moneyFormat2(v);
				}
			},
			width : 130
		}, {
			name : 'descript',
			display : '描述',
			sortable : true,
			width : 400
		}, {
			name : 'createName',
			display : '录入人',
			sortable : true
		}, {
			name : 'createId',
			display : '录入人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '录入时间',
			sortable : true,
			width : 140
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		}
	});
});