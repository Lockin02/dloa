var show_page = function(page) {
	$("#shippingGrid").yxsubgrid("reload");
};

function hasEqu(objId) {
	var equNum = 0
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_borrow_borrowequ&action=getEquById',
		data : {
			id : objId
		},
		async : false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	var ifshow = true;
	var limits=$('#limits').val();
	var param = { 'outSea':$('#outSeaIds').val(),'ExaStatusArr' : '����,���','isshipments' : '1','DeliveryStatus2' : '0,2','limits' : limits };
	$("#shippingGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'shipmentsPageJson',

        param : param,
		title : '����' + limits + '�����÷���',
		// ��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		isDelAction : false,

		buttonsEx : [{
			name : 'export',
			text : "�������ݵ���",
			icon : 'excel',
			action : function(row) {
				window.open("?model=contract_common_allcontract&action=borrowExportExcel&limits="
								+ limits
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		}],
		// ����Ϣ
		colModel : [{
			name : 'status2',
			display : '�´�״̬',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.issuedStatus == 'YXD') {
					return "<img src='images/icon/icon073.gif' />";
				} else if( (!ifshow || row.isproShipcondition==1)&& row.issuedStatus != 'YXD' ){
					return "<img src='images/icon/green.gif' />";
				} else {
					return "";
				}
			}
		}, {
			name : 'rate',
			display : '����',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_borrow_borrow"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע : '+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : '�����÷�����־',
			name : 'isproShipcondition',
			sortable : true,
			hide : true
		},{
			display : 'isship',
			name : 'isship',
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			width : '75',
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '��������',
			width : 80,
			sortable : true
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
			width : '150',
			sortable : true
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
			name : 'DeliveryStatus',
			display : '����״̬',
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�ѷ���";
				} else if (v == '2') {
					return "���ַ���";
				} else if (v == '3') {
					return "ֹͣ����";
				}
			},
			width : '60',
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			sortable : true,
			process : function(v) {
				if (v == 'WXD') {
					return "δ�´�";
				} else if (v == 'BFXD') {
					return "�����´�";
				} else if (v == 'YXD') {
					return "���´�";
				} else if (v == 'WXFH') {
					return "���跢��";
				}
			},
			width : 60,
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			hide : true,
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
			sortable : true
		}, {
			name : 'scienceName',
			display : '����������',
			width : 80,
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			width : 280,
			sortable : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			width : 90,
			hide : true,
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
		}],
		sortname : 'ExaDT',
		sortorder : 'DESC',
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'borrowId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
						name : 'productName',
						width : 200,
						display : '��Ʒ����',
						process : function(v,row){
					    	if(row.changeTips == 1 || row.changeTips == 2){
					    		return "<font color = 'red'>"+ v + "</font>"
					    	}else
					    		return v;
					    }
					}, {
					    name : 'number',
					    display : '����',
						width : 40
					},{
					    name : 'lockNum',
					    display : '��������',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'exeNum',
					    display : '�������',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'issuedShipNum',
					    display : '���´﷢������',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'executedNum',
					    display : '�ѷ�������',
						width : 60
					},{
					    name : 'issuedPurNum',
					    display : '���´�ɹ�����',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'issuedProNum',
					    display : '���´���������',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'backNum',
					    display : '�˿�����',
						width : 60
					},{
					    name : 'projArraDate',
					    display : '�ƻ���������',
						width : 80,
					    process : function(v){
					    	if( v == null ){
					    		return '��';
					    	}else{
					    		return v;
					    	}
					    }
					}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
// text : '�鿴',
// icon : 'view',
// action : function(row) {
// if (row) {
// showOpenWin("?model=projectmanagent_borrow_borrow&action=init&perm=view&id="
// + row.id
// + "&skey="
// + row['skey_']
// );
// }
// }
// }, {
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByBorrow&id='
						+ row.id + "&objType=oa_borrow_borrow"
						+ "&skey="
						+ row['skey_']);
			}
		},{
			text : '�´﷢���ƻ�',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.issuedStatus != 'YXD' && (row.DeliveryStatus == 0 || row.DeliveryStatus == 2)) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&docType=oa_borrow_borrow"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
			}
		},{
			text : '�´�ɹ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus == 0 || row.DeliveryStatus == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if( row.orderCode == '' )
					var codeValue = row.orderTempCode;
				else
					var codeValue = row.orderCode;
				showModalWin('?model=purchase_external_external&action=purchasePlan&orderId='
						+ row.id
						+ "&orderCode="
						+ row.Code
						+ "&orderName="
						+ "&purchType=oa_borrow_borrow"
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1150');
			}
		}, {
			text : '�´���������',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus == 0
						|| row.DeliveryStatus == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin("?model=produce_protask_protask&action=toAdd&id="
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&docType=oa_borrow_borrow"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=1100");
			}
		}, {
// text : '���ȱ�ע',
// icon : 'edit',
// action : function(row) {
// showThickboxWin('?model=stock_outplan_contractrate&action=page&docId='
// + row.id
// + "&docType=oa_borrow_borrow"
// + "&objCode="
// + row.objCode
// + "&skey="
// + row['skey_']
// +
// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
// }
// }, {
			text : '�������',
			icon : 'lock',
			showMenuFn : function(row) {
				if (hasEqu(row.id) != 0 && row.DeliveryStatus == 0
						|| row.DeliveryStatus == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id + "&objCode=" + row.Code
						+ "&skey="
						+ row['skey_']
						+ "&objType=oa_borrow_borrow");
			}
		}, {
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus != 3) {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=contract_common_allcontract&action=closeCont&skey='+row['skey_'],
					data : {
						id : row.id,
						type : 'oa_borrow_borrow'
					},
					// async: false,
					success : function(data) {
						alert("�رճɹ�");
						show_page();
						return false;
					}
				});
			}
		}, {
			text : "�ָ�����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.DeliveryStatus == 3) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�ָ�������')) {
					$.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=recoverCont&skey='+row['skey_'],
						data : {
							id : row.id,
							type : 'oa_borrow_borrow'
						},
						// async: false,
						success : function(data) {
							alert("�ָ��ɹ�");
							show_page();
							return false;
						}
					});
				}
			}
		}]
	});

});