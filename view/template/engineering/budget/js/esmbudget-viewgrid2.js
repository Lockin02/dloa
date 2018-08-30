$(function() {
	var projectId = $("#projectId").val();

	$("#esmbudgetGrid").yxeditgrid({
		url : '?model=engineering_budget_esmbudget&action=searhJson',
		type : 'view',
		param : {
			"projectId" : projectId
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
				width : 200,
				process : function(v,row){
					if(row.id == 'noId' && row.budgetType != 'budgetPerson' && row.budgetType!= 'budgetField' && row.budgetType!= 'budgetTrial'){
						return v;
					}
					switch(row.budgetType){
						case 'budgetField' : return '<span class="blue">'+ v +'</span>';break;
						case 'budgetPerson' : return '<span class="green">'+ v +'</span>';break;
						case 'budgetOutsourcing' : return '<span style="color:gray;">'+ v +'</span>';break;
						case 'budgetOther' : return '����Ԥ��';break;
						case 'budgetDevice' : return '<span style="color:brown;">'+ v +'</span>';break;
						case 'budgetTrial' : return '<span style="color:orange;">'+ v +'</span>';break; 
					}
				}
			}, {
				name : 'budgetName',
				display : '����С��',
				align : 'left',
				width : 200,
				process : function(v,row){
					if(row.id == 'noId') return v;
					else if(row.budgetType == 'budgetPerson'){
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_budget_esmbudget" +
						"&action=toSearchDetailList&parentName=" + row.parentName  + "&projectId="+ projectId + "&budgetType=" + row.budgetType +"&budgetName="  +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>"+ v + "<span style='color:green'>"+row.detCount + "</span></a>";
					}
					else if(row.budgetType == 'budgetDevice'){
						return v;
					}
					else{
						if(row.isImport == 1){//����Ŀ����ά�����������
							return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_cost_esmcostmaintain" +
							"&action=toSearchDetailList&parentName=" + row.parentName+ "&budgetName=" + row.budgetName  + "&projectId="+ projectId +
							"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>"+row.detCount + "</span></a>";
						}else{
							return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_budget_esmbudget" +
							"&action=toSearchDetailList&parentName=" + row.parentName+ "&budgetName=" + row.budgetName  + "&projectId="+ projectId + "&budgetType=" + row.budgetType+
							"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "<span style='color:green'>"+row.detCount + "</span></a>";	
						}
					}
				}
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
					return moneyFormat2(v);
				},
				width : 80,
				type : 'hidden'
			}, {
				name : 'numberOne',
				display : '����1',
				width : 70,
				type : 'hidden'
			}, {
				name : 'numberTwo',
				display : '����2',
				width : 70,
				type : 'hidden'
			}, {
				name : 'amount',
				display : 'Ԥ��',
				align : 'right',
				process : function(v,row) {
					if(row.isImport == 1 && row.status == 0){//���������,δ��˵����ݱ��
						return "<span class='red'>" + moneyFormat2(v) + "</span>" ;
					}else{
                        return moneyFormat2(v);
                    }
				},
				width : 80
			}, {
				name : 'actFee',
				display : '����',
				align : 'right',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'actFeeWait',
				display : '����˾���',
				align : 'right',
				process : function(v,row) {
					if(row.isImport == 1){//���������
						if(row.status == 0){//δ��˵����ݱ��
							return "<span class='red'>" + moneyFormat2(v) + "</span>" ;
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width : 80
			}, {
				name : 'feeProcess',
				display : '���ý���',
				align : 'right',
				process : function(v,row) {
					if(v){
						if(v * 1> 100){
							return "<span class='red'>" + v + " %</span>" ;
						}else{
							return v + " %" ;
						}
					}
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
				process : function(v,row) {
					if(row.isImport == 1){//��̨���������,��ӱ�챸ע����
						if(row.actFee == undefined){//���ҳ����ʾ
							return "<span class='red'>��̨��������</span>";
						}else{
							if(row.status == 0){
								return "<span class='red'>��̨�������ݣ�δ���</span>";
							}else{
								return "<span class='red'>��̨�������ݣ������</span>";
							}	
						}					
					}else{
						return v;
					}
				},
				width : 400
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
		searchitems : [{
				display : "����С��",
				name : 'budgetNameSearch'
			}, {
				display : "���ô���",
				name : 'parentNameSearch'
			}
		]
	}),
	
	$("#budgetType").change(function(){
		var projectId = $("#projectId").val();
		var budgetType = $("#budgetType").val();
		var paramObj = {
			projectId : projectId,
			budgetType : budgetType
		};
		$("#esmbudgetGrid").yxeditgrid("setParam",paramObj).yxeditgrid("processData");
	})
});

