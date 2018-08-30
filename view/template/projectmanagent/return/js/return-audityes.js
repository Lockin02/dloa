/**收票管理列表**/

var show_page=function(page){
   $("#returnGrid").yxgrid("reload");
};

$(function(){
        $("#returnGrid").yxgrid({

        	model:'projectmanagent_return_return',
        	action:'pageJsonAuditYes',
        	title:'已审批',
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
			display : '退货单编号',
			sortable : true
		}, {
			name : 'orderCode',
			display : '源单号',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '合同负责人',
			sortable : true
		},{
			name : 'saleWay',
			display : '销售方式',
			sortable : true
		}, {
			name : 'storage',
			display : '收货仓库',
			sortable : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true
		}, {
			name : 'returnCause',
			display : '退货原因',
			sortable : true
		}],


					//扩展右键菜单
					menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=projectmanagent_return_return&action=init&perm=view&id="+ row.returnId + "&skey="+row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					}
					],
			searchitems:[
			        {
			            display:'退货单编号',
			            name:'returnCode'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});