var show_page = function(page) {
	$("#proejctGrid").yxgrid("reload");
};
$(function() {
	var suppId=$("#suppId").val();
	$("#proejctGrid").yxgrid({
		model : 'outsourcing_supplier_proejct',
		action:'pageJsonProject',
		title : '��Ŀ��Ϣ',
		param:{'signCompanyId':suppId},
		bodyAlign:'center',
		isViewAction:false,
		isAddAction:false,
		isEditAction:false,
		isDelAction:false,
		isOpButton : false,
		showcheckbox:false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},  {
			name : 'projectCode',
			width:'120',
			display : '��Ŀ���',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.projectId +"\",1)'>" + v + "</a>";
			}
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			width:'150',
			sortable : true
		}, {
			name : 'signCompanyName',
			display : '�����Ӧ��',
			width:'150',
			sortable : true
		}, {
			name : 'outContractCode',
			display : '������',
			width:'120',
			sortable : true,
			process : function(v,row){
				 var formId = row.id;
			        var skey = "";
			        $.ajax({
					    type: "POST",
					    url: "?model=contract_outsourcing_outsourcing&action=md5RowAjax",
					    data: { "id" : formId },
					    async: false,
					    success: function(data){
					   	   skey = data;
						}
					});
					return "<a href='#' onclick='showModalWin(\"?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + skey +"\",1)'>" + v + "</a>";
			}
		},{
			name : 'natureName',
			display : '��Ŀ����',
			width:'70',
			sortable : true
		},{
			name : 'outsourcingName',
			display : '�����ʽ',
			width:'70',
			sortable : true
		}, {
			name : 'personNum',
			display : '��Ա����',
			width:'50',
			sortable : true
		}, {
			name : 'beginDate',
			display : '��Ŀʵʩ����(��ʼ)',
			sortable : true
		}, {
			name : 'endDate',
			display : '��Ŀʵʩ����(����)',
			sortable : true
		}, {
			name : 'orderMoney',
			display : '������',
			sortable : true
		}, {
			name : 'checkScore',
			display : '���˵÷�',
			width:'50',
			sortable : true
		}, {
			name : 'deductReason',
			display : '�۷�ԭ��',
			width:'250',
			align:'left',
			sortable : true
		}, {
			name : 'evaluate',
			display : '��Ŀ����',
			width:'250',
			align:'left',
			sortable : true
		}],
		lockCol:['projectCode','projectName'],//����������
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
							display : "��Ŀ���",
							name : 'projectCode'
						},{
							display : "��Ŀ����",
							name : 'projectName'
						}]
	});
});