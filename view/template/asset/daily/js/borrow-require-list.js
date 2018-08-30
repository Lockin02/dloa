// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#borrowGrid").yxsubgrid('reload');
};

$(function() {
	$("#borrowGrid").yxsubgrid({
		model : 'asset_daily_borrow',
		title : '�̶��ʲ�������Ϣ',
		param : {'requireId':$("#requireId").val()},
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���õ����',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '������',
			name : 'requireCode',
			sortable : true,
			width : 120
		}, {
			display : '����������id',
			name : 'chargeManId',
			sortable : true,
			hide : true
		}, {
			display : '����������',
			name : 'chargeMan',
			sortable : true

		}, {
			display : '���ò���id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '���ò���',
			name : 'deptName',
			sortable : true
		}, {
			display : '��������',
			name : 'borrowDate',
			sortable : true
		}, {
			display : 'Ԥ�ƹ黹����',
			name : 'predictDate',
			sortable : true
		}, {
			display : '������id',
			name : 'reposeManId',
			sortable : true,
			hide : true
		}, {
			display : '������',
			name : 'reposeMan',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			hide : true
		}, {
			name : 'docStatus',
			display : '����״̬',
			process : function(v){
				if(v=='BFGH'){
					return '���ֹ黹';
				}else if(v=='YGH'){
					return '�ѹ黹';
				}else{
					return 'δ�黹';
				}
			},
			sortable : true
		}, {
			name : 'isSign',
			display : 'ǩ��״̬',
			process : function(v){
				if(v=='0'){
					return 'δǩ��';
				}else if(v=='1'){
					return '��ǩ��';
				}else{
					return v;
				}
			},
			sortable : true
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_daily_borrowitem&action=pageJson',
			param : [{
				paramId : 'borrowId',
				colId : 'id'
			}],
			colModel : [{
				display : '��Ƭ���',
				name : 'assetCode',
				width : 160
			}, {
				display : '�ʲ�����',
				name : 'assetName',
				width : 150
			}, {
				display : '��������',
				name : 'buyDate',
				type : 'date',
				width : 80
			}, {
				display : '����ͺ�',
				name : 'spec',
				tclass : 'txtshort'
			}, {
				display : 'Ԥ��ʹ���ڼ���',
				name : 'estimateDay',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : '�Ѿ�ʹ���ڼ���',
				name : 'alreadyDay',
				tclass : 'txtshort',
				readonly : true
			}, {
				display : 'ʣ��ʹ���ڼ���',
				name : 'residueYears',
				tclass : 'txtshort'
			}, {
				display : '��ע',
				name : 'remark',
				tclass : 'txt',
				width : 150
			}]
		},

		isDelAction : false,
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		toAddConfig : {
			formWidth : 900,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 400
		},
		menusEx : [{
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_borrow&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'edit',
			text : 'ǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '0' && (row.reposeManId == $('#userId').val()||row.chargeManId== $('#userId').val())) {
					return true;
				} else
					return false;
			},
			action : function(row) {
				if (confirm('ȷ���Ƿ�Ҫǩ�գ�')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_daily_borrow&action=toSign',
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 1) {
								alert("ǩ�ճɹ�");
								show_page();
							}else{
								alert("ǩ��ʧ��")
							}
							return false;
						}
					});
				}
			}
		}, {
			name : 'edit',
			text : '�黹�ʲ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '1' && row.docStatus != 'YGH'
						&& row.reposeManId == $('#userId').val()) {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_return&action=toReturnBorrow&borrowNo="
							+ row.billNo
							+ "&borrowId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		// ��������
		searchitems : [{
			display : '���õ����',
			name : 'billNo'
		}, {
			display : '���ÿͻ�',
			name : 'borrowCustome'
		}, {
			display : '��������',
			name : 'borrowDate'
		}, {
			display : '���ò���',
			name : 'deptName'
		}, {
			display : '���ÿͻ�',
			name : 'borrowCustome'
		}],
//		comboEx : [{
//			text : '����״̬',
//			key : 'ExaStatus',
//			data : [{
//				text : '��������',
//				value : '��������'
//			}, {
//				text : '���ύ',
//				value : '���ύ'
//			}, {
//				text : '���',
//				value : '���'
//			}, {
//				text : '���',
//				value : '���'
//			}]
//		}],
           // Ĭ�������ֶ���
			sortname : "id",
           // Ĭ������˳��
			sortorder : "DESC"

	});

});