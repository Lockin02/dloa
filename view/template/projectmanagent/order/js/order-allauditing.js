/**��Ʊ�����б�**/

var show_page=function(page){
   $("#orderGrid").yxgrid("reload");
};

$(function(){
    $("#orderGrid").yxgrid({
    	model:'projectmanagent_order_order',
    	action:'allAuditingPagejson',
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
 					process : function(v,row){
 						if(row.isTemp == 1 ){
 							switch(v){
								case '���۶�������':return '���ۺ�ͬ���';break;
								case '�����ͬ����':return '�����ͬ���';break;
								case '���޺�ͬ����':return '���޺�ͬ���';break;
								case '�з���ͬ����':return '�з���ͬ���';break;
 							}
 						}else{
	 						if(v == '���۶�������'){
								return '���ۺ�ͬ����';
	 						}else{
	 							return v;
	 						}
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
				if(row.isTemp == 0){
					switch(row.ExaName){
						case '���۶�������': showOpenWin("?model=projectmanagent_order_order&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�����ͬ����': showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '���޺�ͬ����': showOpenWin("?model=contract_rental_rentalcontract&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�з���ͬ����': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�����쳣�ر�����': showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�����ͬ�쳣�ر�����': showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '���޺�ͬ�쳣�ر�����': showOpenWin("?model=contract_rental_rentalcontract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�з���ͬ�쳣�ر�����': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					}
				}else{
					switch(row.ExaName){
						case '���۶�������': showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�����ͬ����': showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '���޺�ͬ����': showOpenWin("?model=contract_rental_rentalcontract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '�з���ͬ����': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					}
				}
			}
		},
			{
				text : '����',
				icon : 'edit',
				action : function(row,rows,grid){
					if(row.isTemp == 0){//�Ǳ������
						switch(row.ExaName){
							case '���۶�������': location = "controller/projectmanagent/order/ewf_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_sale_order"
								+ "&skey="
								+ row['skey_'];break;
							case '�����ͬ����': location = "controller/engineering/serviceContract/ewf_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_contract_service"
								+ "&skey="
								+ row['skey_'];break;
							case '���޺�ͬ����': location = 'controller/contract/rental/ewf_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'];break;
							case '�з���ͬ����': location = 'controller/rdproject/yxrdproject/ewf_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'] ;break;
		                	case '�����쳣�ر�����': location = "controller/projectmanagent/order/ewf_close.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_sale_order"
								+ "&skey="
								+ row['skey_'];break;
							case '�����ͬ�쳣�ر�����': location = "controller/engineering/serviceContract/ewf_close.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_contract_service"
								+ "&skey="
								+ row['skey_'];break;
							case '���޺�ͬ�쳣�ر�����': location = 'controller/contract/rental/ewf_close.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'];break;
		                	case '�з���ͬ�쳣�ر�����': location = 'controller/rdproject/yxrdproject/ewf_close.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'] ;break;
						}
					}else{
						switch(row.ExaName){
							case '���۶�������': location = "controller/projectmanagent/order/ewf_change_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_sale_order"
								+ "&skey="
								+ row['skey_'];break;
							case '�����ͬ����': location = "controller/engineering/serviceContract/ewf_change_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_contract_service"
								+ "&skey="
								+ row['skey_'];break;
							case '���޺�ͬ����': location = 'controller/contract/rental/ewf_change_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'];break;
							case '�з���ͬ����': location = 'controller/rdproject/yxrdproject/ewf_change_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'] ;break;
						}
					}
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