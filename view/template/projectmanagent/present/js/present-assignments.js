var show_page = function(page) {
	$("#shipmentsGrid").yxsubgrid("reload");
};

//��Ʒ����
function proNumCount(docId, type) {
	var proNumCount = 0
	$.ajax({
		type : 'POST',
		url : '?model=common_contract_allsource&action=hasProduct',
		data : {
			id : docId,
			type : type
		},
		async : false,
		success : function(data) {
			proNumCount = data;
			return false;
		}
	})
	return proNumCount;
}

$(function() {
	$("#shipmentsGrid").yxsubgrid({
		model : 'projectmanagent_present_present',
		action : 'assignmentJson',
		customCode : 'presentAssignmentsGrid',
		param : {
			'ExaStatusArr' : "���,���������,����ȷ��,��������",
		},
		title : '������������ȷ������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
			display : '��������״̬',
			name : 'lExaStatus',
			sortable : true,
			hide : true
		}, {
			display : '����������Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			width : 70,
			sortable : true
		}, {
			name : 'rate',
			display : '����',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_assignrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_present_present"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'deliveryDate',
			display : '��������',
			width : 80,
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 150,
			sortable : true
		}
//		, {
//			name : 'orderCode',
//			display : 'Դ�����',
//			width : 170,
//			sortable : true
//		}, {
//			name : 'orderName',
//			display : 'Դ������',
//			width : 170,
//			hide : true,
//			sortable : true
//		}
		, {
			name : 'Code',
			display : '���',
			width : 120,
			sortable : true,
			process : function(v, row) {
				if (v == '') {
					v = '��'
				}
				if (row.changeTips == 1) {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.oldId
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByPresent&id='
							+ row.id
							+ "&objType=oa_present_present"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
			name : 'dealStatus',
			display : '����״̬',
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				} else if (v == '2') {
					return "���δ����";
				} else if (v == '3') {
					return "�ѹر�";
				}else if (v == '4') {
					return "δ����";// �ύ��ͬ���ϴ�ȷ��
				}
			},
			width : '60',
			sortable : true
		}, {
			name : 'salesName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'reason',
			display : '��������',
			hide : true,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			hide : true,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 60,
			sortable : true
		}, {
			name : 'objCode',
			display : 'ҵ����',
			width : 120
		}, {
			name : 'rObjCode',
			display : 'Դ��ҵ����',
			width : 120
		}],
		// ���ӱ������
		subGridOptions : {
			subgridcheck : true,
			url : '?model=projectmanagent_present_product&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				// 'isTemp' : '0',
				'isDel' : '0'
			}, {
				paramId : 'presentId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, row) {
					if (row.changeTips == 1) {
						return '<img title="����༭�Ĳ�Ʒ" src="images/changeedit.gif" />'
								+ v;
					} else if (row.changeTips == 2) {
						return '<img title="��������Ĳ�Ʒ" src="images/new.gif" />'
								+ v;
					} else {
						return v;
					}
				}
			}, {
				name : 'conProductDes',
				width : 200,
				display : '��Ʒ����'
			}, {
				name : 'number',
				display : '����',
				width : 40
			}]
		},

		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByPresent&id='
						+ row.oldId
						+ "&objType=oa_present_present"
						+ "&linkId="
						+ row.linkId + "&skey=" + row['skey_']);
			}
		}, {
			//			text : '�鿴��������',
			//			icon : 'view',
			//			showMenuFn : function(row) {
			//				if (row.linkId) {
			//					return true;
			//				}
			//				return false;
			//			},
			//			action : function(row) {
			//				showModalWin('?model=projectmanagent_present_presentequ&action=toViewTab&id='
			//						+ row.linkId + "&skey=" + row['skey_']);
			//			}
			//		}, {
			text : 'ȷ�Ϸ�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.lExaStatus == '' && row.ExaStatus == '���' && row.dealStatus!='3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_present_presentequ&action=toEquAdd&id='
								+ row.id + "&skey=" + row['skey_'],
						'presentassign');
			}
		}, {
			text : '�༭��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0
						&& (row.lExaStatus == 'δ�ύ' || row.lExaStatus == '���')
						&& row.ExaStatus == '���') {
					return true;
				}else if(row.dealStatus == 4 && row.ExaStatus == '����ȷ��'){
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_present_presentequ&action=toEquEdit&id='
								+ row.id + "&skey=" + row['skey_'],
						'presentassign');
			}
		}, {
			text : '�������ϱ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != 0 && row.lExaStatus != ''
						//				&& proNumCount( row.id,'oa_present_present' )!= 0
						&& row.ExaStatus == '���'  && (row.dealStatus == '0' || row.dealStatus=='2')) {
					return true;
				}else if(row.dealStatus == 2 && row.ExaStatus == '����ȷ��' && row.isTemp != 4){
					return true;
				}
				return false;
			},
			action : function(row) {
				var isChange = (row.changeTips == 1)? 1 : 0;
				var url = ((row.dealStatus == 2 && row.ExaStatus == '����ȷ��'))?
						'?model=projectmanagent_present_presentequ&action=toEquChange&id='
						+ row.id + '&isChange='+isChange+'&skey=' + row['skey_']
						:
						'?model=projectmanagent_present_presentequ&action=toEquChange&id='
						+ row.id + '&skey=' + row['skey_'];
				window.open(url,'presentassign');
			}
		},{
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus=='4' || row.dealStatus=='2') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.dealStatus == '2'){
					var isSubAppChange = 1;
				}else{
					var isSubAppChange = 0;
				}
				if (window.confirm(("ȷ��Ҫ���?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_present_presentequ&action=ajaxBack&isSubAppChange="+isSubAppChange,
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('��سɹ���');
							}else{
								alert('���ʧ�ܣ�');
							}
							show_page();
						}
					});
				}
			}

		},{
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (false&&row.dealStatus != '1' && row.dealStatus != '3'
						&& row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�رո�����ȷ������')) {
					$.ajax({
						type : 'POST',
						url : '?model=common_contract_allsource&action=closeConfirm&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							docType : 'oa_present_present'
						},
						// async: false,
						success : function(data) {
							if (data == 1) {
								alert('�رճɹ��������󽫷ŵ��Ѵ��������С�')
								show_page();
							} else {
								alert('�ر�ʧ�ܣ�����ϵ����Ա��')
							}
							return false;
						}
					});
				}
			}
				//		}, {
				//			text : '�������',
				//			icon : 'view',
				//			showMenuFn : function(row) {
				//				if (row.lExaStatus != '') {
				//					return true;
				//				}
				//				return false;
				//			},
				//			action : function(row) {
				//
				//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_present_equ_link&pid='
				//						+ row.lid
				//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//			}
		}],

		comboEx : [{
			text : '����״̬',
			key : 'dealStatusArr',
			data : [{
				text : 'δ����',
				value : '0,2,4'
			}, {
				text : '�Ѵ���',
				value : '1,3'
			}],
			value : '0,2,4'
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}, {
			display : 'Դ��ҵ����',
			name : 'rObjCode'
		}, {
			display : '������',
			name : 'createName'
		}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC'
	});
});