/**收票管理列表**/

var show_page=function(page){
   $("#orderChangeYesGrid").yxgrid("reload");
};

$(function(){
        $("#orderChangeYesGrid").yxgrid({

        	model:'projectmanagent_order_order',
        	action:'changeAuditYes',
        	title:'已审批的变更销售合同',
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
  					display : '是否签约',
  					sortable : true
              },{
    				name : 'orderstate',
  					display : '纸质合同状态',
  					sortable : true
              },{
    				name : 'parentOrder',
  					display : '父合同名称',
  					sortable : true,
  					hide : true
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
  					sortable : true
              },{
					name : 'prinvipalName',
  					display : '合同负责人',
  					sortable : true
              },{
					name : 'deliveryDate',
  					display : '交货日期',
  					sortable : true
              },{
					name : 'state',
  					display : '合同状态',
  					sortable : true,
  					process : function(v){
  						if( v == '0'){
  							return "未提交";
  						}else if(v == '1'){
  							return "审批中";
  						}else if(v == '2'){
  							return "执行中";
  						}else if(v == '3'){
  							return "已关闭";
  						}else if(v == '4'){
  						    return "已完成";
  						}
  					},
  					width : 90
              },{
					name : 'ExaStatus',
  					display : '审批状态',
  					sortable : true,
  					width : 90
              },{
						display : '审批时间',
						name : 'ExaDT',
						width:80
					}
          ],


					//扩展右键菜单
					menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row){
//							showOpenWin("?model=projectmanagent_order_order&action=toReadTab&id="+ row.orderId+ "&perm=view");
							showOpenWin("?model=projectmanagent_order_order&action=toReadTab&perm=view&id=" + row.orderId + "&skey="+row['skey_'])
						}
					}
					],
			searchitems:[
			        {
			            display:'合同名称',
			            name:'orderName'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});