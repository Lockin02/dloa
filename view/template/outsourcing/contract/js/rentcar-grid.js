var show_page = function(page) {
	$("#rentcarGrid").yxgrid("reload");
};

$(function() {
	$("#rentcarGrid").yxgrid({
		model : 'outsourcing_contract_rentcar',
        action:"pageJsonForAll",
		param : {
			'createId' : $("#createId").val()
		},
		title : '�⳵��ͬ',
		bodyAlign : 'center',
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'createDate',
			display: '¼������',
			sortable: true,
			width : 70
		},{
			name: 'orderCode',
			display: '������ͬ���',
			sortable: true,
			width : 130,
			process : function(v,row) {
				if (row.status == 4) {
					return "<a href='#' style='color:red' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				} else {
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				}
			}
		},{
			name: 'contractNature',
			display: '��ͬ����',
			sortable: true,
			width : 75
		},{
			name: 'contractType',
			display: '��ͬ����',
			sortable: true,
			width : 75
		},{
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'orderName',
			display: '��ͬ����',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'signCompany',
			display: 'ǩԼ��˾',
			sortable: true,
			width : 150,
			align : 'left'
		},{
			name: 'companyProvince',
			display: '��˾ʡ��',
			sortable: true,
			width : 70
		},{
			name: 'ownCompany',
			display: '������˾',
			sortable: true,
			width : 80
		},{
			name: 'linkman',
			display: '��ϵ��',
			sortable: true,
			hide : true,
			width : 60
		},{
			name: 'phone',
			display: '��ϵ�绰',
			sortable: true,
			hide : true,
			width : 85
		},{
			name: 'address',
			display: '��ϵ��ַ',
			sortable: true,
			hide : true,
			width : 150,
			align : 'left'
		},{
			name: 'signDate',
			display: 'ǩԼ����',
			sortable: true,
			width : 70
		},{
//			name: 'payedMoney',
//			display: '�Ѹ����',
//			sortable: true,
//			process : function(v) {
//				return moneyFormat2(v);
//			},
//			align : 'left'
//		},{
			name: 'orderMoney',
			display: '��ͬ���',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
            name: 'contractStartDate',
            display: '��ͬ��ʼ����',
            sortable: true,
            width : 75
        },{
            name: 'contractEndDate',
            display: '��ͬ��������',
            sortable: true,
            width : 75
        },{
            name: 'rentUnitPrice',
            display: '���޷���(Ԫ/��/��)',
            sortable: true,
            width : 100,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        },{
            name: 'fuelCharge',
            display: 'ȼ�ͷ�(Ԫ/����)',
            sortable: true,
            width : 85,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        }, {
            name: 'payApplyMoney',
            display: '���븶��',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        },{
            name: 'payedMoney',
            display: '�Ѹ����',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        }, {
            name: 'invotherMoney',
            display: '���շ�Ʊ',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        }, {
            name: 'confirmInvotherMoney',
            display: '����ȷ�Ϸ�Ʊ',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        }, {
            name: 'needInvotherMoney',
            display: 'ǷƱ���',
            sortable: true,
            process: function (v, row) {
                return "<span style='color:blue'>" + moneyFormat2(v) + "</span>";
            },
            width: 80
        },{
			name: 'returnMoney',
			display: '������',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
			name: 'status',
			display: '��ͬ״̬',
			sortable: true,
			width : 70,
			process : function(v,row) {
				var str = '';
				switch (v) {
					case '0' : str = 'δ�ύ';break;
					case '1' : str = '������';break;
					case '2' : str = 'ִ����';break;
					case '3' : str = '�ѹر�';break;
					case '4' : str = '�����';break;
					case '6' : str = 'δȷ��';break;
				}
				return str;
			}
		},{
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width : 70
		},{
			name: 'signedStatus',
			display: '��ͬǩ��',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return 'δǩ��';
				} else {
					return '��ǩ��';
				}
			}
		},{
			name: 'objCode',
			display: 'ҵ����',
			sortable: true
		},{
			name: 'isNeedStamp',
			display: '�Ƿ���Ҫ����',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '��';
				} else {
					return '��';
				}
			}
		},{
			name: 'isStamp',
			display: '�Ƿ��Ѹ���',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '��';
				} else {
					return '��';
				}
			}
		},{
			name: 'stampType',
			display: '��������',
			sortable: true,
			width : 150,
			align : 'left'
		},{
		// 	name: 'rentalcarCode',
		// 	display: '�⳵����Code',
		// 	sortable: true
		// },{
		// 	name: 'rentUnitPrice',
		// 	display: '���޵��ۣ�Ԫ/��/����',
		// 	sortable: true
		// },{
		// 	name: 'oilPrice',
		// 	display: '�ͼ�',
		// 	sortable: true
		// },{
		// 	name: 'fuelCharge',
		// 	display: 'ȼ�ͷѵ���',
		// 	sortable: true
		// },{
			name: 'createName',
			display: '������',
			sortable: true,
			width : 80
		},{
			name: 'updateTime',
			display: '����ʱ��',
			sortable: true,
			width : 120
		}],

		//���Ų˵�
		buttonsEx : [{
			name : 'searchAdv',
			text : "�߼�����",
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toSearchAdv"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		},
		// 	{
		// 	name : 'exportIn',
		// 	text : "����",
		// 	icon : 'excel',
		// 	action : function(row) {
		// 		showThickboxWin("?model=outsourcing_contract_rentcar&action=toExcelIn"
		// 			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
		// 	}
		// },
			{
			name : 'exportOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toExcelOut&isCreate=true"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
			}
		// },{
		// 	name : 'updatePro',
		// 	text : "����������Ŀ",
		// 	icon : 'excel',
		// 	action : function(row) {
		// 		showThickboxWin("?model=outsourcing_contract_rentcar&action=toExcelPro"
		// 			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
		// 	}
		}],

		//��չ�Ҽ��˵�
		menusEx : [{
			text : "�ύ����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if(row) {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_rentalcar&action=getOfficeInfoForId",
						data: {
							'projectId' : row.projectId
						},
						async: false,
						success: function(data) {
							if(data) {
								showThickboxWin('controller/outsourcing/contract/ewf_index.php?actTo=ewfSelect&billId='
									+ row.id + "&billArea=" + data
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							} else {
								showThickboxWin('controller/outsourcing/contract/ewf_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}
						}
					});
				}
			}
		},{
			text : '���븶��',
			icon : 'add',
			showMenuFn : function(row) {
				// return false; //��ʱ�رո���
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus == "���" && row.contractNatureCode == 'ZCHTXZ-01') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row ,rows ,grid) {
				if (row) {
					var data = '';
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_contract_rentcar&action=canPayapply",
						data: { "id" : row.id},
						async: false,
						success: function(data) {
							data = data;
						}
					});
					if(data == 'hasBack') {
						alert('��ͬ����δ������ɵ��˿���������븶��');
						return false;
					} else{ //������Լ�������
						showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&objType=YFRK-06&objId=" + row.id ,1 ,row.id);
					}
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : '¼�뷢Ʊ',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus == "���" && row.contractNatureCode == 'ZCHTXZ-01') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row ,rows ,grid) {
				if(row.orderMoney*1 <= accAdd(row.invotherMoney,row.returnMoney,2)*1) {
					alert('��ͬ��¼�뷢Ʊ������');
					return false;
				}
				showModalWin("?model=finance_invother_invother&action=toAddObj&objType=YFQTYD03&objId=" + row.id ,1 ,row.id);
			}
		},{
			name : 'stamp',
			text : '�������',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus != "���ύ") {
					if(row.isNeedStamp == '0') {
						return true;
					} else {
						if(row.isStamp == '0') {
							return false;
						} else{
							return true;
						}
					}
				} else {
					return false;
				}
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("?model=outsourcing_contract_rentcar&action=toStamp&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=900");
				}
			}
		},{
			name : 'file',
			text : '�ϴ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if(row.status == 3){
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toUploadFile&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		},{
			name : 'change',
			text : '�����ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.status == 2 && row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				showModalWin("?model=outsourcing_contract_rentcar&action=toChange&id=" + row.id ,'1');
			}
		},{
			text : '�����˿�',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.status == 3) {
					return false;
				}
				if (row.ExaStatus == "���" && row.contractNatureCode == 'ZCHTXZ-01')
					return true;
				else
					return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_contract_rentcar&action=canPayapplyBack",
						data: { "id" : row.id},
						async: false,
						success: function(data) {
							if(data == 'hasBack') {
								alert('��ͬ����δ������ɵĸ������룬���������˿�');
								return false;
							} else if (data*1 == '0') {
								alert('��ͬ���Ѹ�������������˿�');
								return false;
							} else if (data*1 == -1) {
								alert('��ͬ�˿����������������ܼ�������');
								return false;
							} else{
								showModalWin("?model=finance_payablesapply_payablesapply&action=toAddforObjType&payFor=FKLX-03&objType=YFRK-06&objId=" + row.id ,1 ,row.id);
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : '�رպ�ͬ',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���" && row.status == "2") {
					return true;
				}
				return false;
			},
			action: function(row) {
				if (window.confirm(("ȷ���ر���"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_contract_rentcar&action=changeStatus",
						data : {
							"id" : row.id
						},
						success : function(msg) {
							if( msg == 1 ) {
								alert('�رճɹ���');
								show_page();
							} else{
								alert('�ر�ʧ�ܣ�');
							}
						}
					});
				}
			}
		},{
			name : 'back',
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.status == 6) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=outsourcing_contract_rentcar&action=toBack&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=250&width=860");
			}
		},{
			name : 'delete',
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == '���ύ' || row.ExaStatus == '���') && row.status != 6) {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=outsourcing_contract_rentcar&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#rentcarGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_contract_rentcar&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx : [{
			text: "��ͬ״̬",
			key: 'status',
			data : [{
			// 	text : 'δȷ��',
			// 	value : '6'
			// },{
				text : '���ύ',
				value : '0'
			},{
				text : '������',
				value : '1'
			},{
				text : 'ִ����',
				value : '2'
			},{
				text : '�ѹر�',
				value : '3'
			},{
				text : '�����',
				value : '4'
			}]
		},{
			text: "��ͬ����",
			key: 'contractNatureCode',
			datacode : 'ZCHTXZ'
		},{
			text: "��ͬ����",
			key: 'contractTypeCode',
			datacode : 'ZCHTLX'
		}],

		toAddConfig: {
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_contract_rentcar&action=toAdd");
			}
		},
		toEditConfig: {
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					if (get['status'] == 6) { //��Ŀ�����ύ�����ı༭�����޸��⳵���뵥�š���Ŀ��Ϣ��ǩԼ��˾
						showModalWin("?model=outsourcing_contract_rentcar&action=toEditByRentalcar&id=" + get[p.keyField],'1');
					} else {
						showModalWin("?model=outsourcing_contract_rentcar&action=toEdit&id=" + get[p.keyField],'1');
					}
				}
			}
		},
		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems: [{
			display: "¼������",
			name: 'createTimeSea'
		},{
			display: "������ͬ���",
			name: 'orderCode'
		},{
			display: "��Ŀ����",
			name: 'projectName'
		},{
			display: "��Ŀ���",
			name: 'projectCode'
		},{
			display: "��ͬ����",
			name: 'orderName'
		},{
			display: "ǩԼ��˾",
			name: 'signCompany'
		},{
			display: "ǩԼ����",
			name: 'signDateSea'
		}]
	});
});