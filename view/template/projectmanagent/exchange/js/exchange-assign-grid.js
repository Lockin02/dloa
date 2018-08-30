var show_page = function(page) {
	$("#shipmentsGrid").yxsubgrid("reload");
};

//��Ʒ����
function proNumCount(docId,type){
    var proNumCount = 0
     $.ajax({
          type : 'POST',
          url : '?model=common_contract_allsource&action=hasProduct',
          data : { id : docId,
                   type : type
                 },
          async: false,
          success : function (data){
                proNumCount = data;
                return false ;
          }
     })
     return proNumCount ;
}

$(function() {
	$("#shipmentsGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		action : 'shipmentsPageJson',
		customCode : 'exchangeShipmentsGrid',
		param : {
			'ExaStatusArr':"���,���������"
		},
		title : '��������ȷ������',
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
					width : 70,
					sortable : true
				}, {
					name : 'deliveryDate',
					display : '��������',
					width : 80,
					sortable : true
				}, {
					name : 'exchangeCode',
					display : '���������',
					sortable : true
				}, {
					name : 'contractCode',
					display : 'Դ����',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'saleUserName',
					display : '���۸�����',
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
			subgridcheck:true,
			url : '?model=projectmanagent_exchange_exchangeproduct&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				'isTemp' : '0','isDel' : '0'
			}, {
				paramId : 'exchangeId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color = 'red'>" + v + "</font>"
					} else
						return v;
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

		menusEx : [{
			text : '�鿴��������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.linkId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchangeequ&action=toViewTab&id='
						+ row.linkId + "&skey=" + row['skey_']);
			}
		}, {
			text : 'ȷ�Ϸ�������',
			icon : 'view',
			showMenuFn : function(row) {
				if ( row.lExaStatus == '' && row.ExaStatus == '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=projectmanagent_exchange_exchangeequ&action=toEquAdd&id='
						+ row.id + "&skey=" + row['skey_'],'exchangeassign');
			}
		}, {
			text : '�༭��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0 && (row.lExaStatus == 'δ�ύ'||row.lExaStatus == '���')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=projectmanagent_exchange_exchangeequ&action=toEquEdit&id='
						+ row.id + "&skey=" + row['skey_'],'exchangeassign');
			}
		}, {
			text : '�������ϱ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ( row.dealStatus != 0
//				&& proNumCount( row.id,'oa_contract_exchangeapply' )!= 0
				&& row.ExaStatus=='���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=projectmanagent_exchange_exchangeequ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_'],'exchangeassign');
			}
		}, {
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != '1'&&row.dealStatus != '3') {
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
							docType : 'oa_contract_exchangeapply'
						},
						// async: false,
						success : function(data) {
							if( data==1 ){
								alert('�رճɹ��������󽫷ŵ��Ѵ��������С�')
								show_page();
							}else{
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
//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_exchange_equ_link&pid='
//						+ row.lid
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//			}
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
				}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC'
	});
});