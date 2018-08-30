var show_page = function(page) {
	$("#sales").yxgrid("reload");
};
$(function() {
	$("#sales").yxgrid({
        param : {"signStatusIn":"3,4","exaStatus":"���"},
		model : 'contract_sales_sales',
		isViewAction : false,
		isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        showcheckbox : false,
		sortorder : "DESC",
		title : '��ͬǩ���б�',
		comboEx: [{
			text: "ǩԼ״̬",
			key: 'signStatus',
			data : [{
				text : '���ύֽ�ʺ�ͬ',
				value : '3'
				},{
				text : '������ǩ��',
				value : '4'
				}
			]
		}],
		menusEx : [
		{
			text : '��ͬ��Ϣ',
			icon : 'view',
			action: function(row){
                showOpenWin('?model=contract_sales_sales&action=infoTab&id='
						+ row.id);
			}
		},{
			text : '��ͬǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.signStatus == '3' ) {
					return true;
				}
				return false;
			},
			action: function(row){
				if(confirm('ȷ��ǩ�գ�')){
					$.ajax({
						type : "POST",
						url : "?model=contract_sales_sales&action=sign",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ǩ�ճɹ���');
								$("#sales").yxgrid("reload");
							}else{
								alert('ǩ��ʧ��!');
							}
						}
					});
				}
			}
		}],
		// ��
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				display : '������ͬ��',
				name : 'formalNo',
				sortable : true,
				width : 150,
				process : function(v,row){
					if(v != ""){
						return v;
					}else{
						return "<font color='blue'>"+ row.temporaryNo+ "</font>";
					}
				}
//			}, {
//				display : 'ϵͳ��ͬ��',
//				name : 'contNumber',
//				sortable : true,
//				width : 150
			}, {
				display : '��ͬ����',
				name : 'contName',
				sortable : true,
				width : 160
			}, {
				display : '�ͻ�����',
				name : 'customerName',
				sortable : true,
				width : 150
			}, {
				display : '�ͻ���ͬ��',
				name : 'customerContNum',
				sortable : true
			}, {
				display : '�ͻ�����',
				name : 'customerType',
				datacode : 'KHLX',
				sortable : true,
				width : 100
			}, {
				display : '�ͻ�����ʡ��',
				name : 'province',
				sortable : true
			}, {
				display : '����������',
				name : 'principalName',
				sortable : true
			}, {
				display : 'ִ��������',
				name : 'executorName',
				sortable : true
			}, {
				display : 'ǩԼ״̬',
				name : 'signStatus',
				sortable : true,
				process : function(v){
					if (v== 3){
						return "���ύֽ�ʺ�ͬ";
					} else if(v== 4){
						return "������ǩ��";
					}
				}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'contName'
		}]
	});
});