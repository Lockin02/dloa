var show_page = function(page) {
	$("#receiveItemGrid").yxgrid("reload");
};
$(function() {
	$("#receiveItemGrid").yxgrid({
		model : 'asset_purchase_receive_receiveItem',
		title : '������Ƭ��Ϣ',
		showcheckbox : false,
		isToolBar : false,
		param : {
			'receiveId' : $("#receiveId").val()
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'requireinId',
			name : 'requireinId',
			sortable : true,
			hide : true
		}, {
			display : 'requireinCode',
			name : 'requireinCode',
			sortable : true,
			hide : true
		}, {
			name : 'isCardFlag',
			display : '',
			sortable : false,
			width : '20',
			align : 'center',
			process : function(v, row) {
				if (row.cardStatus == '2') {
					return '<img src="images/icon/cicle_green.png"/>';
				}else{
					return '<img src="images/icon/cicle_yellow.png"/>';
				}
			}
		}, {
			display : '����id',
			name : 'receiveId',
			sortable : true,
			hide : true
		}, {
			name : 'assetId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'assetName',
			display : '��������',
			width : '150',
			sortable : true
		}, {
			name : 'assetCode',
			display : '���ϱ��',
			width : '150',
			sortable : true
		}, {
			name : 'spec',
			display : '���',
			sortable : true
		}, {
			name : 'checkAmount',
			display : '����',
			width : '80',
			sortable : true
		}, {
			name : 'cardNum',
			display : '���ɿ�Ƭ����',
			width : '80',
			sortable : true
		}, {
			name : 'cardStatus',
			display : '�Ƿ������ɿ�Ƭ',
			sortable : true,
			process : function(v, row) {
				if (v == '0') 
					return "δ����";
				if (v == '1') 
					return "��������";
				if (v == '2') 
					return "������";
			}
		}],
		comboEx : [{
			text : '���ɿ�Ƭ״̬',
			key : 'cardStatus',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '��������',
				value : '1'
			}, {
				text : '������',
				value : '2'
			}]
		}],
		buttonsEx : [{
			name : 'Review',
			text : "����",
			icon : 'view',
			action : function() {
				location.href="?model=asset_purchase_receive_receive";
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�����ʲ���Ƭ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.cardStatus != '2') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//�����ɵĿ�Ƭ���� = ���� - �����ɿ�Ƭ����
					var num = row.checkAmount - row.cardNum;
					showOpenWin("?model=asset_assetcard_assetTemp&action=toadd&assetName="
							+ row.assetName
							+ "&productId="
							+ row.assetId
							+ "&productCode="
							+ row.assetCode
							+ "&productName="
							+ row.assetName
							+ "&spec="
							+ row.spec
							+ "&brand="
							+ row.brand
							+ "&num="
							+ num
							+ "&requireinId="
							+ row.requireinId
							+ "&requireinItemId="
							+ row.requireinItemId
							+ "&receiveItemId="
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]
	});
});