/**��Ʊ�����б�**/

var show_page=function(page){
   $("#returnGrid").yxgrid("reload");
};

$(function(){
        $("#returnGrid").yxgrid({

        	model:'projectmanagent_return_return',
        	action:'pageJsonAuditNo',
//        	title:'������',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,
        	isAddAction:false,
        	isEditAction:false,
        	isDelAction:false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'renturnCode',
			display : '�˻������',
			sortable : true
		}, {
			name : 'orderCode',
			display : 'Դ����',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '��ͬ������',
			sortable : true
		},{
			name : 'saleWay',
			display : '���۷�ʽ',
			sortable : true
		}, {
			name : 'storage',
			display : '�ջ��ֿ�',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '��������',
			sortable : true
		}, {
			name : 'returnCause',
			display : '�˻�ԭ��',
			sortable : true
		}],



					//��չ�Ҽ��˵�
					menusEx : [
					{
						text : '�鿴',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=projectmanagent_return_return&action=init&perm=view&id="+ row.returnId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					},
						{
						text : '����',
						icon : 'edit',
						action: function(row){
			                location = 'controller/projectmanagent/return/ewf_index.php?taskId='+
			                	row.task +
			                	'&spid=' +
			                	row.id +
			                	'&billId=' +
			                	row.returnId +  '&actTo=ewfExam';
						}
					}
					],
			searchitems:[
			        {
			            display:'�˻������',
			            name:'returnCode'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});