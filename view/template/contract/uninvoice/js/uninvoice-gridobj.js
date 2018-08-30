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
		title : '��ͬ����Ʊ���',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'objCode',
			display : 'Դ�����',
			sortable : true,
			hide : true
		}, {
			name : 'objType',
			display : 'Դ������',
			sortable : true,
			hide : true,
			datacode : 'KPRK'
		}, {
			name : 'isRed',
			display : '�Ƿ����',
			sortable : true,
			hide : true,
			process : function(v){
				if(v == '1'){
					return '��';
				}else{
					return '��';
				}
			}
		}, {
			name : 'money',
			display : '���',
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
			display : '����',
			sortable : true,
			width : 400
		}, {
			name : 'createName',
			display : '¼����',
			sortable : true
		}, {
			name : 'createId',
			display : '¼����id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '¼��ʱ��',
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