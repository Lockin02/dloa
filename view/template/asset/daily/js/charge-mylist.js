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
		title : '�ҵ��ʲ�����',
		action : 'myPageJson',
		isToolBar : true,
		// isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���󵥱��',
			name : 'requireCode',
			sortable : true,
			width : 140
		}, {
			display : '���õ����',
			name : 'billNo',
			sortable : true,
			width : 140
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
			display : '������',
			name : 'createName',
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
						width : 150
					}, {
						display : '����ͺ�',
						name : 'spec',
						tclass : 'txtshort',
						readonly : true,
						width : 100
					}, {
						display : '��������',
						name : 'buyDate',
						// type : 'date',
						tclass : 'txtshort',
						readonly : true,
						width : 80
					}, {
//						display : 'Ԥ��ʹ���ڼ���',
//						name : 'estimateDay',
//						tclass : 'txtshort',
//						readonly : true
//					}, {
//						display : '�Ѿ�ʹ���ڼ���',
//						name : 'alreadyDay',
//						tclass : 'txtshort',
//						readonly : true
//					}, {
						display : 'ʣ��ʹ���ڼ���',// ���ڿ�Ƭ��Ԥ��ʹ���ڼ�����ȥ��ʹ���ڼ���
						name : 'residueYears',
						tclass : 'txtshort',
						readonly : true
					}, {
						display : '��ע',
						name : 'remark',
						tclass : 'txt',
						readonly : true,
						width : 180
					}]
		},
		// toAddConfig : {
		// formWidth : 900,
		// formHeight : 400,
		// showMenuFn : function(row) {
		// if (false) {
		// return true;
		// }
		// return false;
		// }
		// },
		// toEditConfig : {
		// formWidth : 900,
		// formHeight : 400,
		// showMenuFn : function(row) {
		// if (false) {
		// return true;
		// }
		// return false;
		// }
		// },
		// toViewConfig : {
		// formWidth : 900,
		// formHeight : 400
		// },

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
				// }, {
				// text : 'ɾ��',
				// icon : 'delete',
				// showMenuFn : function(row) {
				// if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row) {
				// if (window.confirm(("ȷ��ɾ����"))) {
				// $.ajax({
				// type : "GET",
				// url : "?model=asset_daily_charge&action=deletes&id="
				// + row.id,
				// success : function(msg) {
				// $("#datadictList").yxsubgrid("reload");
				// }
				// });
				// }
				// }
		}],

		searchitems : [{
			display : '���󵥱��',
			name : 'requireCode'
		}, {
			display : '���õ����',
			name : 'billNo'
		}, {
			display : "��Ƭ���",
			name : 'productCode'
		}, {
			display : "�ʲ�����",
			name : 'productName'
		}],
		comboEx : [{
			text : 'ǩ��״̬',
			key : 'isSign',
			data : [{
				text : 'δǩ��',
				value : '0'
			}, {
				text : '��ǩ��',
				value : '1'
			}]
		}, {
			text : '����״̬',
			key : 'docStatus',
			data : [{
				text : 'δ�黹',
				value : 'WGH'
			}, {
				text : '���ֹ黹',
				value : 'BFGH'
			}, {
				text : '�ѹ黹',
				value : 'YGH'
			}]
		}],
		// ҵ���������
		// boName : 'ȫ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC"

	});
});
