var show_page = function(page) {
	$("#payablesGrid").yxgrid("reload");
};

$(function() {
	$("#payablesGrid").yxgrid({
	      model : 'finance_payables_payables',
      	title : 'Ӧ�����/Ӧ��Ԥ����/Ӧ���˿',
			//����Ϣ
			colModel : [{
								display : 'id',
								name : 'id',
								sortable : true,
								hide : true
				        }                  ,{
           					name : 'payApplyId',
         					display : '�������id',
         					sortable : true
                     }                  ,{
           					name : 'payApplyNo',
         					display : '����������',
         					sortable : true
                     }                  ,{
           					name : 'formNo',
         					display : '���ݺ�',
         					sortable : true
                     }                  ,{
           					name : 'formDate',
         					display : '��������',
         					sortable : true
                     }                  ,{
           					name : 'financeDate',
         					display : '��������',
         					sortable : true
                     }                  ,{
           					name : 'supplierId',
         					display : '��Ӧ��id',
         					sortable : true
                     }                  ,{
           					name : 'supplierCode',
         					display : '��Ӧ�̱��',
         					sortable : true
                     }                  ,{
           					name : 'supplierName',
         					display : '��Ӧ������',
         					sortable : true
                     }                  ,{
           					name : 'payType',
         					display : '��������',
         					sortable : true
                     }                  ,{
           					name : 'payNo',
         					display : '�����',
         					sortable : true
                     }                  ,{
           					name : 'cashsubject',
         					display : '�ֽ����Ŀ',
         					sortable : true
                     }                  ,{
           					name : 'bank',
         					display : '��������',
         					sortable : true
                     }                  ,{
           					name : 'account',
         					display : '�ʺ�',
         					sortable : true
                     }                  ,{
           					name : 'currency',
         					display : '�ұ�',
         					sortable : true
                     }                  ,{
           					name : 'rate',
         					display : '����',
         					sortable : true
                     }                  ,{
           					name : 'certificate',
         					display : 'ƾ֤�ֺ�',
         					sortable : true
                     }                  ,{
           					name : 'amount',
         					display : '���ݽ��',
         					sortable : true
                     }                  ,{
           					name : 'remark',
         					display : '��ע',
         					sortable : true
                     }                  ,{
           					name : 'deptId',
         					display : '����id',
         					sortable : true
                     }                  ,{
           					name : 'deptName',
         					display : '����',
         					sortable : true
                     }                  ,{
           					name : 'salesman',
         					display : 'ҵ��Ա',
         					sortable : true
                     }                  ,{
           					name : 'salesmanId',
         					display : 'ҵ��Ա�ʺ�',
         					sortable : true
                     }                  ,{
           					name : 'status',
         					display : '����״̬',
         					sortable : true
                     }                  ,{
           					name : 'ExaStatus',
         					display : '����״̬',
         					sortable : true
                     }                  ,{
           					name : 'ExaDT',
         					display : '����ʱ��',
         					sortable : true
                     }                  ,{
           					name : 'createId',
         					display : '������ID',
         					sortable : true
                     }                  ,{
           					name : 'createName',
         					display : '����������',
         					sortable : true
                     }                  ,{
           					name : 'createTime',
         					display : '����ʱ��',
         					sortable : true
                     }                  ,{
           					name : 'updateId',
         					display : '�޸���ID',
         					sortable : true
                     }                  ,{
           					name : 'updateName',
         					display : '�޸�������',
         					sortable : true
                     }                  ,{
           					name : 'updateTime',
         					display : '�޸�ʱ��',
         					sortable : true
                     }         ]
 		});
 });