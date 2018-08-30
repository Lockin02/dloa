var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

// ��Ʒ����
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
	var limits = $('#limits').val();
	if (limits == '�ͻ�') {
		var ifshow = false;
	} else {
		var ifshow = true;
	}
	
	var param = {
		'ExaStatusArr' : "����,���,���������,����ȷ��",
        'dealStatusArr':"0,2",
		'limits' : limits
	};
	$("#shippingGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'assignmentJson',

		param : param,
		title : '����������ȷ������',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isDelAction : false,

		// ����Ϣ
		colModel : [{
//			display : 'ת��',
//			name : 'subTip',
//			width : '20',
//			process : function(v, row) {
//				if (row.subTip == 1) {
//					return "<img src='images/icon/icon063.gif' />";
//				}else{
//					return "";
//				}
//			},
//			sortable : true
//		}, {
			display : '��������״̬',
			name : 'lExaStatus',
			sortable : true
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
			width : '75',
			sortable : true
		}, {
			name : 'rate',
			display : '����',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_assignrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_borrow_borrow"
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
			sortable : true,
			process : function(v) {
				if (v == '0000-00-00') {
					return '';
				} else {
					return v;
				}
			}
		}, {
			name : 'standardDate',
			display : '��׼������',
			width : 80,
			sortable : true,
			process : function(v) {
				if (v == '0000-00-00') {
					return '';
				} else {
					return v;
				}
			}
		}, {
			name : 'chanceId',
			display : '�̻�Id',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 160,
			sortable : true,
			hide : ifshow
		}, {
			name : 'objCode',
			display : '����ҵ����',
			width : '150',
			sortable : true
		}, {
			name : 'Code',
			display : '���',
			sortable : true,
			width : '150',
			process : function(v, row) {
				if (row.changeTips == 1 || row.lExaStatus == '���������') {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
							+ row.id
							+ "&objType=oa_borrow_borrow"
							+ "&linkId="
							+ row.linkId
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=stock_outplan_outplan&action=viewByBorrow&id='
							+ row.id
							+ "&objType=oa_borrow_borrow"
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
			name : 'Type',
			display : '����',
			width : '40',
			sortable : true
		}, {
			name : 'limits',
			display : '��Χ',
			width : '40',
			sortable : true
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
				}
			},
			width : '60',
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			hide : ifshow,
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			width : 80,
			sortable : true
		}, {
			name : 'salesName',
			display : '���۸�����',
			width : 80,
			hide : ifshow,
			sortable : true
		}, {
			name : 'scienceName',
			display : '����������',
			width : 80,
			hide : ifshow,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 90,
			hide : true,
			sortable : true,
			process : function(v, row){
				if(v == '���' && row.lExaStatus == '���������'){
					return row.lExaStatus;
				}else{
					return v;
				}
			}
		}, {
			name : 'subTip',
			display : '�Ƿ�ת��',
			width : 90,
			sortable : true,
			process : function(v, row){
			    if(v == '0'){
			       return "��";
			    }else if(v == '1'){
			       return "<b>��</b>";
			    }
			}
		}, {
			name : 'reason',
			display : '��������',
			width : 280,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			width : 280,
			sortable : true
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���',
			name : 'Code'
		}, {
			display : '����ҵ����',
			name : 'objCode'
		}, {
			display : '������',
			name : 'createName'
		}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC',
		// ���ӱ������
		subGridOptions : {
			subgridcheck : true,
			url : '?model=projectmanagent_borrow_product&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				'isTemp' : '0',
				'isDel' : '0'
			}, {
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
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
		comboEx : [{
			text : '����״̬',
			key : 'dealStatusArr',
			data : [{
				text : 'δ����',
				value : '0,2'
			}, {
				text : '�Ѵ���',
				value : '1,3'
			}],
			value : '0,2'
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				window.open(
						'?model=stock_outplan_outplan&action=viewByBorrow&id='
								+ row.id + "&objType=oa_borrow_borrow"
								+ "&linkId=" + row.linkId + "&skey="
								+ row['skey_'], 'borrowassign');
			}
		}, {
			// text : '�鿴��������',
			// icon : 'view',
			// showMenuFn : function(row) {
			// if (row.linkId) {
			// return true;
			// }
			// return false;
			// },
			// action : function(row) {
			// showModalWin('?model=projectmanagent_borrow_borrowequ&action=toViewTab&id='
			// + row.linkId + "&skey=" + row['skey_']);
			// }
			// }, {
			text : 'ȷ�Ϸ�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.lExaStatus == '' && row.dealStatus != 2
						&& (row.ExaStatus == '����ȷ��')) {// ��Ȩ�������ĵ���
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_borrow_borrowequ&action=toEquAdd&id='
								+ row.id + "&skey=" + row['skey_'],
						'borrowassign');
			}
		}, {
			text : '�༭��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0
						&& (row.lExaStatus == 'δ�ύ' || row.lExaStatus == '���')
						&& (row.ExaStatus == '����ȷ��')
				) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open(
						'?model=projectmanagent_borrow_borrowequ&action=toEquEdit&id='
								+ row.id + "&skey=" + row['skey_'],
						'borrowassign');
			}
		}, {
			text : '�������ϱ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.needSalesConfirm === '3' && row.salesConfirmId > 0){
					return false;
				}else if (row.dealStatus != 0
						//&& row.lExaStatus != ''
						// && proNumCount( row.id,'oa_borrow_borrow' )!= 0
						&& (row.ExaStatus == '���' || ((row.ExaStatus == '����ȷ��' && row.dealStatus=='2'))) && row.status != 2
						&& (row.timeType != '���ڽ���')
						&& (row.lExaStatus != '���������')
//                    && (row.dealStatus == '0' || row.dealStatus=='2')
                   ) {
					return true;
				}else if(row.ExaStatus == '����'){
					return false;
				}
				return false;
			},
			action : function(row) {
				var fromWho = row.dealStatus == "1" ? "manager" : "apply";
				if (row.lExaStatus == '���������') {
					alert("���������ϱ�������У���ȴ�������ɡ�");
				} else {
					window.open(
						'?model=projectmanagent_borrow_borrowequ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_'] + "&fromWho=" + fromWho,
						'borrowassign');
				}
			}
		}, {
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (false && row.dealStatus != '1' && row.dealStatus != '3'
						&& (row.ExaStatus == '���' || row.ExaStatus == '����')) {
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
							docType : 'oa_borrow_borrow'
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
		}, {
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != '1' && row.dealStatus != '3' && row.ExaStatus != '���������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {

					showThickboxWin("?model=projectmanagent_borrow_borrow&action=rollBack&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700")
				} else {
					alert("��ѡ��һ������");
				}
			}
				// }, {
				// text : '�������',
				// icon : 'view',
				// showMenuFn : function(row) {
				// if (row.lExaStatus != '') {
				// return true;
				// }
				// return false;
				// },
				// action : function(row) {
				//
				// showThickboxWin('controller/projectmanagent/borrow/readview.php?itemtype=oa_borrow_equ_link&pid='
				// + row.lid
				// +
				// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				//			}
		}]
	});

});