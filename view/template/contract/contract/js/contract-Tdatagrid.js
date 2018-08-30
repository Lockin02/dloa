var show_page = function(page) {
	$("#TGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [{
			text: "����",
			icon: 'delete',
			action: function (row) {
				history.go(0);
			}
		},{
            name: 'export',
            text: "������",
            icon: 'excel',
            action: function (row) {
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#TGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#TGrid").data('yxgrid').options.searchParam[t];
                    }
                }

                var states = $("#states").val();
                var isIncome = $("#isIncome").val();
				var isReplan = $("#isReplan").val();


                var i = 1;
                var colId = "";
                var colName = "";
                $("#TGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    })
                var searchSql = $("#TGrid").data('yxgrid').getAdvSql();

				var msg = $.ajax({
					url: '?model=contract_contract_contract&action=setColInfoToSession',
					data: 'ColId=' + colId + '&ColName='+colName + '&sType=exportContractTdate',
					dataType: 'html',
					type: 'post',
					async: false
				}).responseText;

				if(msg == 1){
                window
                    .open("?model=contract_contract_contract&action=TdataExportExcel"
                        + "&states="
                        + states
                        + "&isIncome="
                        + isIncome
						+ "&isReplan="
						+ isReplan
                        + "&searchSql="
                        + searchSql
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
				}
            }
		}],
	$("#TGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'tdatDataJson',
		title : '��ͬT����Ϣ��',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		leftLayout: true,
		customCode: 'TdataGrid',
		// ��չ�Ҽ��˵�
		menusEx : [],
		//		lockCol : ['conflag', 'exeStatus'],// ����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			able : true,
			hide : true
		}, {
			name : 'year',
			display : "���",
			able : true,
			width : 40
		}, {
			name : 'ExaDTOne',
			display : "����ʱ��",
			able : true,
			width : 70
		}, {
			name : 'signSubjectName',
			display : "ǩԼ����",
			able : true,
			width : 60
		}, {
			name : 'contractTypeName',
			display : "��ͬ����",
			able : true,
			width : 60
		}, {
			name : 'contractNatureName',
			display : '��ͬ����',
			able : true,
			width : 70,
			process : function(v, row) {
				if (v == 'NULL') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			able : true,
			width : 70
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			able : true,
			width : 70
		}, {
			name : 'customerTypeName',
			display : '�ͻ�����',
			able : true,
			width : 70
		}, {
			name : 'contractName',
			display : '��ͬ����',
			able : true,
			width : 70
		}, {
			name : 'payInfo',
			display : '�տ�����',
			able : true,
			width : 80
		}, {
			name : 'clauseInfo',
			display : '��������',
			able : true,
			width : 80
		}, {
			name : 'editInfo',
			display : '�޸ļ�¼',
			width : 80
		}, {
			name : 'incomeDate_1',
			display : '�ؿ�1��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_1',
			display : '�ؿ�1�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_1',
			display : '�ؿ�1�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_1',
			display : '�ؿ�1�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_2',
			display : '�ؿ�2��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_2',
			display : '�ؿ�2�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_2',
			display : '�ؿ�2�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_2',
			display : '�ؿ�2�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_3',
			display : '�ؿ�3��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_3',
			display : '�ؿ�3�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v+ "%";
				}
			}
		}, {
			name : 'incomeMoney_3',
			display : '�ؿ�3�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_3',
			display : '�ؿ�3�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_4',
			display : '�ؿ�4��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_4',
			display : '�ؿ�4�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_4',
			display : '�ؿ�4�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_4',
			display : '�ؿ�4�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_5',
			display : '�ؿ�5��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_5',
			display : '�ؿ�5�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_5',
			display : '�ؿ�5�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_5',
			display : '�ؿ�5�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_6',
			display : '�ؿ�6��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_6',
			display : '�ؿ�6�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_6',
			display : '�ؿ�6�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_6',
			display : '�ؿ�6�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_7',
			display : '�ؿ�7��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_7',
			display : '�ؿ�7�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_7',
			display : '�ؿ�7�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_7',
			display : '�ؿ�7�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_8',
			display : '�ؿ�8��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_8',
			display : '�ؿ�8�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_8',
			display : '�ؿ�8�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_8',
			display : '�ؿ�8�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_9',
			display : '�ؿ�9��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_9',
			display : '�ؿ�9�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_9',
			display : '�ؿ�9�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_9',
			display : '�ؿ�9�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_10',
			display : '�ؿ�10��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_10',
			display : '�ؿ�10�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_10',
			display : '�ؿ�10�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_10',
			display : '�ؿ�10�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_11',
			display : '�ؿ�11��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_11',
			display : '�ؿ�11�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_11',
			display : '�ؿ�11�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_11',
			display : '�ؿ�11�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_12',
			display : '�ؿ�12��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_12',
			display : '�ؿ�12�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_12',
			display : '�ؿ�12�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_12',
			display : '�ؿ�12�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_13',
			display : '�ؿ�13��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_13',
			display : '�ؿ�13�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_13',
			display : '�ؿ�13�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_13',
			display : '�ؿ�13�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_14',
			display : '�ؿ�14��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_14',
			display : '�ؿ�14�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_14',
			display : '�ؿ�14�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_14',
			display : '�ؿ�14�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'incomeDate_15',
			display : '�ؿ�15��T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'incomePtn_15',
			display : '�ؿ�15�ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'incomeMoney_15',
			display : '�ؿ�15�ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'invoiceMoney_15',
			display : '�ؿ�15�ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'signContractMoney',
			display : 'ǩԼ��ͬ��Ч��ͬ��',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unIncomeMoney',
			display : 'ǩԼ��ͬӦ���ʿ����',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'Tmoney',
			display : '��ȷ��T�ս��',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'uninvoiceMoney',
			display : 'ʣ�࿪Ʊ���',
			able : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'planInvoiceDate',
			display : '�ƻ���Ʊ����',
			width : 40
		}, {
			name : 'contractProvince',
			display : 'ʡ��',
			able : true,
			width : 60
		}, {
			name : 'contractCity',
			display : '����',
			able : true,
			width : 60
		}, {
			name : 'areaName',
			display : '��������',
			width : 60
		}, {
			name : 'prinvipalName',
			display : '��ͬ������',
			width : 60
		}, {
			name : 'outstockDate',
			display : '��ɽ���ʱ��',
			width : 60
		}, {
			name : 'relDate',
			display : '�����ڼ�',
			width : 60
		}, {
			name : 'projectState',
			display : '��Ŀ״̬',
			width : 60
		}, {
			name : 'projectEndDate',
			display : '��ĿԤ�ƽ���ʱ��',
			width : 60
		}, {
			name : 'payRemark',
			display : '��ͬ��Ŀ��ע',
			width : 60
		}, {
			name : 'unTdayMoney',
			display : 'δȷ��T�ս��',
			width : 60,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}],
		//		comboEx : [{
		//			text : '����',
		//			key : 'contractType',
		//			data : [{
		//				text : '���ۺ�ͬ',
		//				value : 'HTLX-XSHT'
		//			}, {
		//				text : '�����ͬ',
		//				value : 'HTLX-FWHT'
		//			}, {
		//				text : '���޺�ͬ',
		//				value : 'HTLX-ZLHT'
		//			}, {
		//				text : '�з���ͬ',
		//				value : 'HTLX-YFHT'
		//			}]
		//		}, {
		//			text : '��ͬ״̬',
		//			key : 'states',
		//			value : '0,1,2,3,4,5,6',
		//			data : [{
		//				text : '����(�����쳣��ͬ)',
		//				value : '0,1,2,3,4,5,6'
		//			}, {
		//				text : '������',
		//				value : '1'
		//			}, {
		//				text : 'ִ����',
		//				value : '2'
		//			}, {
		//				text : '�����',
		//				value : '4'
		//			}, {
		//				text : '�ѹر�',
		//				value : '3'
		//			}
		//					// , {
		//					// text : '�Ѻϲ�',
		//					// value : '5'
		//					// }, {
		//					// text : '�Ѳ��',
		//					// value : '6'
		//					// }
		//					, {
		//						text : '�쳣�ر�',
		//						value : '7'
		//					}, {
		//						text : '��Ч��ͬ(ִ�У���ɣ��ر�)',
		//						value : '2,3,4'
		//					}]
		//		}, {
		//			text : '����״̬',
		//			key : 'ExaStatusArr',
		//			data : [{
		//				text : 'δ����',
		//				value : 'δ����'
		//			}, {
		//				text : '��������',
		//				value : '��������'
		//			}, {
		//				text : '���������',
		//				value : '���������'
		//			}, {
		//				text : '���',
		//				value : '���'
		//			}, {
		//				text : '���',
		//				value : '���'
		//			}, {
		//				text : '��ɺͱ��������',
		//				value : '���,���������'
		//			}]
		//		}, {
		//			text : 'ǩԼ����',
		//			key : 'businessBelong',
		//			datacode : 'QYZT'
		//		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}, {
			display : '��Ʒ����',
			name : 'conProductName'
		}, {
			display : '������Ŀ',
			name : 'trialprojectCode'
		}],
		name : "createTime",
		buttonsEx: buttonsArr,
		//��������
        comboEx: [
            {
                text: '��ͬ״̬',
                key: 'states',
                value: '2,3,4',
                data: [
                    {
                        text: '����(�����쳣��ͬ)',
                        value: '0,1,2,3,4,5,6'
                    },
                    {
                        text: '������',
                        value: '1'
                    },
                    {
                        text: 'ִ����',
                        value: '2'
                    },
                    {
                        text: '�����',
                        value: '4'
                    },
                    {
                        text: '�ѹر�',
                        value: '3'
                    },
                    {
                        text: '�쳣�ر�',
                        value: '7'
                    },
                    {
                        text: '��Ч��ͬ(ִ�У���ɣ��ر�)',
                        value: '2,3,4'
                    }
                ]
            },
            {
                text: '�ؿ�״̬',
                key: 'isIncome',
                value: '0',
                data: [
                    {
                        text: 'δ��ɻؿ�',
                        value: '0'
                    },
                    {
                        text: '����ɻؿ�',
                        value: '1'
                    }
                ]
            },
			{
				text: '�ؿ�����',
				key: 'isReplan',
				data: [
					{
						text: '�лؿ�����',
						value: '0'
					},
					{
						text: '������/T��',
						value: '1'
					}
				]
			}
        ],
		// �߼�����
        advSearchOptions: {
            modelName: 'contractInfo',
            // ѡ���ֶκ��������ֵ����
            selectFn: function ($valInput) {
                $valInput.yxcombogrid_area("remove");
                $valInput.yxselect_user("remove");
            },
            searchConfig: [
                {
                    name: '��������',
                    value: 'c.ExaDTOne',
                    changeFn: function ($t, $valInput) {
                        $valInput.click(function () {
                            WdatePicker({
                                dateFmt: 'yyyy-MM-dd'
                            });
                        });
                    }
                },
                {
                    name: '��ݣ�ֱ���������֣���2013��',
                    value: 'date_format(c.ExaDTOne,"%Y")'
                },
                {
                    name: '�·ݣ�ֱ���������֣��� 04��11��',
                    value: 'date_format(c.ExaDTOne,"%m")'
                },
                {
                    name: '���ȣ�ֱ���������֣��� 1��2��3��4��',
                    value: 'quarter(c.ExaDTOne)'
                },
                {
                    name: '��ͬ����',
                    value: 'c.contractType',
                    type: 'select',
                    datacode: 'HTLX'
                }
                ,
                {
                    name: '�ͻ�����',
                    value: 'c.customerType',
                    type: 'select',
                    datacode: 'KHLX'
                },
                {
                    name: '��������',
                    value: 'c.areaPrincipal',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaPrincipalId' + rowNum,
                            nameCol: 'areaPrincipal',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '��������',
                    value: 'c.areaName',
                    changeFn: function ($t, $valInput, rowNum) {
                        $valInput.yxcombogrid_area({
                            hiddenId: 'areaCode' + rowNum,
                            nameCol: 'areaName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: '��ͬ������',
                    value: 'c.prinvipalName',
                    changeFn: function ($t, $valInput, rowNum) {

                        $valInput.yxselect_user({
                            hiddenId: 'prinvipalId' + rowNum,
                            nameCol: 'prinvipalName',
                            height: 200,
                            width: 550,
                            gridOptions: {
                                showcheckbox: true
                            }
                        });
                    }
                },
                {
                    name: 'ʡ��',
                    value: 'c.contractProvince'
                },
                {
                    name: '����',
                    value: 'c.contractCity'
                },
                {
                    name: '��ͬ״̬',
                    value: 'c.state',
                    type: 'select',
                    options: [
                        {
                            'dataName': 'δ�ύ',
                            'dataCode': '0'
                        },
                        {
                            'dataName': '������',
                            'dataCode': '1'
                        },
                        {
                            'dataName': 'ִ����',
                            'dataCode': '2'
                        },
                        {
                            'dataName': '�����',
                            'dataCode': '4'
                        },
                        {
                            'dataName': '�ѹر�',
                            'dataCode': '3'
                        },
                        {
                            'dataName': '�쳣�ر�',
                            'dataCode': '7'
                        }
                    ]

                },
                {
                    name: 'ǩԼ����',
                    value: 'c.businessBelong',
                    type: 'select',
                    datacode: 'QYZT'
                }
            ]
        }
	});

});
