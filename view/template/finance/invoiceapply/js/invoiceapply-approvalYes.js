/**收票管理列表**/

var show_page=function(page){
   $("#invoiceapply").yxgrid("reload");
};

$(function(){

        $("#invoiceapply").yxgrid({

        	model:'finance_invoiceapply_invoiceapply',
        	action:'pageJsonAuditYes',
        	title:'已审批开票申请',
        	isToolBar:false,
        	showcheckbox : false,
        	isViewAction:false,

			colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '申请单号',
				name : 'applyNo',
				sortable : true,
				width:130
			},{
				display : '业务编号',
				name : 'objCode',
				width:140
			},{
				display : '业务类型',
				name : 'objType',
				sortable : true,
				datacode : 'KPRK'
			},
			{
				display : '客户单位',
				name : 'customerName',
				width:150
			},
			{
				display : '申请人',
				sortable : true,
				name : 'createName',
				width:90
			},
			{
				display : '申请类型',
				name : 'invoiceType',
				sortable : true,
				datacode : 'FPLX'
			},
			{
				display : '申请金额',
				name : 'invoiceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			},
			{
				display : '审批状态',
				sortable : true,
				name : 'ExaStatus',
				width:80
			},
			{
				display : '申请时间',
				sortable : true,
				name : 'applyDate',
				width:90
			}],


					//扩展右键菜单
					menusEx : [
					{
						text : '查看',
						icon : 'view',
						action : function(row,rows,grid){
							showThickboxWin('?model=finance_invoiceapply_invoiceapply&action=init'
							+ '&id=' + row.applyId
							+ '&skey=' + row['skey_1']
							+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000');
						}
					},{
						text : '审批情况',
						icon : 'view',
						action : function(row) {
							showThickboxWin('controller/common/readview.php?itemtype=oa_finance_invoiceapply&pid='
					             +row.applyId
					             + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
						}
					}],
			searchitems:[
			        {
			            display:'开票申请单号',
			            name:'applyNo'
			        }
			        ],
			sortname:'id',
			sortorder:'ASC'
        });
});