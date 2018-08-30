var show_page = function(page) {
	$("#payViewGrid").yxgrid("reload");
};
$(function() {
	$("#payViewGrid").yxgrid( {
		model : 'hr_payView_payView',
		title : '借款信息',
		param :{
			"Debtor" : $("#debtor").val(),
			"ProjectNo" : $("#projectNo").val()
		},
		isOpButton : false,
		showcheckbox:false,
		bodyAlign:'center',
		// 列信息
		colModel : [
	        {
				name : 'userNo',
				display : '员工编号',
				sortable : true,
				width:'70'
			}, {
				name : 'userName',
				display : '借款人',
				sortable : true,
				width:'60'
			}, {
				name : 'Status',
				display : '状态',
				sortable : true,
				width:'60'
			},{
				name : 'ApplyDT',
				display : '借款时间',
				sortable : true,
				width:'120'
			},{
				name : 'Reason',
				display : '借款原因',
				sortable : true,
				width:'200'
			},{
				name : 'Amount',
				display : '借款金额',
				sortable : true,
				width:'100',
				process : function(v,row) {
					if(v){
						return moneyFormat2(v);
					}
				}
			},{
				name : 'PayDT',
				display : '付款时间',
				sortable : true,
				width:'120'
			}, {
				name : 'ReceiptDT',
				display : '还款时间',
				sortable : true,
				width:'120'
			}, {
				name : 'ProjectNo',
				display : '项目编号',
				sortable : true,
				width:'100'
			}, {
				name : 'XmFlag',
				display : '借款类型',
				sortable : true,
				width:'80',
				process: function(v) {
				if (v == "0") {
					return '部门借款';
				} else if (v == "1") {
					return '项目借款';
				}else {
					return '';
						}
					}
					}],
			isViewAction:false,
			isAddAction : false,
			isEditAction : false,
			isDelAction : false
		});
});