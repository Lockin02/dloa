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
                window
                    .open("?model=contract_contract_contract&action=TinitExportExcel&colId="
                        + colId
                        + "&colName="
                        + colName
                        + "&states="
                        + states
                        + "&isIncome="
                        + isIncome
                        + "&searchSql="
                        + searchSql
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
            }
		}],
	$("#TGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'tInitJson',
		title : '��ͬ�տ�����T����Ϣ��',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		leftLayout: true,
		customCode: 'TinitGrid',
		// ��չ�Ҽ��˵�
		menusEx : [],
		//		lockCol : ['conflag', 'exeStatus'],// ����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'year',
			display : "���",
			sortable : true,
			width : 40
		}, {
			name : 'ExaDTOne',
			display : "����ʱ��",
			sortable : true,
			width : 70
		}, {
			name : 'signSubjectName',
			display : "ǩԼ����",
			sortable : true,
			width : 60
		}, {
			name : 'contractTypeName',
			display : "��ͬ����",
			sortable : true,
			width : 60
		}, {
			name : 'contractNatureName',
			display : '��ͬ����',
			sortable : true,
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
			sortable : true,
			width : 70
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 70
		}, {
			name : 'customerTypeName',
			display : '�ͻ�����',
			sortable : true,
			width : 70
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 70
		}, {
			name : 'paymenttermInfo',
			display : '�տ�����',
			sortable : true,
			width : 80
		}, {
			name : 'clauseInfo',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'editInfo',
			display : '�޸ļ�¼',
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'Tday',
			display : '�ؿ�T��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'paymentPer',
			display : '�ؿ��ڱ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v + "%";
				}
			}
		}, {
			name : 'unIncomeMoney',
			display : '�ؿ��ڽ��',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInvMoney',
			display : '�ؿ��ڿ�Ʊ���',
			width : 60,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'cMoney',
			display : 'ǩԼ��ͬ��Ч��ͬ��',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unCmoney',
			display : '����Ӧ�տ�',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '' || row.Tday == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_a',
			display : '������Ӧ�տ�',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_b',
			display : '�¼���Ӧ�տ�',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_c',
			display : '�¼����Ժ�Ӧ�տ�',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_d',
			display : '����������',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_e',
			display : '����������������',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_f',
			display : '���ڰ�����һ��',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'income_g',
			display : '����һ������',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unCincMoney',
			display : 'ǩԼ��ͬӦ���˿����',
			sortable : true,
			width : 80,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'planIcomDate',
			display : '�ƻ��տ�����',
			width : 40,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'planInvoiceDate',
			display : '�ƻ���Ʊ����',
			width : 40,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'unTreason',
			display : '�޷���ɿ�Ʊ�տ�ԭ��',
			width : 40,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'unTremark',
			display : '�������',
			width : 40,
			process : function(v, row) {
				if (v == '') {
					return "-";
				} else {
					return v;
				}
			}
		}, {
			name : 'contractProvince',
			display : 'ʡ��',
			sortable : true,
			width : 60
		}, {
			name : 'contractCity',
			display : '����',
			sortable : true,
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
			name : 'remark',
			display : '��ͬ��Ŀ��ע',
			width : 60
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
		sortname : "id",
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
                    value: 'c.customerTypeName',
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
