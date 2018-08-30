var show_page = function(page) {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'pageJsonReport',
		title : '��Ŀ�ܱ��б�',
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
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true
			}, {
				name : 'milestoneId',
				display : '�����̱�id',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '�ύ��',
				sortable : true
			}, {
				name : 'handupDate',
				display : '�ύ����',
				sortable : true
			}, {
				name : 'budgetAll',
				display : '��Ԥ��',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'budgetField',
				display : '�ֳ�����Ԥ��',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'feeField',
				display : '�ֳ�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'feeAll',
				display : '�ܷ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'feeAllProcess',
				display : '���ý���',
				sortable : true,
				process : function(v){
					return v + ' %';
				}
			}, {
				name : 'projectProcess',
				display : '���̽���',
				sortable : true,
				process : function(v){
					return v + ' %';
				}
			}, {
				name : 'feeFieldProcess',
				display : '�ֳ����ý���',
				sortable : true,
				process : function(v){
					return v + ' %';
				}
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
				}
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
				}
			}, {
				name : 'changeTimes',
				display : '�������',
				sortable : true,
				hide : true
			}, {
				name : 'status',
				display : '����״̬',
				sortable : true,
				datacode : 'XMZTBG'
			}, {
				name : 'confirmName',
				display : 'ȷ����',
				sortable : true
			}, {
				name : 'confirmDate',
				display : 'ȷ������',
				sortable : true
			}, {
				name : 'milestoneName',
				display : '�����̱�',
				sortable : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}
		],
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=engineering_project_statusreport&action=toView&id=" + rowData[p.keyField] );
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == "XMZTBG01") {
					return true;
				}
				return false;
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=engineering_project_statusreport&action=toEdit&id=" + rowData[p.keyField] );
			}
		},
		// ��չ�Ҽ��˵�
		menusEx : [
			{
//				text : 'ȷ��',
//				icon : 'edit',
//				showMenuFn : function(row) {
//					if (row.status == "XMZTBG02") {
//						return true;
//					}
//					return false;
//				},
//				action : function(row, rows, grid) {
//					showThickboxWin("?model=engineering_project_statusreport&action=toConfirmReport&id="
//								+ row.id
//								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
//				}
//			},{
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
			value : 'XMZTBG03'
		}]
	});
});