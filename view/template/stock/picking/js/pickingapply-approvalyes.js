/**收票管理列表**/

var show_page=function(page){
   $("#pickingapply").yxgrid("reload");
};

$(function(){
        $("#pickingapply").yxgrid({

        	model:'stock_picking_pickingapply',
        	action:'pageJsonAuditYes',
        	title:'已审批领料出库',
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
						display : '领料申请单号',
						name : 'pickingCode',
						sortable : true,
						width:130
					},{
						display : '领料类型',
						name : 'pickingType',
						width:130
					},
					{
						display : '审批单号',
						name : 'task',
						width:80
					},
					{
						display : '发料仓库',
						name : 'stockName'
					},
					{
						display : '领料人',
						name : 'pickName'
					},
					{
						display : '发料人',
						name : 'sendName'
					},
					{
						display : '申请人',
						name : 'createName'
					},
					{
						display : '审批状态',
						name : 'ExaStatus',
						width:80
					},
					{
						display : '审批时间',
						name : 'ExaDT'
					}],


					//扩展右键菜单
					menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin("?model=stock_picking_pickingapply&action=init&perm=view&id="+ row.applyId
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800"
							);
						}
					},
						{
							text : '审批情况',
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
			            display:'开票申请单号',
			            name:'applyNo'
			        }
			        ],
			sortname:'id',
			sortorder:'DESC'
        });
});