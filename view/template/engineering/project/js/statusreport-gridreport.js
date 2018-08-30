var show_page = function(page) {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'pageJsonReport',
		title : '��Ŀ״̬����',
		isDelAction : false,
		isAddAction : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectId',
				display : '��Ŀid',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				width : 140
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true
			}, {
				name : 'officeName',
				display : '���´�',
				sortable : true
			}, {
				name : 'createName',
				display : '�ύ��',
				sortable : true
			}, {
				name : 'handupDate',
				display : '�ύ����',
				sortable : true,
				width : 80
			}, {
				name : 'budgetAll',
				display : '��Ԥ��',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'budgetField',
				display : '�ֳ�����Ԥ��',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeField',
				display : '�ֳ�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeAll',
				display : '�ܷ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeAllProcess',
				display : '���ý���',
				sortable : true,
				process : function(v){
					return v + ' %';
				},
				width : 80
			}, {
				name : 'projectProcess',
				display : '���̽���',
				sortable : true,
				process : function(v){
					return v + ' %';
				},
				width : 80
			}, {
				name : 'feeFieldProcess',
				display : '�ֳ����ý���',
				sortable : true,
				process : function(v){
					return v + ' %';
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : 'Ԥ�ƽ�������',
				sortable : true,
				process : function(v,row){
					if(v=="0000-00-00"){
						return "";
					}else{
						return  v;
					}
				},
				width : 80
			}, {
				name : 'actEndDate',
				display : 'ʵ�ʽ�������',
				sortable : true,
				process : function(v,row){
					if(v=="0000-00-00"){
						return "";
					}else{
						return  v;
					}
				},
				width : 80
			}, {
				name : 'changeTimes',
				display : '�������',
				sortable : true,
				hide : true,
				width : 80
			}, {
				name : 'status',
				display : '����״̬',
				sortable : true,
				datacode : 'XMZTBG',
				width : 80
			}, {
				name : 'confirmName',
				display : 'ȷ����',
				sortable : true
			}, {
				name : 'confirmDate',
				display : 'ȷ������',
				sortable : true,
				width : 80
			}, {
				name : 'milestoneName',
				display : '�����̱�',
				sortable : true,
				width : 80
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}
		],
		toViewConfig : {
			formWidth : 1000,
			formHeight : 500
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == "XMZTBG01") {
					return true;
				}
				return false;
			},
			formWidth : 1000,
			formHeight : 500
		},
		// ��չ�Ҽ��˵�
		menusEx : [
			{
				text : 'ȷ��',
				icon : 'edit',
				showMenuFn : function(row) {
					if (row.status == "XMZTBG02") {
						return true;
					}
					return false;
				},
				action : function(row, rows, grid) {
					showThickboxWin("?model=engineering_project_statusreport&action=toConfirmReport&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
				}
			},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn:function(row){
					if (row.status == "XMZTBG01" || row.status == "") {
						return true;
					}
					return false;
				},
				action : function(rowData, rows, rowIds, g) {
					g.options.toDelConfig.toDelFn(g.options,g);
				}
			}
		],
		searchitems : [{
			display : '��Ŀ���',
			name : 'projectCode'
		},{
			display : '��Ŀ����',
			name : 'projectName'
		},{
			display : '�ύ��',
			name : 'createName'
		},{
			display : '�ύ����',
			name : 'handupDateSearch'
		}],
		// ����״̬���ݹ���
		comboEx : [{
			text : '����״̬',
			key: 'status',
			datacode : 'XMZTBG',
			value : 'XMZTBG02'
		}]
	});
});