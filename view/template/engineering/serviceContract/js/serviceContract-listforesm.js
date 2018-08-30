// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#serviceContractForESM").yxgrid("reload");
};
$(function() {
	$("#serviceContractForESM").yxgrid({
		model : 'engineering_serviceContract_serviceContract',
		action : 'pageJsonForESM',
		customCode : 'serviceContractForESM',
		title : '��Ŀ��ͬ�б�',
		param : {'ExaStatus' : '���'},
		isToolBar : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			},{
				name : 'tablename',
				display : '��ͬ����',
				sortable : true,
				process : function(v) {
					if (v == 'oa_sale_order') {
						return "���ۺ�ͬ";
					} else if (v == 'oa_sale_service') {
						return "�����ͬ";
					}
				},
				width : 80
			},{
				name : 'ExaDT',
				display : '����ʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'orderCode',
				display : '������ͬ��',
				sortable : true,
				width : 160
			}, {
				name : 'orderTempCode',
				display : '��ʱ��ͬ��',
				sortable : true,
				width : 160
			}, {
				name : 'customerName',
				display : '�ͻ�����',
				sortable : true,
				width : 150
			}, {
				name : 'orderName',
				display : '��ͬ����',
				sortable : true,
				width : 150,
				hide : true
			}, {
				name : 'orderProvince',
				display : 'ʡ��',
				sortable : true
			}, {
				name : 'orderTempMoney',
				display : 'Ԥ�ƺ�ͬ���',
				sortable : true,
				width : 80,
				process : function(v) {
					return moneyFormat2(v);
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
				name : 'sign',
				display : '�Ƿ�ǩԼ',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'areaName',
				display : '��������',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'prinvipalName',
				display : '��ͬ������',
				sortable : true,
				width : 100
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
					} else if (v == '5') {
						return "�Ѳ��";
					}
				},
				width : 60,
				hide : true
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				sortable : true,
				width : 60
			}, {
				name : 'dealStatus',
				display : '����״̬',
				sortable : true,
				width : 60,
				process : function(v){
					if(v == 0){
						return 'δ����';
					}else{
						return '�Ѵ���';
					}
				}
			}, {
				name : 'contractProcess',
				display : '��ͬ����',
				sortable : true,
				width : 60,
				process : function(v){
					return v + " %";
				}
			}, {
				name : 'objCode',
				display : 'ҵ����',
				sortable : true,
				width : 110
			}
		],
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if(row.tablename == 'oa_sale_order'){
					showOpenWin('?model=projectmanagent_order_order&action=init&perm=view&id='
						+ row.orgid)
				}else{
					showOpenWin('?model=engineering_serviceContract_serviceContract&action=init&perm=view&id='
						+ row.orgid)
				}
			}

		}, {
			text : '������Ŀ�³�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.dealStatus == 0) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.objCode == ""){
						alert('�˺�ͬû��ҵ���ţ�������ϵ����Ա����ҵ����');
						return false;
					}
					showThickboxWin("?model=engineering_charter_esmcharter&action=toAddInContract&contractId="
						+ row.orgid
						+ "&contractType=" + row.tablename
						+ "&objCode=" + row.objCode
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1000");
				}
			}
		}],
		buttonsEx : [{
			name : 'export',
			text : "������ͬ",
			icon : 'excel',
			action : function(row) {
				var type = "oa_sale_service"
				var state = $("#state").val();
				var ExaStatus = $("#ExaStatus").val();
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
						.open("?model=engineering_serviceContract_serviceContract&action=exportServiceExcel"
								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
			}
		}],
		//��������
		searchitems : [{
				display : '��ͬ����',
				name : 'orderName'
			}, {
				display : '��ͬ���',
				name : 'orderCodeOrTempSearch'
			}, {
				display : 'ҵ����',
				name : 'objCode'
			}
		],
		// ����״̬���ݹ���
		comboEx : [{
			text : '����״̬',
			key: 'dealStatus',
			value : '0',
			data : [{
				text : 'δ����',
				value : '0'
			}, {
				text : '�Ѵ���',
				value : '1'
			}]
		}],
		sortname : "createTime",
		//��ʾ�鿴��ť
		isViewAction : false
	});

});