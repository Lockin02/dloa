/**��Ʊ�����б�**/

var show_page=function(page){
   $("#pickingapply").yxgrid("reload");
};

$(function(){
        $("#pickingapply").yxgrid({

        	model:'stock_picking_pickingapply',
        	action:'pageJsonAuditNo',
        	title:'���������۳���',
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
					},{
						display : 'applyId',
						name : 'applyId',
						sortable : true,
						hide : true
					}, {
						display : '�������뵥��',
						name : 'pickingCode',
						sortable : true,
						width:130
					},{
						display : '��������',
						name : 'pickingType',
						width:130
					},
					{
						display : '��������',
						name : 'task',
						width:80
					},
					{
						display : '���ϲֿ�',
						name : 'stockName'
					},
					{
						display : '������',
						name : 'pickName'
					},
					{
						display : '������',
						name : 'sendName'
					},
					{
						display : '������',
						name : 'createName'
					},
					{
						display : '����״̬',
						name : 'ExaStatus',
						width:80
					},
					{
						display : '����ʱ��',
						name : 'ExaDT'
					}],


					//��չ�Ҽ��˵�
					menusEx : [
					{
						text : '�鿴',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=stock_picking_pickingapply&action=init&perm=view&id="+ row.applyId
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					},
						{
							text : '����',
							icon : 'edit',
							action : function(row,rows,grid){
								if(row){
									location = "controller/stock/picking/ewf_index.php?actTo=ewfExam&taskId="+row.task+"&spid="+row.id+"&billId="+row.applyId+"&examCode=oa_stock_pickingapply";
								}
							}
						}
					],
			searchitems:[
			        {
			            display:'��Ʊ���뵥��',
			            name:'applyNo'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});