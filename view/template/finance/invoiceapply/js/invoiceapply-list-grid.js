var show_page=function(){
   $("#invoiceapplyGrid").yxgrid("reload");
};

$(function(){
    $("#invoiceapplyGrid").yxgrid({
    	model:'finance_invoiceapply_invoiceapply',
    	title:'所有的开票申请',
    	isViewAction:false,
    	isEditAction:false,
    	isDelAction:false,
    	showcheckbox: false,
		isOpButton : false,
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				display : '申请单号',
				name : 'applyNo',
				sortable : true,
				width:135,
				process : function(v,row){
					if(row.isOffSite == '1'){
						return "<span class='green' title='异地开票申请'>" + v + "</span>";
					}else{
						return v;
					}
				}
			},{
				display : '源单类型',
                name : 'objTypeName',
				sortable : true,
                width:70
			},{
				display : '源单编号',
				name : 'objCode',
				width:140
			},
			{
				display : '客户单位',
				name : 'customerName',
				width:150
			},
			{
				display : '归属公司',
				name : 'businessBelongName',
				width:70
			},
			{
				display : '申请人',
				sortable : true,
				name : 'createName',
				width:80
			},
			{
				display : '申请类型',
				name : 'invoiceTypeName',
				sortable : true,
				width:80
			},
            {
                display : '币别',
                name : 'currency',
                sortable : true,
                width:60,
                process: function (v) {
                    return v == '人民币' ? v : '<span class="red">'+ v +'</span>';
                }
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
				process : function(v,row){
					if(v*1 > row.invoiceMoney*1){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width:90
			},
			{
				display : '合同金额',
				name : 'contAmount',
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
                width:60
			},
			{
				display : '申请日期',
				sortable : true,
				name : 'applyDate',
                width:70
			},
			{
				display : '业务编号',
				sortable : true,
				name : 'rObjCode',
				width:120
			},
			{
				display : '租赁开始日期',
				name : 'rentBeginDate',
				width:80
			},
			{
				display : '租赁结束日期',
				name : 'rentEndDate',
				width:80
			},
			{
				display : '租赁天数',
				name : 'rentDays',
				width:60
			}
		],
		toAddConfig : {
			toAddFn : function() {
				showModalWin("?model=finance_invoiceapply_invoiceapply&action=toAddIndep");
			}
		},
		comboEx:
		[
			{
				text: "审批状态",
				key: 'ExaStatus',
				type : 'workFlow',
				value : '完成'
			},{
				text: "开票状态",
				key: 'moneyStatus',
				data : [{
						text : '完成',
						value : 'done'
					}, {
						text : '未完成',
						value : 'undo'
					}
				],
				value : 'undo'
			}
		],

		//扩展右键菜单
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action : function(row){
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
				+ '&id=' + row.id + '&skey=' + row['skey_']
				+ '&perm=view');
			}
		},{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '待提交'){
					return true;
				}
				return false;
			},
			action : function(row){
				showModalWin('?model=finance_invoiceapply_invoiceapply&action=init'
				+ '&id=' + row.id + '&skey=' + row['skey_'] );
			}
		},{
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '待提交'){
					return true;
				}
				return false;
			},
			action : function(row){
				if(row.isOffSite == '1'){
					showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
		            	+ row.id + "&formName=异地开票申请"
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
				}else{
					showThickboxWin('controller/finance/invoiceapply/ewf_index.php?actTo=ewfSelect&billId='
		            	+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600');
				}
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row){
				if(row.ExaStatus == '待提交' || row.ExaStatus == '完成'){
					if(row.payedAmount * 1 == 0){
						return true;
					}
				}
				return false;
			},
			action : function(row) {
				if(row.payedAmount * 1 != 0){
					alert('已经开票，不能进行删除操作');
					return false;
				}
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_invoiceapply_invoiceapply&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#invoiceapplyGrid").yxgrid("reload");
							}else{
								alert("删除失败! ");
							}
						}
					});
				}
			}
		},{
			text : '发票登记',
			icon : 'add',
			showMenuFn : function(row){
				if(row.ExaStatus == '完成'){
					return true;
				}
				return false;
			},
			action : function(row){
				location="?model=finance_invoiceapply_invoiceapply&action=toregister&id="
				+ row.id + '&skey=' + row['skey_'] ;
			}

		},{
			text : '审批情况',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus != '待提交') {
					return true;
				}
				return false;
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
				+ '&id=' + row.id + '&skey=' + row['skey_'] );
			}
		}],
        buttonsEx : [
	        {
				name : 'close',
				text : "开票额度预览",
				icon : 'view',
				action : function() {
					showOpenWin('?model=finance_invoice_invoice&action=toInvoicePerview');
				}
	        }
        ],
		searchitems:[
	        {
	            display:'客户单位',
	            name:'customerNameSearch'
	        },
	        {
	            display:'源单编号',
	            name:'objCodeSearch'
	        },
	        {
	            display:'业务编号',
	            name:'rObjCodeSearch'
	        },
	        {
	            display:'申请人',
	            name:'createName'
	        },
	        {
	            display:'开票申请单号',
	            name:'applyNo'
	        },
	        {
	            display:'申请日期',
	            name:'applyDateSearch'
	        }
        ],
		sortname : 'c.updateTime'
    });
});