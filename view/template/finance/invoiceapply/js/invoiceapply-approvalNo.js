/**��Ʊ�����б�**/

var show_page=function(page){
   $("#invoiceapply").yxgrid("reload");
};

$(function(){

        $("#invoiceapply").yxgrid({

        	model:'finance_invoiceapply_invoiceapply',
        	action:'pageJsonAuditNo',
        	title:'��������Ʊ����',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,

			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '���뵥��',
				name : 'applyNo',
				sortable : true,
				width:130
			},{
				display : 'ҵ����',
				name : 'objCode',
				width:140
			},{
				display : 'ҵ������',
				name : 'objType',
				sortable : true,
				datacode : 'KPRK'
			},
			{
				display : '�ͻ���λ',
				name : 'customerName',
				width:150
			},
			{
				display : '������',
				sortable : true,
				name : 'createName',
				width:90
			},
			{
				display : '��������',
				name : 'invoiceType',
				sortable : true,
				datacode : 'FPLX'
			},
			{
				display : '������',
				name : 'invoiceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '����״̬',
				sortable : true,
				name : 'ExaStatus',
				width:80
			},
			{
				display : '����ʱ��',
				sortable : true,
				name : 'applyDate',
				width:90
			}],


			//��չ�Ҽ��˵�
			menusEx : [
			{
				text : '�鿴',
				icon : 'view',
				action : function(row,rows,grid){
					showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init'
					+ '&id=' + row.applyId
					+ '&skey=' + row['skey_1']
					+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000');
				}
			},
				{
					text : '����',
					icon : 'edit',
					action : function(row,rows,grid){
						location = "controller/finance/invoiceapply/ewf_index.php?actTo=ewfExam&taskId="+row.task+"&spid="+row.id+"&billId="+row.applyId+"&examCode=oa_finance_invoiceapply"
							+ '&skey=' + row['skey_1'];
					}
				}
			],
			searchitems:[
		        {
		            display:'��Ʊ���뵥��',
		            name:'applyNo'
		        }
	        ],
			sortorder:'ASC'
        });
});