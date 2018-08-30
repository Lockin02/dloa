/**收票管理列表**/
var show_page = function(){
   $("#invoiceapplyGrid").yxgrid("reload");
};

$(function(){
    $("#invoiceapplyGrid").yxgrid({
    	model:'finance_invoiceapply_invoiceapply',
		param : {'objId' : $('#objId').val(),'objType' : $('#objType').val()},
		action : 'objPageJson',
    	title:'所有的开票申请',
    	isAddAction:false,
    	isViewAction:false,
    	isEditAction:false,
    	isDelAction:false,
    	showcheckbox: false,
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '申请单号',
				name : 'applyNo',
				sortable : true,
				width:150,
				process : function(v,row){
					return "<a href='#' onclick='showModalWin(\"?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,"+row.id+")'>" + v + "</a>";
				}
			},
			{
				display : '客户单位',
				name : 'customerName',
				width:200
			},
			{
				display : '申请人',
				sortable : true,
				name : 'createName',
				width:90
			},
            {
                display : '申请时间',
                sortable : true,
                name : 'applyDate',
                width:90
            },
			{
				display : '申请类型',
				name : 'invoiceTypeName',
				sortable : true,
				width:130
			},
			{
				display : '申请金额',
				name : 'invoiceMoney',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width:90
			},
			{
				display : '已开金额',
				name : 'payedAmount',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width:90
			},
			{
				display : '审批状态',
				sortable : true,
				name : 'ExaStatus',
				width:80
			},
            {
                display : '审批日期',
                sortable : true,
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
                showModalWin('?model=finance_invoiceapply_invoiceapply&action=init&perm=view'
                    + '&id=' + row.id + '&skey=' + row['skey_']
                    + '&perm=view',1,row.id);
			}
		},{
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
                return row.ExaStatus != '待提交' ? true : false;
			},
			action : function(row) {
				showThickboxWin('controller/common/readview.php?itemtype=oa_finance_invoiceapply&pid='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		},{
			text : '打印',
			icon : 'print',
			action : function(row) {
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=printInvoiceApply'
				    + '&id=' + row.id + '&skey=' + row['skey_'],1,row.id);
			}
		}],
        comboEx:[{
            text: "审批状态",
            key: 'ExaStatus',
            type : 'workFlow'
        },{
            text: "开票状态",
            key: 'moneyStatus',
            data : [{
                text : '完成',
                value : 'done'
            }, {
                text : '未完成',
                value : 'undo'
            }]
        }],
		searchitems:[
	        {
	            display:'客户单位',
	            name:'customerNameSearch'
	        },
	        {
	            display:'业务编号',
	            name:'objCodeSearch'
	        },
	        {
	            display:'申请人',
	            name:'createName'
	        },
	        {
	            display:'开票申请单号',
	            name:'applyNo'
	        }
        ]
    });
});