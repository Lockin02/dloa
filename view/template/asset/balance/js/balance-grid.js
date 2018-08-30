var show_page = function(page) {
	$("#balanceGrid").yxgrid("reload");
};
$(function() {
	$("#balanceGrid").yxgrid({
		model : 'asset_balance_balance',
		action : 'pageJson',
		title : '�ʲ��۾�',
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		// showcheckbox : false,
		param:{"assetId":$("#assetId").val()},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetId',
			display : '�̶��ʲ�id',
			sortable : true,
			hide : true
		},
				// ��ȡ�����ʲ���Ƭ������
				{
					name : 'useOrgName',
					display : 'ʹ�ò���',
					sortable : true,
					hide : true
				}, {
					name : 'assetCode',
					display : '��Ƭ���',
					sortable : true,
					width : 250
				}, {
					name : 'assetName',
					display : '�ʲ�����',
					sortable : true
				}, {
					name : 'localNetValue',
					display : 'Ŀǰ��ֵ',
					sortable : true,
					process : function(v) {
						return moneyFormat2(v);
					}
				},
				// ��ȡ�������������
				{
					name : 'origina',
					display : '�ʲ�ԭֵ',
					sortable : true,
					// �б��ʽ��ǧ��λ
					process : function(v) {
						return moneyFormat2(v);
					}
				},{
					name : 'deprTime',
					display : '�۾�����',
					sortable : true
				}, {
					name : 'initDepr',
					display : '�ڳ��ۼ��۾�',
					sortable : true,
					// �б��ʽ��ǧ��λ
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'depr',
					display : '���ڼ����۾ɶ�',
					sortable : true,
					// �б��ʽ��ǧ��λ
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
//					name : 'deprRate',
//					display : '�����۾���',
//					sortable : true,
//					width : 80
//				}, {
					name : 'deprRemain',
					display : 'ʣ���۾ɶ�',
					sortable : true,
					// �б��ʽ��ǧ��λ
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'deprShould',
					display : '����Ӧ���۾ɶ�',
					sortable : true,
					// �б��ʽ��ǧ��λ
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'salvage',
					display : 'Ԥ�ƾ���ֵ',
					sortable : true
				}, {
					name : 'estimateDay',
					display : 'Ԥ��ʹ���ڼ�',
					sortable : true,
					width : 80
				}, {
					name : 'workLoad',
					display : '������',
					sortable : true,
					hide : true
				}, {
					name : 'period',
					display : '�ڼ�',
					sortable : true,
					hide : true,
					width : 80
				}, {
					name : 'years',
					display : '���',
					sortable : true
				}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '�۾�����',
			name : 'deprTime'
		}],
		toAddConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 600
		},
		buttonsEx : [{
			name : 'Review',
			text : "����",
			icon : 'view',
			action : function() {
				history.back();
			}
		}],
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showThickboxWin('?model=asset_balance_balance&action=init&id='
							+ row.id
							+ "&assetCode="
							+ row.assetCode
							+ "&assetName="
							+ row.assetName
//							+ "&origina="
//							+ row.origina
//							+ "&netValue="
//							+ row.netValue
							+ '&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				//��������
						var date = new Date(); //���ڶ���
						var year = date.getFullYear();
						var month = date.getMonth()+1; //ȡ�µ�ʱ��ȡ���ǵ�ǰ��-1�����ȡ��ǰ��+1�Ϳ�����
				//�۾����ڵ�����
						arr=row.deprTime.split("-");
				//�ж��۾����ڲ��Ǳ���ʱ�����ܱ༭
				if (arr[0] == year && arr[1] == month) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_balance_balance&action=init&id='
							+ row.id
							+ "&assetCode="
							+ row.assetCode
							+ "&assetName="
							+ row.assetName
//							+ "&origina="
//							+ row.origina
//							+ "&netValue="
//							+ row.netValue
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]

	});
});