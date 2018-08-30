/** �ʲ�������Ϣ�б�
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxsubgrid("reload");
};
$(function() {
	$("#datadictList").yxsubgrid({
		model : 'asset_disposal_sell',
		title : '�ʲ�����',
		isToolBar : true,
		showcheckbox : false,
		//isViewAction : false,
		//isEditAction : false,
		//isAddAction : false,
		isDelAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '���۵����',
			name : 'billNo',
			sortable : true,
			width : 130
		}, {
			display : '����������',
			name : 'seller',
			sortable : true
		}, {
			display : '���������˲���',
			name : 'deptName',
			sortable : true
		}, {
			display : '������������',
			name : 'sellDate',
			sortable : true
		}, {
			display : '����������',
			name : 'sellNum',
			sortable : true
		}, {
			display : '�����ܽ��',
			name : 'sellAmount',
			sortable : true,
			//�б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '��������',
			name : 'donationDate',
			sortable : true
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '����ʱ��',
			name : 'ExaDT',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true
		}],
		// �б�ҳ������ʾ�ӱ�
		subGridOptions : {
			url : '?model=asset_disposal_sellitem&action=pageJson',
			param : [{
				paramId : 'sellID',
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
				display : '��������',
				name : 'buyDate',
				type : 'date',
				tclass : 'txtshort'
			}, {
				display : '�����豸',
				name : 'equip',
				type : 'statictext',
				process : function(e, data) {
					return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_equip&action=toPage&showBtn=0&assetCode='
							+ data.assetCode + '\')">��ϸ</a>'
				}
			}, {
				display : '�Ѿ�ʹ���ڼ���',
				name : 'alreadyDay',
				tclass : 'txtshort'
			}, {
				display : '�۳�����',
				name : 'deptName',
				tclass : 'txtshort'
			}, {
				display : '���۾ɽ��',
				name : 'depreciation',
				tclass : 'txtshort',
				//�б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}, {
				display : '�����ֵ',
				name : 'salvage',
				tclass : 'txtshort',
				//�б��ʽ��ǧ��λ
				process : function(v) {
					return moneyFormat2(v);
				}
			}]
		},
		toAddConfig : {
			formWidth : 1050,
			formHeight : 400
		},
		toEditConfig : {
			formWidth : 1050,
			formHeight : 400,
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 500
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('controller/asset/disposal/ewf_index_sell.php?actTo=ewfSelect&billId='
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
							examCode : 'oa_asset_sell'
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
									    url: "controller/asset/disposal/ewf_index_sell.php?actTo=delWork&billId=",
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
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���" || row.ExaStatus == "���" || row.ExaStatus == "��������")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_sell&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			name : 'clear',
			text : '����Ƭ',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					//ƽ��
					location = "?model=asset_assetcard_clean&action=toCleanSell&billNo="
							+ row.billNo + "&sellID=" + row.id;
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_disposal_sell&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxsubgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : '���۵����',
			name : 'billNo'
		}, {
			display : '����������',
			name : 'seller'
		}, {
			display : '���������˲���',
			name : 'deptName'
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
		// Ĭ������˳�� ����
		sortorder : "DESC"

	});
});
