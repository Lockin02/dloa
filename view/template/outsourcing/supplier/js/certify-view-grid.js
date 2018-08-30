var show_page = function(page) {
	$("#certifyGrid").yxgrid("reload");
};
$(function() {
	var suppId=$("#suppId").val();
	$("#certifyGrid").yxgrid({
		model : 'outsourcing_supplier_certify',
		title : '��Ӧ������֤��',
		param:{'suppId':suppId},
		bodyAlign:'center',
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		showcheckbox:false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'suppName',
			display : '�����Ӧ��',
			width:150,
			sortable : true
		}, {
			name : 'typeName',
			display : '����',
			width:70,
			sortable : true
		}, {
			name : 'certifyName',
			display : '��������',
			width:150,
			sortable : true
		}, {
			name : 'certifyLevel',
			display : '���ʵȼ�',
			width:50,
			sortable : true,
			process:function(v){
					if(v=="V"){
						return "�� ";
					}else if(v=="X"){
						return "��";
					}else{
						return "";
					}
			}
		}, {
			name : 'certifyCode',
			display : '����֤����',
			width:150,
			sortable : true
		}, {
			name : 'beginDate',
			display : '������Ч��(��ʼ��)',
			width:120,
			sortable : true
		}, {
			name : 'endDate',
			display : '������Ч��(��ֹ��)',
			width:120,
			sortable : true
		}, {
			name : 'certifyCompany',
			display : '���ʷ��ŵ�λ',
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
						display : "��������",
						name : 'certifyName'
					},{
						display : "����֤����",
						name : 'certifyCode'
					},{
						display : "���ʷ��ŵ�λ",
						name : 'certifyCompany'
					}],

				sortname : 'typeName',
				sortorder : 'ASC'
	});
});