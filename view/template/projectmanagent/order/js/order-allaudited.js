/**��Ʊ�����б�**/

var show_page=function(page){
   $("#orderGrid").yxgrid("reload");
};

$(function(){
    $("#orderGrid").yxgrid({
    	model:'projectmanagent_order_order',
    	action:'allAuditedPagejson',
    	title:'��ͬ����',
    	showcheckbox : false,
    	isViewAction:false,
    	isAddAction:false,
    	isEditAction:false,
    	isDelAction:false,
		colModel :[
			  {
 					display : 'id',
 					name : 'id',
 					sortable : true,
 					hide : true
			  },{
 					display : '��ͬid',
 					name : 'orderId',
 					sortable : true,
 					hide : true
			  },{
 					name : 'ExaName',
 					display : '��������',
 					sortable : true,
 					process : function(v){
 						if(v == '���۶�������'){
							return '���ۺ�ͬ����';
 						}else{
 							return v;
 						}
 					}
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
  					sortable : true,
  					width : 120
              },{
					name : 'prinvipalName',
  					display : '��ͬ������',
  					sortable : true
              },{
					name : 'customerName',
  					display : '�ͻ�����',
  					sortable : true
              },{
					name : 'areaName',
  					display : '��������',
  					sortable : true,
  					width : 80
              },{
    				name : 'sign',
  					display : '�Ƿ�ǩԼ',
  					sortable : true,
  					width : 70
              },{
					name : 'ExaStatus',
  					display : '����״̬',
  					width : 80
              },{
					display : '����ʱ��',
					name : 'ExaDT',
					width:80
		}],
		//��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action : function(row,rows,grid){
				switch(row.ExaName){
					case '���۶�������': showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '�����ͬ����': showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '���޺�ͬ����': showOpenWin("?model=contract_rental_rentalcontract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '�з���ͬ����': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '�����쳣�ر�����': showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '�����ͬ�쳣�ر�����': showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '���޺�ͬ�쳣�ر�����': showOpenWin("?model=contract_rental_rentalcontract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					case '�з���ͬ�쳣�ر�����': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
				}

			}
		},
			{
				text : '�������',
				icon : 'edit',
				action : function(row,rows,grid){
					showThickboxWin('controller/common/readview.php?itemtype=' + row.ExaObj + '&pid='
						+ row.orderId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}
		],
		searchitems:[
	        {
	            display:'��ͬ��',
	            name:'orderCodeOrTempSearch'
	        }
        ],
		sortname:'ExaDT',
		sortorder:'DESC'
    });
});