/**��Ʊ�����б�**/

var show_page=function(page){
   $("#pickingapply").yxgrid("reload");
};

$(function(){
        $("#pickingapply").yxgrid({

        	model:'stock_picking_pickingapply',
        	action:'pageJsonAuditYes',
        	title:'���������ϳ���',
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
							text : '�������',
							icon : 'view',
							action : function(row,rows,grid){
				             showThickboxWin('controller/common/readview.php?itemtype=oa_stock_pickingapply&pid='
					             +row.applyId
					             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
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