
var show_page=function(page){
   $("#orderChangeGrid").yxgrid("reload");
};

$(function(){
        $("#orderChangeGrid").yxgrid({

        	model:'projectmanagent_order_order',
        	action:'changeAuditNo',
        	title:'δ�����ı�����ۺ�ͬ',
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
					name : 'isTemp',
  					display : 'isTemp',
  					hide : true
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
					},
						{
							text : '����',
							icon : 'edit',
							action : function(row,rows,grid){
								if(row){
									location = "controller/projectmanagent/order/ewf_change_index.php?actTo=ewfExam&orderId="+row.orderId+"&spid="+row.id+"&billId="+row.orderId+"&examCode=oa_sale_order&isTemp="+row.isTemp + "&skey="+row['skey_'];
								}
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