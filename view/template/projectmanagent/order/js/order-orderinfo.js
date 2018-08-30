var show_page = function(page) {
	$("#orderInfoGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [{
			name : 'advancedsearch',
			text : "�߼�����",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=search&gridName=orderInfoGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
			}
		},{
		name : 'export',
		text : "��ͬ����",
		icon : 'excel',
		action : function(row) {
				var searchConditionKey = "";
				var searchConditionVal = "";
				for (var t in $("#orderInfoGrid").data('yxgrid').options.searchParam) {
					if (t != "") {
						searchConditionKey += t;
						searchConditionVal += $("#orderInfoGrid")
								.data('yxgrid').options.searchParam[t];
					}
				}
				var type = $("#signinType").val();
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
				var beginDate = $("#orderInfoGrid").data('yxgrid').options.extParam.beginDate;// ��ʼʱ��
				var endDate = $("#orderInfoGrid").data('yxgrid').options.extParam.endDate;// ��ֹʱ��
				var ExaDT = $("#orderInfoGrid").data('yxgrid').options.extParam.ExaDT;// ����ʱ��
				var areaNameArr = $("#orderInfoGrid").data('yxgrid').options.extParam.areaNameArr;// ��������
				var orderCodeOrTempSearch = $("#orderInfoGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;// ��ͬ���
				var prinvipalName = $("#orderInfoGrid").data('yxgrid').options.extParam.prinvipalName;// ��ͬ������
				var customerName = $("#orderInfoGrid").data('yxgrid').options.extParam.customerName;// �ͻ�����
				var orderProvince = $("#orderInfoGrid").data('yxgrid').options.extParam.orderProvince;// ����ʡ��
				var customerType = $("#orderInfoGrid").data('yxgrid').options.extParam.customerType;// �ͻ�����
				var orderNatureArr = $("#orderInfoGrid").data('yxgrid').options.extParam.orderNatureArr;// ��ͬ����
				var isShip = $("#orderInfoGrid").data('yxgrid').options.extParam.DeliveryStatusArr;// �Ƿ��з�����¼
				var i = 1;
				var colId = "";
				var colName = "";
				$("#orderInfoGrid_hTable").children("thead").children("tr")
						.children("th").each(function() {
							if ($(this).css("display") != "none"
									&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
				window
						.open("?model=projectmanagent_order_order&action=exportExcel&colId="
								+ colId
								+ "&colName="
								+ colName
								+ "&type="
								+ type
								+ "&state="
								+ state
								+ "&ExaStatus="
								+ ExaStatus
								+ "&beginDate="
								+ beginDate
								+ "&endDate="
								+ endDate
								+ "&ExaDT="
								+ ExaDT
								+ "&areaNameArr="
								+ areaNameArr
								+ "&orderCodeOrTempSearch="
								+ orderCodeOrTempSearch
								+ "&prinvipalName="
								+ prinvipalName
								+ "&customerName="
								+ customerName
								+ "&orderProvince="
								+ orderProvince
								+ "&customerType="
								+ customerType
								+ "&orderNatureArr="
								+ orderNatureArr
								+ "&isShip="
								+ isShip
								+ "&searchConditionKey="
								+ searchConditionKey
								+ "&searchConditionVal="
								+ searchConditionVal
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
	}];

	//��ͷ��ť����
	updateProA = {
		name : 'update',
		text : "���¹���������",
		icon : 'add',
		action : function(row) {
				$.ajax({
					type : 'get',
					url : "?model=projectmanagent_order_order&action=updateProjectProcess",
					success : function(data) {
						if (data == 1) {
							alert("���³ɹ�.");
							show_page();
						} else {
							alert("����ʧ��.ʧ��ԭ��:"+data)
						}
					}

				});
			}
	};
    updateProB = {
		name : 'update',
		text : "����ҵ����",
		icon : 'add',
		action : function(row) {
				showThickboxWin("?model=common_contract_allsource&action=toUpdateObjCode"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=600")
			}
	};
	updateProC = {
		name : 'update',
		text : "���º�ͬ״̬",
		icon : 'add',
		action : function(row) {
				if(confirm("�������ݿ��ܻ���������ȷ�����������ȴ�,��Ҫ�ر��������ȷ��������")){
				   $.ajax({
						type : 'get',
						url : "?model=projectmanagent_order_order&action=updateContractState",
						success : function(data) {
							if (data == 1) {
								alert("���³ɹ�.");
								show_page();
							} else {
								alert("����ʧ��.ʧ��ԭ��:"+data)
							}
						}
					});
				}
			}
	};
	HTDR = {
		name : 'update',
		text : "��ͬ����",
		icon : 'excel',
		action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=toExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
	};
//	HTDC = {
//		name : 'export',
//		text : "��ͬ����",
//		icon : 'excel',
//		action : function(row) {
//				var searchConditionKey = "";
//				var searchConditionVal = "";
//				for (var t in $("#orderInfoGrid").data('yxgrid').options.searchParam) {
//					if (t != "") {
//						searchConditionKey += t;
//						searchConditionVal += $("#orderInfoGrid")
//								.data('yxgrid').options.searchParam[t];
//					}
//				}
//				var type = $("#tablename").val();
//				var state = $("#state").val();
//				var ExaStatus = $("#ExaStatus").val();
//				var beginDate = $("#orderInfoGrid").data('yxgrid').options.extParam.beginDate;// ��ʼʱ��
//				var endDate = $("#orderInfoGrid").data('yxgrid').options.extParam.endDate;// ��ֹʱ��
//				var ExaDT = $("#orderInfoGrid").data('yxgrid').options.extParam.ExaDT;// ����ʱ��
//				var areaNameArr = $("#orderInfoGrid").data('yxgrid').options.extParam.areaNameArr;// ��������
//				var orderCodeOrTempSearch = $("#orderInfoGrid").data('yxgrid').options.extParam.orderCodeOrTempSearch;// ��ͬ���
//				var prinvipalName = $("#orderInfoGrid").data('yxgrid').options.extParam.prinvipalName;// ��ͬ������
//				var customerName = $("#orderInfoGrid").data('yxgrid').options.extParam.customerName;// �ͻ�����
//				var orderProvince = $("#orderInfoGrid").data('yxgrid').options.extParam.orderProvince;// ����ʡ��
//				var customerType = $("#orderInfoGrid").data('yxgrid').options.extParam.customerType;// �ͻ�����
//				var orderNatureArr = $("#orderInfoGrid").data('yxgrid').options.extParam.orderNatureArr;// ��ͬ����
//				var isShip = $("#orderInfoGrid").data('yxgrid').options.extParam.DeliveryStatusArr;// �Ƿ��з�����¼
//				var i = 1;
//				var colId = "";
//				var colName = "";
//				$("#orderInfoGrid_hTable").children("thead").children("tr")
//						.children("th").each(function() {
//							if ($(this).css("display") != "none"
//									&& $(this).attr("colId") != undefined) {
//								colName += $(this).children("div").html() + ",";
//								colId += $(this).attr("colId") + ",";
//								i++;
//							}
//						})
//				window
//						.open("?model=projectmanagent_order_order&action=exportExcel&colId="
//								+ colId
//								+ "&colName="
//								+ colName
//								+ "&type="
//								+ type
//								+ "&state="
//								+ state
//								+ "&ExaStatus="
//								+ ExaStatus
//								+ "&beginDate="
//								+ beginDate
//								+ "&endDate="
//								+ endDate
//								+ "&ExaDT="
//								+ ExaDT
//								+ "&areaNameArr="
//								+ areaNameArr
//								+ "&orderCodeOrTempSearch="
//								+ orderCodeOrTempSearch
//								+ "&prinvipalName="
//								+ prinvipalName
//								+ "&customerName="
//								+ customerName
//								+ "&orderProvince="
//								+ orderProvince
//								+ "&customerType="
//								+ customerType
//								+ "&orderNatureArr="
//								+ orderNatureArr
//								+ "&isShip="
//								+ isShip
//								+ "&searchConditionKey="
//								+ searchConditionKey
//								+ "&searchConditionVal="
//								+ searchConditionVal
//								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
//			}
//	};
	HTWLDR = {
		name : 'importOrderPro',
		text : "��ͬ���ϵ���",
		icon : 'excel',
		action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=toImportOrderPro"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
	};
	CWJEDR = {
		name : 'import',
		text : "�������",
		icon : 'excel',
		action : function(row) {
				showThickboxWin("?model=projectmanagent_order_order&action=FinancialImportexcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
			}
	};
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(updateProA);
				buttonsArr.push(updateProB);
				buttonsArr.push(updateProC);

			}
		}
	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '��ͬ����'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(HTDR);
			}
		}
	});
//	$.ajax({
//		type : 'POST',
//		url : '?model=projectmanagent_order_order&action=getLimits',
//		data : {
//			'limitName' : '��ͬ����'
//		},
//		async : false,
//		success : function(data) {
//			if (data == 1) {
//				buttonsArr.push(HTDC);
//			}
//		}
//	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '��ͬ���ϵ���'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(HTWLDR);
			}
		}
	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_order_order&action=getLimits',
		data : {
			'limitName' : '�������'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(CWJEDR);
			}
		}
	});
	$("#orderInfoGrid").yxgrid({
		model : 'projectmanagent_order_order',
		action : 'OrderInfoJson',
		param : {
			'states' : '1,2,3,4,5,6'
		},

		title : '��ͬ��Ϣ',

		/**
		 * �Ƿ���ʾ�鿴��ť/�˵�
		 *
		 * @type Boolean
		 */
		isViewAction : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		/**
		 * �Ƿ���ʾɾ����ť/�˵�
		 *
		 * @type Boolean
		 */
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'orderinfo',
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row.tablename == 'oa_sale_order') {
					showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id='
							+ row.orgid + "&skey=" + row['skey_']);
				} else if (row.tablename == 'oa_sale_service') {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_lease') {
					showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_rdproject') {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id="
							+ row.orgid + "&skey=" + row['skey_'])
				}

			}
		}, {
			text : '����',
			icon : 'add',
			action : function(row) {
				window
						.open('?model=contract_common_allcontract&action=importCont&id='
								+ row.orgid
								+ '&type='
								+ row.tablename
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		}, {
			text : '�����ϴ�',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=projectmanagent_order_order&action=toUploadFile&id='
						+ row.orgid
						+ '&type='
						+ row.tablename
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
			}
		}, {
			text : '�����޸�',
			icon : 'edit',
			action : function(row) {
				if (row.tablename == 'oa_sale_order') {
					showOpenWin('?model=projectmanagent_order_order&action=productedit&id='
							+ row.orgid + "&skey=" + row['skey_']);
				} else if (row.tablename == 'oa_sale_service') {
					showOpenWin("?model=engineering_serviceContract_serviceContract&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_lease') {
					showOpenWin("?model=contract_rental_rentalcontract&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				} else if (row.tablename == 'oa_sale_rdproject') {
					showOpenWin("?model=rdproject_yxrdproject_rdproject&action=productedit&id="
							+ row.orgid + "&skey=" + row['skey_'])
				}

			}
		}, {
			text : '��ɺ�ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.com == 1 && row.state == 2) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ����ɡ� ״̬��"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_order_order&action=completeOrder&id="
								+ row.orgid + "&type=" + row.tablename,
						success : function(msg) {
							$("#orderInfoGrid").yxgrid("reload");
						}
					});
				}
			}
		}, {
			text : 'ִ�к�ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.com == 1 && row.state == 4) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫ�Ѻ�ͬ״̬��Ϊ ��ִ���С� ״̬��"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_order_order&action=exeOrder&id="
								+ row.orgid + "&type=" + row.tablename,
						success : function(msg) {
							if (msg == '0') {
								alert("��ͬ��������ɣ���ѡ��������");
								$("#orderInfoGrid").yxgrid("reload");
							} else {
								$("#orderInfoGrid").yxgrid("reload");
							}

						}
					});
				}
			}
		}],

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'createTime',
					display : '����ʱ��',
					sortable : true,
					width : 80
				}, {
					name : 'tablename',
					display : '��ͬ����',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'oa_sale_order') {
							return "���ۺ�ͬ";
						} else if (v == 'oa_sale_service') {
							return "�����ͬ";
						} else if (v == 'oa_sale_lease') {
							return "���޺�ͬ";
						} else if (v == 'oa_sale_rdproject') {
							return "�з���ͬ";
						} else if (v == '') {
							return "���ϼ�";
						}
					}
				}, {
					name : 'signinType',
					display : 'ǩ������',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'order') {
							return "���ۺ�ͬ";
						} else if (v == 'service') {
							return "�����ͬ";
						} else if (v == 'lease') {
							return "���޺�ͬ";
						} else if (v == 'rdproject') {
							return "�з���ͬ";
						}
					}
				}, {
					name : 'orderNatureName',
					display : '��ͬ����',
					sortable : true,
					width : 60
				}, {
					name : 'orderCode',
					display : '������ͬ��',
					sortable : true,
					width : 180,
					process : function(v, row) {
						if (row.isR == 1) {
							return "<font color = '#0000FF'>" + v;
						} else if (row.isBecome == 1) {
							return "<font color = '#FF0000'>" + v;
						} else {
							return v;
						}
					}
				}, {
					name : 'orderTempCode',
					display : '��ʱ��ͬ��',
					sortable : true,
					width : 180,
					process : function(v, row) {
						if (row.isR == 1) {
							return "<font color = '#0000FF'>" + v;
						} else if (row.isBecome == 1) {
							return "<font color = '#FF0000'>" + v;
						} else {
							return v;
						}
					}
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 100
				}, {
					name : 'customerId',
					display : '�ͻ�Id',
					sortable : true,
					width : 100,
					hide : true
				}, {
					name : 'customerType',
					display : '�ͻ�����',
					sortable : true,
					datacode : 'KHLX',
					width : 70
				}, {
					name : 'orderName',
					display : '��ͬ����',
					sortable : true,
					width : 150
				}, {
					name : 'signIn',
					display : 'ǩ��״̬',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "δǩ��";
						} else if (v == '1') {
							return "��ǩ��";
						}
					},
					width : 80
				}, {
					name : 'orderTempMoney',
					display : 'Ԥ�ƺ�ͬ���',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.orderMoney == '' || row.orderMoney == 0.00
								|| row.id == 'allMoney') {
							return moneyFormat2(v);
						} else {
							return "<font color = '#B2AB9B'>" + moneyFormat2(v)
									+ "</font>";
						}

					}
				}, {
					name : 'orderMoney',
					display : 'ǩԼ��ͬ���',
					sortable : true,
					width : 80,
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					name : 'invoiceMoney',
					display : '��Ʊ���',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'surplusInvoiceMoney',
					display : 'ʣ�࿪Ʊ���',
					sortable : true,
					process : function(v, row) {
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else {
							return "<font color = 'blue'>" + moneyFormat2(v);
							+"</font>"
						}
					}
				}, {
					name : 'incomeMoney',
					display : '���ս��',
					width : 60,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'surOrderMoney',
					display : 'ǩԼ��ͬӦ���˿����',
					sortable : true,
					width : 120,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v != '') {
							return "<font color = 'blue'>" + moneyFormat2(v);
							+"</font>"
						}
					}
				}, {
					name : 'surincomeMoney',
					display : '����Ӧ���˿����',
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v != '') {
							return "<font color = 'blue'>" + moneyFormat2(v);
							+"</font>"
						} else {
							return "<font color = 'blue'>"
									+ moneyFormat2(accSub(row.invoiceMoney,
											row.incomeMoney, 2)) + "</font>"
						}
					}
				}, {
					name : 'ExaStatus',
					display : '����״̬',
					sortable : true,
					width : 60
				}, {
					name : 'sign',
					display : '�Ƿ�ǩԼ',
					sortable : true,
					width : 70
				}, {
					name : 'areaName',
					display : '��������',
					sortable : true,
					width : 60
				}, {
					name : 'areaPrincipal',
					display : '��������',
					sortable : true
				}, {
					name : 'orderProvince',
					display : 'ʡ��',
					sortable : true,
					width : 60
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'state',
					display : '��ͬ״̬',
					sortable : true,
					process : function(v) {
						if (v == '0') {
							return "δ�ύ";
						} else if (v == '1') {
							return "������";
						} else if (v == '2') {
							return "ִ����";
						} else if (v == '3') {
							return "�ѹر�";
						} else if (v == '4') {
							return "�����";
						} else if (v == '5') {
							return "�Ѻϲ�";
						} else if (v == '6') {
							return "�Ѳ��";
						}
					},
					width : 60
				}, {
					name : 'softMoney',
					display : '������',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'hardMoney',
					display : 'Ӳ�����',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'serviceMoney',
					display : '������',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}, {
					name : 'repairMoney',
					display : 'ά�޽��',
					width : 80,
					sortable : true,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (row.orderMoney == '******'
								|| row.orderTempMoney == '******') {
							return "******";
						} else if (v == '') {
							return "0.00";
						} else {
							return moneyFormat2(v);
						}
					}
				}
				// , {
				// name : 'productConfirmMoney',
				// display : '��Ʒȷ������',
				// sortable : true,
				// width : 80,
				// process : function(v,row) {
				// if(row.id == "allMoney"){
				// return "";
				// }
				// if(row.FinanceCon == '1'){
				// return moneyFormat2(accAdd(row.softMoney,row.hardMoney));
				// }else{
				// return "******";
				// }
				// }
				// }
				, {
					name : 'serviceconfirmMoneyAll',
					display : '����ȷ��������',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							if (row.FinanceCon == '1') {
								return moneyFormat2(v);
							} else {
								return "******";
							}
						}
						if (row.FinanceCon == '1') {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_order_order&action=financialDetailTab&id='
									+ row.orgid
									+ '&tablename='
									+ row.tablename
									+ '&moneyType=serviceconfirmMoney'
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
									+ "<font color = '#4169E1'>"
									+ moneyFormat2(v) + "</font>" + '</a>';
						} else {
							return "******";
						}

					}
				}

				// , {
				// name : 'financeconfirmAmout',
				// display : '����ȷ���ܳɱ�',
				// sortable : true,
				// width : 80,
				// process : function(v,row) {
				// if(row.id == "allMoney"){
				// return "";
				// }
				// if(row.FinanceCon == '1'){
				// return
				// moneyFormat2(accAdd(row.serviceconfirmMoney,accAdd(row.softMoney,row.hardMoney)));
				// }else{
				// return "******";
				// }
				// }
				// }
				, {
					name : 'financeconfirmPlan',
					display : '����ȷ�Ͻ���',
					sortable : false,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if (row.orderMoney != '0.00') {
								var contractMoney = row.orderMoney;
							} else {
								var contractMoney = row.orderTempMoney;
							}
							var financePlan = (row.serviceconfirmMoney)
									/ (accSub(contractMoney, row.deductMoney));
							if (isNaN(financePlan)) {
								return "0.00%";
							} else {
								financePlan = parseFloat(financePlan).toFixed(2);
								return financePlan * 100 + "%";
							}
						} else {
							return "******";
						}
					}
				}, {
					name : 'financeconfirmMoneyAll',
					display : '����ȷ���ܳɱ�',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							if (row.FinanceCon == '1') {
								return moneyFormat2(v);
							} else {
								return "******";
							}
						} else {
							if (row.FinanceCon == '1') {
								return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_order_order&action=financialDetailTab&id='
										+ row.orgid
										+ '&tablename='
										+ row.tablename
										+ '&moneyType=financeconfirmMoney'
										+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
										+ "<font color = '#4169E1'>"
										+ moneyFormat2(v) + "</font>" + '</a>';
							} else {
								return "******";
							}
						}
					}
				}, {
					name : 'gross1',
					display : 'ë��',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if (isNaN(v) || !v) {
								return v;
							} else {
								return moneyFormat2(v);
							}

						} else {
							return "******";
						}
					}
				}, {
					name : 'rateOfGross1',
					display : 'ë����',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if (isNaN(v) || !v) {
								return v;
							} else {
								return v + "%";
							}
						} else {
							return "******";
						}
					}
				},
				// {
				// name : 'gross',
				// display : 'ë��',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.id == "allMoney" || row.id == undefined) {
				// return "";
				// }
				// if (row.FinanceCon == '1') {
				// return moneyFormat2(v);
				// } else {
				// return "******";
				// }
				// }
				// }, {
				// name : 'rateOfGross',
				// display : 'ë����',
				// sortable : true,
				// width : 80,
				// process : function(v, row) {
				// if (row.id == "allMoney" || row.id == undefined) {
				// return "";
				// }
				// if (row.FinanceCon == '1') {
				// if (v == '') {
				// return "0.00%";
				// }
				// return v + "%";
				// } else {
				// return "******";
				// }
				// }
				// },
				{
					name : 'budgetAll',
					display : '��Ԥ��',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'budgetOutsourcing',
					display : '�������Ԥ��',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'feeFieldCount',
					display : '�ֳ�����(ʵʱ)',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'feeOutsourcing',
					display : '�������(ʵʱ)',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'feeAll',
					display : '�ܷ���(ʵʱ)',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							if (row.FinanceCon == '1') {
								return moneyFormat2(v);
							} else {
								return "******";
							}
						}
						if (row.FinanceCon == '1') {
                            if (v == "") {
							  return "0.00";
						    }
							  return moneyFormat2(v);
						} else {
							return "******";
						}
					}
				}, {
					name : 'projectProcess',
					display : '����������',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (v == "") {
							return "0.00%";
						}
						return v + "%";
					}
				}, {
					name : 'processMoney',
					display : '��������ִ�к�ͬ��',
					sortable : true,
					width : 80,

					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							 moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'deductMoney',
					display : '�ۿ���',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == '') {
							return "0.00";
						} else {
							return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_order_order&action=financialDetailTab&id='
									+ row.orgid
									+ '&tablename='
									+ row.tablename
									+ '&moneyType=deductMoney'
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000\')">'
									+ "<font color = '#4169E1'>"
									+ moneyFormat2(v) + "</font>" + '</a>';
						}
					}
				}, {
					name : 'badMoney',
					display : '����',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return moneyFormat2(v);
						}
						if (v == "") {
							return "0.00";
						}
						return moneyFormat2(v);
					}
				}, {
					name : 'objCode',
					display : 'ҵ����',
					sortable : true,
					width : 120
				}, {
					name : 'completeDate',
//					name : "if(c.signinType='service' or c.state != 4,'-',c.completeDate)",
					display : '��ͬ�������',
					sortable : true,
					width : 80,
					process : function(v,row){
					   if(v == ''){
						    return "-";
						  }
						    return v ;
					}
				}, {
					name : 'exeDate',
					display : '��ִͬ��ʱ��',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if(row.state != '4' && row.state != '3'){
							if(v == '' || v == '-'){
						      return "-";
						    }
						    return "<span class='red'>" + v + "��</span>";
						}else{
						  if(v == '' || v == '-'){
						    return "-";
						  }
						    return v + "��";
						}
					}
				}, {
					name : "invoiceDifferenceTemp",
//					name : "if(c.signinType='service',(c.processMoney-i.invoiceMoney),'-')",
//					name : 'invoiceDifference',
					display : '��Ʊ��ִ�в���',
					sortable : true,
					width : 80,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
							if(v=='0.00'){
							   return "-";
							}else{
							  if (isNaN(v) || !v) {
									return v;
								} else {
									var m = v * 100;
									return moneyFormat2(m) + "%";
								}
							}

					}
				}, {
					name : 'AffirmincomeDifference',
					display : 'ȷ��������ִ�в���',
					sortable : true,
					width : 100,
					process : function(v, row) {
						if (row.id == "allMoney" || row.id == undefined) {
							return "";
						}
						if (row.FinanceCon == '1') {
							if(v=='0.00'){
							  return "-";
							}else{
							  if (isNaN(v) || !v) {
									return v;
								} else {
									return v + "%";
								}
							}
						} else {
							return "******";
						}
					}
				}],
		buttonsEx : buttonsArr,
		comboEx : [{
					text : '����',
					key : 'signinType',
					data : [{
								text : '���ۺ�ͬ',
								value : 'order'
							}, {
								text : '���޺�ͬ',
								value : 'lease'
							}, {
								text : '�����ͬ',
								value : 'service'
							}, {
								text : '�з���ͬ',
								value : 'rdproject'
							}]
				}, {
					text : '��ͬ״̬',
					key : 'state',
					data : [{
								text : '������',
								value : '1'
							}, {
								text : 'ִ����',
								value : '2'
							}, {
								text : '�����',
								value : '4'
							}, {
								text : '�ѹر�',
								value : '3'
							}, {
								text : '�Ѻϲ�',
								value : '5'
							}, {
								text : '�Ѳ��',
								value : '6'
							}]
				}, {
					text : '����״̬',
					key : 'ExaStatus',
					value : '���',
					data : [{
								text : 'δ����',
								value : 'δ����'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���������',
								value : '���������'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '���',
								value : '���'
							}]
				}],
		/**
		 * ��������
		 */
		searchitems : [{
					display : '��ͬ���',
					name : 'orderCodeOrTempSearch'
				}, {
					display : '��ͬ����',
					name : 'orderName'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : 'ҵ����',
					name : 'objCode'
				}],
		sortname : "isBecome desc,ExaDT"
			// // �߼�����
			// advSearchOptions : {
			// modelName : 'orderInfo',
			// // ѡ���ֶκ��������ֵ����
			// selectFn : function($valInput) {
			// $valInput.yxcombogrid_area("remove");
			// },
			// searchConfig : [{
			// name : '��������',
			// value : 'c.createTime',
			// changeFn : function($t, $valInput) {
			// $valInput.click(function() {
			// WdatePicker({
			// dateFmt : 'yyyy-MM-dd'
			// });
			// });
			// }
			// }, {
			// name : '��������',
			// value : 'c.areaPrincipal',
			// changeFn : function($t, $valInput, rowNum) {
			// if (!$("#areaPrincipalId" + rowNum)[0]) {
			// $hiddenCmp = $("<input type='hidden' id='areaPrincipalId"
			// + rowNum + "' value=''>");
			// $valInput.after($hiddenCmp);
			// }
			// $valInput.yxcombogrid_area({
			// hiddenId : 'areaPrincipalId' + rowNum,
			// height : 200,
			// width : 550,
			// gridOptions : {
			// showcheckbox : true
			// }
			// });
			// }
			// }]
			// }
			// sortorder : "DESC"

	});
		// $("#orderInfoGrid").yxgrid("createAdvSearchWin");
});