var show_page = function(page) {
	$("#payViewGrid").yxgrid("reload");
};
$(function() {
	$("#payViewGrid").yxgrid( {
		model : 'hr_payView_payView',
		title : '�����Ϣ',
		param :{
			"Debtor" : $("#debtor").val(),
			"ProjectNo" : $("#projectNo").val()
		},
		isOpButton : false,
		showcheckbox:false,
		bodyAlign:'center',
		// ����Ϣ
		colModel : [
	        {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true,
				width:'70'
			}, {
				name : 'userName',
				display : '�����',
				sortable : true,
				width:'60'
			}, {
				name : 'Status',
				display : '״̬',
				sortable : true,
				width:'60'
			},{
				name : 'ApplyDT',
				display : '���ʱ��',
				sortable : true,
				width:'120'
			},{
				name : 'Reason',
				display : '���ԭ��',
				sortable : true,
				width:'200'
			},{
				name : 'Amount',
				display : '�����',
				sortable : true,
				width:'100',
				process : function(v,row) {
					if(v){
						return moneyFormat2(v);
					}
				}
			},{
				name : 'PayDT',
				display : '����ʱ��',
				sortable : true,
				width:'120'
			}, {
				name : 'ReceiptDT',
				display : '����ʱ��',
				sortable : true,
				width:'120'
			}, {
				name : 'ProjectNo',
				display : '��Ŀ���',
				sortable : true,
				width:'100'
			}, {
				name : 'XmFlag',
				display : '�������',
				sortable : true,
				width:'80',
				process: function(v) {
				if (v == "0") {
					return '���Ž��';
				} else if (v == "1") {
					return '��Ŀ���';
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