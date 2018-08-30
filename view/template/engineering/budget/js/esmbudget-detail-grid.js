$(function() {
	var projectId = $("#projectId").val();
	var parentName = $("#parentName").val();
	var budgetName = $("#budgetName").val();
	var budgetType = $("#budgetType").val();

	$("#esmbudgetGrid").yxeditgrid({
		url : '?model=engineering_budget_esmbudget&action=searhDetailJson',
		type : 'view',
		param : {
			"projectId" : projectId,
			"parentName" : parentName,
			"budgetName" : budgetName,
			"budgetType" : budgetType
		},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden'
			}, {
				name : 'parentName',
				display : '���ô���',
				align : 'left',
				process : function(v,row){
					if(row.id == 'noId'){
						return v;
					}
					switch(row.budgetType){
						case 'budgetField' : return '<span class="blue">'+ v +'</span>';break;
						case 'budgetPerson' : return '<span class="green" >'+ v +'</span>';break;
						case 'budgetOutsourcing' : return '<span style="color:gray;">'+ v +'</span>';break;
						case 'budgetOther' : return '����Ԥ��';break;
					}
				}
			}, {
				name : 'budgetName',
				display : '����С��',
				align : 'left',
				width : 120
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				type : 'hidden'
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				type : 'hidden'
			}, {
				name : 'price',
				display : '����',
				process : function(v,row) {
                    if(row.customPrice == "1"){
                        return "<span class='blue' title='�Զ���۸�'>" + moneyFormat2(v) + "</span>";
                    }else{
                        return moneyFormat2(v);
                    }
				},
				align : 'right',
				width : 80
			}, {
				name : 'numberOne',
				display : '����1',
				align : 'right',
				width : 70
			}, {
				name : 'numberTwo',
				display : '����2',
				align : 'right',
				width : 70
			}, {
				name : 'amount',
				display : 'С��',
				align : 'right',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'budgetType',
				display : '��������',
				process : function(v){
					switch(v){
						case 'budgetField' : return '<span class="blue">�ֳ�Ԥ��</span>';break;
						case 'budgetPerson' : return '<span class="green">����Ԥ��</span>';break;
						case 'budgetOutsourcing' : return '<span style="color:gray">���Ԥ��</span>';break;
						case 'budgetOther' : return '����Ԥ��';break;
					}
				},
				width : 80,
				type : 'hidden'
			}, {
				name : 'remark',
				display : '��ע˵��',
				align : 'left',
				width : 300
			}
		],
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.id != "noId") {
					return true;
				}
				return false;
			},
			action : 'toView'
		},
		//��������
		comboEx : [{
			text : '��������',
			key : 'budgetType',
			data : [{
				text : '�ֳ�Ԥ��',
				value : 'budgetField'
			}, {
				text : '����Ԥ��',
				value : 'budgetPerson'
			}, {
				text : '���Ԥ��',
				value : 'budgetOutsourcing'
			}, {
				text : '����Ԥ��',
				value : 'budgetOther'
			}]
		}],
		searchitems : [{
				display : "����С��",
				name : 'budgetNameSearch'
			}, {
				display : "���ô���",
				name : 'parentNameSearch'
			}
		],
		sortname : 'c.budgetType,c.parentName',
		sortorder : 'ASC'
	});
});