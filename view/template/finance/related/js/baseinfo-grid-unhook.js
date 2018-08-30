var show_page = function(page) {
	$("#baseinfoGrid").yxgrid("reload");
};
$(function() {
	$("#baseinfoGrid").yxgrid({
		model: 'finance_related_baseinfo',
		param : {"ids" : $('#ids').val(),"hookMainId" : $("#hookMainId").val()},
		action : 'pageJsonRelated',
		title: '������־',
		showcheckbox: false,
		isToolBar : true,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		//����Ϣ
		colModel: [{
			display: '�������',
			name: 'relatedId',
			sortable: true,
			width : 60
		},{
			display: '��Ŀid',
			name: 'id',
			hide :true
		},{
			display: 'hookMainId',
			name: 'hookMainId',
			hide :true
		},
		{
			name: 'years',
			display: '�������',
			sortable: true,
			width : 60
		},
		{
			name: 'createTime',
			display: '����ʱ��',
			sortable: true,
			process : function(v){
				return formatDate(v);
			},
			width : 80
		},
		{
			name: 'createName',
			display: '������',
			sortable: true
		},
		{
			name: 'supplierName',
			display: '��Ӧ��',
			sortable: true,
			width : 150
		},{
			name: 'hookObj',
			display: ' ��������',
			sortable: true,
			process : function(v){
				if(v == 'invpurchase'){
					return '�ɹ���Ʊ';
				}else if(v == 'invcost'){
					return '���÷�Ʊ';
				}else{
					return '�⹺��ⵥ';
				}
			},
			width : 80
		},{
			name: 'hookObjCode',
			display: '�����',
			sortable: true,
			width : 160
		},{
			name: 'productName',
			display: '��Ʒ����',
			sortable: true
		},{
			name: 'productNo',
			display: '���ϴ���',
			sortable: true
		},{
			name: 'number',
			display: '��������',
			sortable: true,
			width : 60
		},{
			name: 'amount',
			display: '�������',
			sortable: true,
			process : function(v){
				return moneyFormat2(v);
			}
		}],
		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
				if(row.hookObj == 'invpurchase'){
					showOpenWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.hookMainId
						+ "&skey=" + row.skey_ ,1);
				}else if(row.hookObj == 'invcost'){
					showOpenWin('?model=finance_invcost_invcost&action=init&perm=view&id=' + row.hookMainId
						+ "&skey=" + row.skey_ ,1);
				}else{
					showOpenWin('?model=stock_instock_stockin&action=toView&docType=RKPURCHASE&id=' + row.hookMainId
						+ "&skey=" + row.skey_ ,1);
				}
			}
		},{
			text : '�鿴�������',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=finance_related_baseinfo&action=init&perm=view&id=' + row.relatedId +
					"&placeValuesBefore&TB_iframe=true&modal=false&height=" +
					550 + "&width=" + 800);
			}
		},{
			text : '������',
			icon : 'delete',
			action : function(row) {
				if (window.confirm(("ȷ��������?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_related_baseinfo&action=unHook",
							data : {
								id : row.relatedId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('�������ɹ���');
									self.window.close();
									self.opener.show_page(1);
								}else{
									alert("������ʧ��! ");
								}
							}
						});
					}
			}
		}],buttonsEx : [{
			separator : true
		},{
			name : 'close',
			text : "�ر�",
			icon : 'edit',
			action : function() {
				self.window.close();
				self.opener.show_page(1);
			}
		}]
	});
});