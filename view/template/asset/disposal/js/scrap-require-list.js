/**
 * ȷ�ϱ��������б�
 */
var show_page = function(page) {
	$("#requireList").yxsubgrid("reload");
};
$(function() {
	$("#requireList").yxsubgrid({
		model : 'asset_disposal_scrap',
		action : 'requirePageJson',
		title : 'ȷ�ϱ�������',
		showcheckbox : false,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���ϱ��',
			name : 'billNo',
			sortable : true,
			width : 110
		}, {
			display : '������������',
			name : 'scrapDate',
			sortable : true,
			width : 80
		}, {
			display : '�������벿��Id',
			name : 'deptId',
			sortable : true,
			hide : true
		}, {
			display : '�������벿��',
			name : 'deptName',
			sortable : true,
			width : 90
		}, {
			display : '����������',
			name : 'proposer',
			sortable : true
		}, {
			display : '��������',
			name : 'scrapNum',
			sortable : true,
			width : 60
		}, {
			display : '����ԭ��',
			name : 'reason',
			sortable : true,
			width : 70
		}, {
			display : '�����ܲ�ֵ',
			name : 'salvage',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '�����ܾ�ֵ',
			name : 'netValue',
			sortable : true,
			// �б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			},
			width : 80
		}, {
			display : '����ȷ��״̬',
			name : 'financeStatus',
			sortable : true,
			width : 70
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true,
			width : 70
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			width : 150
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_disposal_scrapitem&action=pageJson',
			param : [{
				paramId : 'allocateID',
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
				display : '����ͺ�',
				name : 'spec'
			}, {
				display : '��������',
				name : 'buyDate',
				width : 80
			}, {
				display : '�ʲ�ԭֵ',
				name : 'origina',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '��ֵ',
				name : 'salvage',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '��ֵ',
				name : 'netValue',
				// �б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '��ע',
				name : 'remark',
				width : 150
			}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�˶�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.financeStatus == "����ȷ��" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_disposal_scrap&action=toCheckRequire&id='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.financeStatus == "��ȷ��" && row.ExaStatus != '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/asset/disposal/ewf_index.php?actTo=ewfSelect&billId='
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			name : 'cancel',
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_scrap'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
						    	show_page();
								return false;
							}else{
								if(confirm('ȷ��Ҫ����������')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/disposal/ewf_index.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		searchitems : [{
			display : '���ϵ����',
			name : 'billNo'
		}, {
			display : '����������',
			name : 'proposer'
		}, {
			display : '�������벿��',
			name : 'deptName'
		}],
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC"

	});
});
