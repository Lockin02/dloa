$(document).ready(function() {
	//�����Ա��Ⱦ
	$("#activityMembers").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_activity_esmactmember&action=listJson',
		type : 'view',
		isAdd : false,
		tableClass : 'form_in_table',
		param : {
			'activityId' : $("#id").val()
		},
		colModel : [{
			display : '����',
			name : 'memberName',
			width : 200
		}, {
			display : '��Ա�ȼ�',
			name : 'personLevel',
			width : 130
		}, {
			display : '��ɫ',
			name : 'roleName',
			width : 130
		}, {
			display : 'ʵ�ʿ�ʼ',
			name : 'actBeginDate',
			width : 120
		}, {
			display : 'ʵ�ʽ���',
			name : 'actEndDate',
			width : 120
		}, {
			display : '����',
			name : 'personDays'
		}, {
			display : '�����ɱ�',
			name : 'personCostDays',
			type : 'hidden'
		}, {
			display : '�����ɱ����',
			name : 'personCost',
			process : function(v){
				return moneyFormat2(v);
			},
			type : 'hidden'
		}]
	});

	//��������Ԥ��
	$("#activityPersons").yxeditgrid({
		objName : 'esmEditArr',
		url : '?model=engineering_person_esmperson&action=taskListJson',
		type : 'view',
		isAdd : false,
		tableClass : 'form_in_table',
		param : {
			'activityId' : $("#id").val()
		},
		colModel : [{
			display : '��Ա�ȼ�',
			name : 'personLevel',
			width : 120
		}, {
			display : 'Ԥ�ƿ�ʼ',
			name : 'planBeginDate',
			width : 120
		}, {
			display : 'Ԥ�ƽ���',
			name : 'planEndDate',
			width : 120
		}, {
			display : '����',
			name : 'days'
		}, {
			display : '����',
			name : 'number'
		}, {
			display : '�˹�����',
			name : 'personDays',
			tclass : 'txtshort'
		}, {
			display : '�����ɱ�',
			name : 'personCostDays',
			tclass : 'txtshort'
		}, {
			display : '�����ɱ����',
			name : 'personCost',
			process : function(v){
				return moneyFormat2(v);
			}
		}]
	});

	//����Ԥ����Ⱦ
	$("#activityBudgets").yxeditgrid({
		objName : 'esmbudget[budgets]',
		url : '?model=engineering_budget_esmbudget&action=listJson',
		param : {
			'activityId' : $("#id").val(),
			'projectId' : $("#projectId").val()
		},
		tableClass : 'form_in_table',
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'Ԥ����ĿId',
			name : 'budgetId',
			type : 'hidden'
		}, {
			display : '�ϼ�id',
			name : 'parentId',
			type : 'hidden'
		}, {
			display : '���÷���',
			name : 'parentName'
		}, {
			display : 'Ԥ����Ŀ',
			name : 'budgetName'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			type : 'money',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '����1',
			name : 'numberOne',
			tclass : 'txtshort'
		}, {
			display : '��λ1',
			name : 'unit',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '����2',
			name : 'numberTwo',
			tclass : 'txtshort'
		}, {
			display : '��λ2',
			name : 'unitTwo',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '���',
			name : 'amount',
			tclass : 'txtshort',
			type : 'money',
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			display : '��ע˵��',
			name : 'remark',
			width : 220
		}]
	});

	//������ϸ��ͷ
	var feeColModel = [{
		display : 'createId',
		name : 'createId',
		type : 'hidden'
	},{
		display : '¼����Ա',
		name : 'createName',
		width : 120
	}];
	var feeTitle = "";
	var tempJson = "";
	//��ȡ������
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_cost_esmcostdetail&action=getFeeTitle",
	    data: {
	    	'activityId' : $("#id").val(),
			'projectId' : $("#projectId").val()
		},
	    async: false,
	    success: function(data){
	    	if(data){
	   			feeTitle = eval("(" + data + ")");
	   			for(var i = 0;i < feeTitle.length ; i++){
	   				tempJson = {
						display : feeTitle[i].costType,
						name :  feeTitle[i].costTypeId,
						process : function(v){
							if(v == "" || v == undefined){
								return '0.00';
							}
							return moneyFormat2(v);
						}
	   				};
					feeColModel.push(tempJson);
	   			}
	    	}
		}
	});
	if(feeTitle){
		//������ϸ
		$("#activityFees").yxeditgrid({
			url : '?model=engineering_cost_esmcostdetail&action=feeListJson',
			param : {
				'activityId' : $("#id").val(),
				'projectId' : $("#projectId").val()
			},
			tableClass : 'form_in_table',
			type : 'view',
			colModel : feeColModel
		});
	}else{
		$("#activityFees").html("û����ط�����Ϣ");
	}
});
