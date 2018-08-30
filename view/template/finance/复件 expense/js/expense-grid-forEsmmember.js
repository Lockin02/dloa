var show_page = function(page) {
	$("#expenseGrid").yxgrid("reload");
};

//�鿴���� - �����¾ɱ�����
function viewBill(id,billNo,isNew){
	if(isNew == '1'){
		showModalWin("?model=finance_expense_exsummary&action=toView&id="+ id ,1)
	}else{
		showOpenWin("general/costmanage/reim/summary_detail.php?status=���ɸ���&BillNo="+ billNo ,1)
	}
}

$(function() {
	$("#expenseGrid").yxgrid({
		model : 'finance_expense_expense',
		title : '������ϸ',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		param : {
			"costUser" : $("#userAccount").val(),
			"ProjectNo" : $("#projectNo").val()
			},
		showcheckbox : false,
		isOpButton : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�յ�',
			name : 'recView',
			sortable : true,
			align : 'center',
			width : 30,
			process : function(v,row){
				if(row.needExpenseCheck == "1"){
					if(row.IsFinRec == '1'){
						return '<img title="�����յ�['+ row.RecInvoiceDT +'] \n�Ͻ�����['+ row.HandUpDT +'] \n�����յ�[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}else{
						if(row.isHandUp == "1"){
							return '<img title="�����յ�['+ row.RecInvoiceDT +'] \n�Ͻ�����['+ row.HandUpDT +']" src="images/icon/ok2.png" style="width:15px;height:15px;">';
						}else{
							if(row.isNotReced == '0'){
								return '<img title="�����յ�['+ row.RecInvoiceDT +']" src="images/icon/ok1.png" style="width:15px;height:15px;">';
							}
						}
					}
				}else{
					if(row.IsFinRec == '1'){
						return '<img title="�����յ�[' + row.FinRecDT + ']" src="images/icon/ok3.png" style="width:15px;height:15px;">';
					}
				}
			}
		}, {
			display : '�����յ�״̬',
			name : 'isNotReced',
			sortable : true,
			width : 60,
			hide : true,
			process : function(v,row){
				if(v == '0'){
					return '���յ�';
				}else{
					return 'δ�յ�';
				}
			}
		}, {
			display : '�����յ�ʱ��',
			name : 'RecInvoiceDT',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '�����Ͻ�״̬',
			name : 'isHandUp',
			sortable : true,
			width : 60,
			hide : true,
			process : function(v,row){
				if(v == '1'){
					return '���Ͻ�';
				}else{
					return 'δ�Ͻ�';
				}
			}
		}, {
			display : '�����Ͻ�ʱ��',
			name : 'HandUpDT',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '�����յ�״̬',
			name : 'IsFinRec',
			sortable : true,
			width : 60,
			hide : true,
			process : function(v,row){
				if(v == '1'){
					return '���յ�';
				}else{
					return 'δ�յ�';
				}
			}
		}, {
			display : '�����յ�ʱ��',
			name : 'FinRecDT',
			sortable : true,
			width : 120,
			hide : true
		}, {
			display : '�±�����',
			name : 'isNew',
			sortable : true,
			width : 50,
			process : function(v){
				if(v == '1'){
					return '��';
				}else{
					return '��';
				}
			},
			hide : true
		}, {
			display : '��Ҫ���ż��',
			name : 'needExpenseCheck',
			sortable : true,
			width : 50,
			process : function(v){
				if(v == '1'){
					return '��';
				}else{
					return '��';
				}
			},
			hide : true
		}, {
			name : 'BillNo',
			display : '��������',
			sortable : true,
			width : 130,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='viewBill(\""+ row.id +"\",\""+ row.BillNo +"\",\""+ row.isNew +"\")'>"+ v +"</a>";
			}
		}, {
			name : 'DetailType',
			display : '��������',
			sortable : true,
			width : 70,
			process : function(v,row){
				if(v*1 > 0){
					switch(v){
//						case '0' : return '�ɱ�����';break;
						case '1' : return '���ŷ���';break;
						case '2' : return '��ͬ��Ŀ����';break;
						case '3' : return '�з�����';break;
						case '4' : return '��ǰ����';break;
						case '5' : return '�ۺ����';break;
						default : return v;
					}
				}else{
					switch(row.CostBelongTo){
						case '1' : return '���ŷ���';break;
						default : return '���̷���';break;
					}
				}
			}
		}, {
			name : 'CostManName',
			display : '������',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'CostDepartName',
			display : '�����˲���',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'CostManCom',
			display : '�����˹�˾',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'CostBelongDeptName',
			display : '���ù�������',
			sortable : true,
			width : 75
		}, {
			name : 'CostBelongCom',
			display : '���ù�����˾',
			sortable : true,
			width : 75
		}, {
			name : 'Amount',
			display : '�������',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'feeRegular',
			display : '�������',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'feeSubsidy',
			display : '��������',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			},
			hide : true
		}, {
			name : 'invoiceMoney',
			display : '��Ʊ���',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'invoiceNumber',
			display : '��Ʊ����',
			sortable : true,
			width : 60,
			hide : true
		}, {
			name : 'CheckAmount',
			display : '�����',
			sortable : true,
			hide : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'isProject',
			display : '��Ŀ����',
			sortable : true,
			process : function(v){
				if(v == '1'){
					return '��';
				}else{
					return '��';
				}
			},
			width : 60,
			hide : true
		}, {
			name : 'ProjectNO',
			display : '��Ŀ���',
			sortable : true,
			width : 150,
			hide : true
		}, {
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 150,
			hide : true
		}, {
			name : 'proManagerName',
			display : '��Ŀ����',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'CustomerType',
			display : '�ͻ�����',
			sortable : true,
			hide : true
		}, {
			name : 'Purpose',
			display : '����',
			sortable : true,
			width : 180
		}, {
			name : 'InputManName',
			display : '¼����',
			sortable : true,
			width : 90,
			hide : true
		}, {
			name : 'Status',
			display : '����״̬',
			sortable : true,
			width : 70
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 70,
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '��������',
			width : 80,
			sortable : true
		}, {
			name : 'InputDate',
			display : '¼��ʱ��',
			sortable : true,
			width : 130
		}, {
			name : 'UpdateDT',
			display : '����ʱ��',
			sortable : true,
			width : 130,
			hide : true
		}],
		//�򿪵���expense�еķ��� -- ���αȽ�����
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				viewBill(row.id,row.BillNo,row.isNew);
			}
		},
		//��������
		comboEx : [{
			text : '��������',
			key : 'DetailType',
			data : [{
				text : '���ŷ���',
				value : '1'
			}, {
				text : '��ͬ��Ŀ����',
				value : '2'
			}, {
				text : '�з�����',
				value : '3'
			}, {
				text : '��ǰ����',
				value : '4'
			}, {
				text : '�ۺ����',
				value : '5'
			}, {
				text : '�ɱ�����',
				value : '0'
			}]
		},{
			text : '����״̬',
			key : 'StatusArr',
			value : '���,�༭,���ż��,�ȴ�ȷ��,��������,�������,���ɸ���,���',
			data : [{
				text : '�༭',
				value : '�༭'
			}, {
				text : '���ż��',
				value : '���ż��'
			}, {
				text : '�ȴ�ȷ��',
				value : '�ȴ�ȷ��'
			}, {
				text : '��������',
				value : '��������'
			}, {
				text : '�������',
				value : '�������'
			}, {
				text : '���ɸ���',
				value : '���ɸ���'
			}, {
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}],
		searchitems : [{
			display : "��������",
			name : 'BillNoSearch'
		}, {
			display : "��Ŀ����",
			name : 'projectNameSearch'
		}, {
			display : "��Ŀ���",
			name : 'projectCodeSearch'
		}, {
			display : "�̻����",
			name : 'chanceCodeSearch'
		}, {
			display : "�̻�����",
			name : 'chanceNameSearch'
		}, {
			display : "��ͬ���",
			name : 'contractCodeSearch'
		}, {
			display : "��ͬ����",
			name : 'contractNameSearch'
		}, {
			display : "�������",
			name : 'Amount'
		}, {
			display : "������",
			name : 'InputManNameSearch'
		}, {
			display : "����",
			name : 'PurposeSearch'
		}],
		sortname : "c.UpdateDT"
	});
});