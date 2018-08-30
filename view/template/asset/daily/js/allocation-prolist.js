// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#allocationProGrid").yxsubgrid('reload');
};

$(function() {
	$("#allocationProGrid").yxsubgrid({
		model : 'asset_daily_allocation',
		title : '�̶��ʲ�������Ϣ',
		action : "pageByProJson",
		param : {projectId:$("#projectId").val()},
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���������',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '��������',
			name : 'moveDate',
			sortable : true

		}, {
			display : '��������Id',
			name : 'outDeptId',
			sortable : true,
			hide : true

		}, {
			display : '��������',
			name : 'outDeptName',
			sortable : true
		}, {
			display : '���벿��id',
			name : 'inDeptId',
			sortable : true,
			hide : true
		}, {
			display : '���벿��',
			name : 'inDeptName',
			sortable : true

		}, {
			display : '����������',
			name : 'proposer',
			sortable : true
		}, {
			display : '����ȷ����',
			name : 'recipient',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'isSign',
			display : '�Ƿ�ǩ��',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '¼����',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�����',
			sortable : true,
			hide : true
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_daily_allocationitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
				colId : 'id'
			}],
			colModel : [{
				display : '��Ƭ���',
				name : 'assetCode',
				width : 250
			}, {
				display : '�ʲ�����',
				name : 'assetName'
			}, {
				display : 'Ӣ������',
				name : 'englishName',
				readonly : true
			}, {
				display : '��������',
				name : 'buyDate',
				// type : 'date',
				readonly : true
			}, {
				display : '����ͺ�',
				name : 'spec',
				readonly : true
			}, {
				display : '�����豸',
				name : 'equip',
				type : 'statictext',
				process : function(e, data) {
					return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='
							+ data.assetCode + '\')">��ϸ</a>'
				}
			}, {
				display : '��ע',
				name : 'remark'
			}]
		},
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		toAddConfig : {
			formWidth : 1100,
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
					showThickboxWin("?model=asset_daily_allocation&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=1100");
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'
						|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_allocation&pid="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}, {
			text : 'ǩ��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���" && row.isSign == "δǩ��" && row.inProId==$("#projectId").val()) {
					return true;
				} else {
					return false;
				}

			},
			action : function(row) {
				if (window.confirm(("ȷ��ǩ����"))) {

					$.ajax({
						type : "POST",
						url : "?model=asset_daily_allocation&action=sign&id="
								+ row.id,
						success : function(msg) {
							if (msg == 1) {
								alert('ǩ�ճɹ���');
								$("#allocationGrid").yxsubgrid("reload");
							} else {
								alert('ǩ��ʧ�ܣ�');
							}
						}
					});
				}
			}

		}],

		// ��������
		searchitems : [{
			display : '���������',
			name : 'billNo'
		}, {
			display : '��������',
			name : 'moveDate'
		}, {
			display : '��������',
			name : 'outDeptName'
		}, {
			display : '���벿��',
			name : 'inDeptName'
		}],
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			}, {
				text : '���ύ',
				value : '���ύ'
			}, {
				text : '���',
				value : '���'
			}, {
				text : '���',
				value : '���'
			}]
		}],
		// Ĭ�������ֶ���

		sortname : "id",
		// Ĭ������˳��

		sortorder : "DESC"

	});

});