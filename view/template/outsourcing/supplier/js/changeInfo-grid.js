var show_page = function(page) {
	$("#changeInfoGrid").yxgrid("reload");
};
$(function() {
	var suppId=$("#suppId").val();
	$("#changeInfoGrid").yxgrid({
		model : 'outsourcing_supplier_changeInfo',
		title : '�ȼ������¼',
		param:{'suppId':suppId},
		bodyAlign:'center',
		isAddAction:false,
		isViewAction:false,
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
			name : 'suppGradeOld',
			display : 'ԭ��֤�ȼ�',
			sortable : true,
			width:70,
			process:function(v){
					if(v=="1"){
						return "�� ";
					}else if(v=="2"){
						return "��";
					}else if(v=="3"){
						return "ͭ";
					}else if(v=="4"){
						return "������";
					}
			}
		}, {
			name : 'suppGrade',
			display : '����󼶱�',
			sortable : true,
			width:70,
			process:function(v){
					if(v=="1"){
						return "�� ";
					}else if(v=="2"){
						return "��";
					}else if(v=="3"){
						return "ͭ";
					}else if(v=="4"){
						return "������";
					}
			}
		}, {
			name : 'remark',
			display : '���ԭ��',
			width:420,
			align:'left',
			sortable : true
		}, {
			name : 'createName',
			display : '���������',
			width:70,
			sortable : true
		} ,{
			name : 'createTime',
			display : '�������ʱ��',
			width:120,
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_supplier_NULL&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'XXX',
				display : '�ӱ��ֶ�'
			}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});