var show_page = function(page) {
	$("#requireinItemGrid").yxgrid("reload");
};
$(function() {
	$("#requireinItemGrid").yxgrid({
		model : 'asset_require_requireinitem',
		title : '������Ƭ��Ϣ',
		showcheckbox : false,
		isToolBar : false,
		param : {
			'mainId' : $("#requireId").val()
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'isCardFlag',
			display : '',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.cardStatus == 2) {
					return "<img src='images/icon/icon071.gif' />";
				}else{
					return "";
				}
			}
		}, {
			display : '����id',
			name : 'mainId',
			sortable : true,
			hide : true
		}, {
			name : 'productId',
			display : '����id',
			sortable : true,
			hide : true
		}, {
			name : 'productCode',
			display : '���ϱ��',
			width : '150',
			sortable : true
		}, {
			name : 'productName',
			display : '��������',
			width : '150',
			sortable : true
		}, {
			name : 'spec',
			display : '���',
			sortable : true
		}, {
			name : 'number',
			display : '����',
			width : '80',
			sortable : true
		}, {
			name : 'executedNum',
			display : '��������',
			width : '80',
			sortable : true
		}, {
			name : 'receiveNum',
			display : '��������',
			width : '80',
			sortable : true
		}, {
			name : 'cardNum',
			display : '���ɿ�Ƭ����',
			width : '80',
			sortable : true
		}, {
			name : 'cardStatus',
			display : '���ɿ�Ƭ״̬',
			sortable : true,
			process : function(v, row) {
				if (v == '0')
					return "������";
				if (v == '1')
					return "��ǩ��";
				if (v == '2')
					return "������";
				if (v == '3')
					return "��������";
				if (v == '4')
					return "������";
			}
		}],
		comboEx : [{
			text : '���ɿ�Ƭ״̬',
			key : 'cardStatus',
			data : [{
				text : '������',
				value : '0'
			}, {
				text : '��ǩ��',
				value : '1'
			}, {
				text : '������',
				value : '2'
			}, {
				text : '��������',
				value : '3'
			}, {
				text : '������',
				value : '4'
			}]
		}],
		buttonsEx : [{
			name : 'Review',
			text : "����",
			icon : 'view',
			action : function() {
				location.href="?model=asset_require_requirein";
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�����ʲ���Ƭ',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.cardStatus == 1) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//�����ɵĿ�Ƭ����=��������-�����ɿ�Ƭ����
					var num = row.executedNum-row.cardNum;
					showOpenWin("?model=asset_assetcard_assetTemp&action=toadd&assetName="
							+ row.productName
							+ "&productId="
							+ row.productId
							+ "&productCode="
							+ row.productCode
							+ "&productName="
							+ row.productName
							+ "&spec="
							+ row.spec
							+ "&num="
							+ num
							+ "&requireinItemId="
							+ row.id
							+ "&requireinId="
							+ row.mainId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]
	});
});