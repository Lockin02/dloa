/**��Ʊ�����б�**/

var show_page=function(page){
   $("#orderGrid").yxgrid("reload");
};

$(function(){
        $("#orderGrid").yxgrid({

        	model:'projectmanagent_order_order',
        	action:'pageJsonAuditYes',
        	title:'���������۶���',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,
        	isAddAction:false,
        	isEditAction:false,
        	isDelAction:false,

			colModel :
			[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
    				name : 'sign',
  					display : '�Ƿ�ǩԼ',
  					sortable : true
              },{
    				name : 'orderstate',
  					display : 'ֽ�ʺ�ͬ״̬',
  					sortable : true
              },{
    				name : 'parentOrder',
  					display : '����ͬ����',
  					sortable : true,
  					hide : true
              },{
					name : 'orderCode',
  					display : '������ͬ��',
  					sortable : true
              },{
					name : 'orderTempCode',
  					display : '��ʱ��ͬ��',
  					sortable : true
              },{
					name : 'orderName',
  					display : '��ͬ����',
  					sortable : true
              },{
					name : 'prinvipalName',
  					display : '��ͬ������',
  					sortable : true
              },{
					name : 'deliveryDate',
  					display : '��������',
  					sortable : true
              },{
					name : 'state',
  					display : '��ͬ״̬',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "δ�ύ";
  						}else if(v == '1'){
  							return "������";
  						}else if(v == '2'){
  							return "ִ����";
  						}else if(v == '3'){
  							return "�ѹر�";
  						}else if(v == '4'){
  						    return "�����";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '����״̬',
  					sortable : true,
  					width : 90
              },{
						display : '����ʱ��',
						name : 'ExaDT',
						width:80
					}
          ],


					//��չ�Ҽ��˵�
					menusEx : [
					{
						text : '�鿴',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=projectmanagent_order_order&action=init&perm=view&id="+ row.orderId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					}
					],
			searchitems:[
			        {
			            display:'��ͬ����',
			            name:'orderName'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});