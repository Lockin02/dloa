/**收票管理列表**/

var show_page=function(page){
   $("#orderGrid").yxgrid("reload");
};

$(function(){
    $("#orderGrid").yxgrid({
    	model:'projectmanagent_order_order',
    	action:'allAuditingPagejson',
    	title:'合同审批',
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
 					display : '合同id',
 					name : 'orderId',
 					sortable : true,
 					hide : true
			  },{
 					name : 'ExaName',
 					display : '审批类型',
 					sortable : true,
 					process : function(v,row){
 						if(row.isTemp == 1 ){
 							switch(v){
								case '销售订单审批':return '销售合同变更';break;
								case '服务合同审批':return '服务合同变更';break;
								case '租赁合同审批':return '租赁合同变更';break;
								case '研发合同审批':return '研发合同变更';break;
 							}
 						}else{
	 						if(v == '销售订单审批'){
								return '销售合同审批';
	 						}else{
	 							return v;
	 						}
 						}
 					}
			  },{
					name : 'orderCode',
  					display : '鼎利合同号',
  					sortable : true
              },{
					name : 'orderTempCode',
  					display : '临时合同号',
  					sortable : true
              },{
					name : 'orderName',
  					display : '合同名称',
  					sortable : true,
  					width : 120
              },{
					name : 'prinvipalName',
  					display : '合同负责人',
  					sortable : true
              },{
					name : 'customerName',
  					display : '客户名称',
  					sortable : true
              },{
					name : 'areaName',
  					display : '所属区域',
  					sortable : true,
  					width : 80
              },{
    				name : 'sign',
  					display : '是否签约',
  					sortable : true,
  					width : 70
              },{
					name : 'ExaStatus',
  					display : '审批状态',
  					width : 80
              },{
					display : '审批时间',
					name : 'ExaDT',
					width:80
		}],
		//扩展右键菜单
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action : function(row,rows,grid){
				if(row.isTemp == 0){
					switch(row.ExaName){
						case '销售订单审批': showOpenWin("?model=projectmanagent_order_order&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '服务合同审批': showOpenWin("?model=engineering_serviceContract_serviceContract&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '租赁合同审批': showOpenWin("?model=contract_rental_rentalcontract&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '研发合同审批': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=init&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '销售异常关闭审批': showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '服务合同异常关闭审批': showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '租赁合同异常关闭审批': showOpenWin("?model=contract_rental_rentalcontract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '研发合同异常关闭审批': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					}
				}else{
					switch(row.ExaName){
						case '销售订单审批': showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '服务合同审批': showOpenWin("?model=engineering_serviceContract_serviceContract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '租赁合同审批': showOpenWin("?model=contract_rental_rentalcontract&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
						case '研发合同审批': showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toReadTab&perm=view&id="+ row.orderId + "&skey="+row.skey_ );break;
					}
				}
			}
		},
			{
				text : '审批',
				icon : 'edit',
				action : function(row,rows,grid){
					if(row.isTemp == 0){//非变更审批
						switch(row.ExaName){
							case '销售订单审批': location = "controller/projectmanagent/order/ewf_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_sale_order"
								+ "&skey="
								+ row['skey_'];break;
							case '服务合同审批': location = "controller/engineering/serviceContract/ewf_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_contract_service"
								+ "&skey="
								+ row['skey_'];break;
							case '租赁合同审批': location = 'controller/contract/rental/ewf_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'];break;
							case '研发合同审批': location = 'controller/rdproject/yxrdproject/ewf_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'] ;break;
		                	case '销售异常关闭审批': location = "controller/projectmanagent/order/ewf_close.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_sale_order"
								+ "&skey="
								+ row['skey_'];break;
							case '服务合同异常关闭审批': location = "controller/engineering/serviceContract/ewf_close.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_contract_service"
								+ "&skey="
								+ row['skey_'];break;
							case '租赁合同异常关闭审批': location = 'controller/contract/rental/ewf_close.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'];break;
		                	case '研发合同异常关闭审批': location = 'controller/rdproject/yxrdproject/ewf_close.php?taskId='
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
							case '销售订单审批': location = "controller/projectmanagent/order/ewf_change_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_sale_order"
								+ "&skey="
								+ row['skey_'];break;
							case '服务合同审批': location = "controller/engineering/serviceContract/ewf_change_index.php?actTo=ewfExam&taskId="
								+ row.task
								+ "&spid="
								+ row.id
								+ "&billId="
								+ row.orderId
								+ "&examCode=oa_contract_service"
								+ "&skey="
								+ row['skey_'];break;
							case '租赁合同审批': location = 'controller/contract/rental/ewf_change_index.php?taskId='
			                	+ row.task
			                	+ '&spid='
			                	+ row.id
			                	+ '&billId='
			                	+ row.orderId
			                	+ '&actTo=ewfExam'
			                	+ "&skey="
			                	+ row['skey_'];break;
							case '研发合同审批': location = 'controller/rdproject/yxrdproject/ewf_change_index.php?taskId='
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
	            display:'合同号',
	            name:'orderCodeOrTempSearch'
	        }
        ],
		sortname:'ExaDT',
		sortorder:'DESC'
    });
});