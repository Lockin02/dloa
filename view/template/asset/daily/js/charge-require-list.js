/**
 * �ʲ�������Ϣ�б�
 *
 * @linzx
 */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_daily_charge',
		param : {
			'requireId' : $("#requireId").val()
		},
		title : '�ʲ�����',
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���õ����',
			name : 'billNo',
			sortable : true,
			width : 130
		}, {
			display : '������',
			name : 'requireCode',
			sortable : true,
			width : 130
		}, {
			display : '��������',
			name : 'chargeDate',
			sortable : true
		}, {
			display : '���ò���id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '���ò�������',
			name : 'deptName',
			sortable : true
		}, {
			display : '������Id',
			name : 'chargeManId',
			sortable : true,
			hide : true
		}, {
			display : '������',
			name : 'chargeMan',
			sortable : true
		}, {
			name : 'docStatus',
			display : '����״̬',
			process : function(v) {
				if (v == 'BFGH') {
					return '���ֹ黹';
				} else if (v == 'YGH') {
					return '�ѹ黹';
				} else {
					return 'δ�黹';
				}
			},
			sortable : true
		}, {
			display : '������',
			name : 'createName',
			sortable : true
		}, {
			name : 'isSign',
			display : 'ǩ��״̬',
			process : function(v) {
				if (v == '0') {
					return 'δǩ��';
				} else if (v == '1') {
					return '��ǩ��';
				} else {
					return v;
				}
			},
			sortable : true
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_daily_chargeitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [
					// {
					// display:'���',
					// name : 'sequence'
					// },
					{
						display : '��Ƭ���',
						name : 'assetCode',
						width : 160
					}, {
						display : '�ʲ�����',
						name : 'assetName',
						width : 160
					}, {
						display : '����ͺ�',
						name : 'spec',
						tclass : 'txtshort',
						readonly : true
					}, {
						display : '��������',
						name : 'buyDate',
						// type : 'date',
						tclass : 'txtshort',
						readonly : true
					}, {
						// display : 'Ԥ��ʹ���ڼ���',
						// name : 'estimateDay',
						// tclass : 'txtshort',
						// readonly : true
						// }, {
						// display : '�Ѿ�ʹ���ڼ���',
						// name : 'alreadyDay',
						// tclass : 'txtshort',
						// readonly : true
						// }, {
						display : 'ʣ��ʹ���ڼ���',// ���ڿ�Ƭ��Ԥ��ʹ���ڼ�����ȥ��ʹ���ڼ���
						name : 'residueYears',
						tclass : 'txtshort',
						readonly : true
					}, {
						display : '��ע',
						name : 'remark',
						tclass : 'txt',
						readonly : true,
						width : 160
					}]
		},
		toAddConfig : {
			formWidth : 900,
			formHeight : 400
		},
		toEditConfig : {
			formWidth : 900,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.isSign == '0') {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 400
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_charge&action=init&id="
							+ row.id
							+ '&perm=view'
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'edit',
			text : '�黹�ʲ�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == '1' && row.docStatus != 'YGH'
						&& row.chargeManId == $('#userId').val()) {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_return&action=toReturnCharge&borrowNo="
							+ row.billNo
							+ "&borrowId="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : 'ǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isSign == "0" && row.chargeManId == $('#userId').val()) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				var chargeManId = row.chargeManId;
				//������Ϊ�麣�з����ʲ�����Ա�����ִ�������ʲ�����Ա��ǩ��ʱ����ȷ��ת�豸
				if(chargeManId == 'ZHYFZCGLY' || chargeManId == 'FWZXZXZCGLY'){
					showOpenWin("?model=asset_daily_charge&action=toSignToDevice&id=" + row.id,1,700,1100,'signToDevice');
				}else{
					if (window.confirm(("ȷ��ǩ����"))) {
						$.ajax({
							type : "POST",
							url : "?model=asset_daily_charge&action=toSign&id="
									+ row.id,
							success : function(msg) {
								if (msg == 1) {
									alert('ǩ�ճɹ���');
									$("#datadictList").yxsubgrid("reload");
								} else {
									alert('ǩ��ʧ�ܣ�');
								}
							}
						});
					}
				}
			}
		}],

		searchitems : [{
			display : '���õ����',
			name : 'billNo'
		}, {
			display : '������',
			name : 'chargeMan'
		}, {
			display : '���ò���',
			name : 'deptName'
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
		// ҵ���������
		// boName : 'ȫ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC"

	});
});
