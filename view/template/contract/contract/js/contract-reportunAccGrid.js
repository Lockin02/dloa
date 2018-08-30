var show_page = function(page) {
	$("#unAccGrid").yxgrid("reload");
};
var periodArr = [];
var periodDefault = '';
$.ajax({
    type: "POST",
    url: "?model=finance_period_period&action=getAllPeriod",
    data: {effectiveCost: 1},
    async: false,
    success: function (data) {
        periodArr = eval("(" + data + ")");
        if (periodArr.length > 0) {
            var newPeriod = [];
            for (var i = 0; i < periodArr.length; i++) {
                newPeriod.push({
                    value: periodArr[i].text,
                    text: periodArr[i].text
                });
            }
            periodArr = newPeriod;
            periodDefault = periodArr[0].value;
        }
    }
});

$(function() {
	buttonsArr = [{
                text: "����",
                icon: 'delete',
                action: function (row) {
                    var listGrid = $("#unAccGrid").data('yxgrid');
                    listGrid.options.extParam = {};
                    $("#caseListWrap tr").attr('style',
                        "background-color: rgb(255, 255, 255)");
                    listGrid.reload();
                }
            },{
            name: 'export',
            text: "������",
            icon: 'excel',
            action: function (row) {
                var searchConditionKey = "";
                var searchConditionVal = "";
                for (var t in $("#unAccGrid").data('yxgrid').options.searchParam) {
                    if (t != "") {
                        searchConditionKey += t;
                        searchConditionVal += $("#unAccGrid").data('yxgrid').options.searchParam[t];
                    }
                }
                var i = 1;
                var colId = "";
                var colName = "";
                var overPoint = $("#overPoint").val();
                var isIncome = $("#isIncome").val();
                var states = $("#states").val();
                $("#unAccGrid_hTable").children("thead").children("tr")
                    .children("th").each(function () {
                        if ($(this).css("display") != "none"
                            && $(this).attr("colId") != undefined) {
                            colName += $(this).children("div").html() + ",";
                            colId += $(this).attr("colId") + ",";
                            i++;
                        }
                    })
                window
                    .open("?model=contract_contract_contract&action=reportIncomeExcel&colId="
                        + colId
                        + "&colName="
                        + colName
                        + "&overPoint="
                        + overPoint
                        + "&isIncome="
                        + isIncome
                        + "&states="
                        + states
                        + "&searchConditionKey="
                        + searchConditionKey
                        + "&searchConditionVal=" + searchConditionVal)
            }
		}],
	$("#unAccGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'reportUnAccJson',
		title : '����Ӧ�տ����',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		leftLayout: true,
		showcheckbox : false,
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
			name : 'formBelongName',
			display : "������˾",
			able : true,
			width : 80
		}, {
			name : 'areaName',
			display : "��������",
			able : true,
			width : 80
		}, {
			name : 'unInomeMoney',
			display : '����Ӧ�տ�',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney3',
			display : '����������',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney6',
			display : '����������������',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney12',
			display : '���ڰ�����һ��',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}, {
			name : 'unInomeMoney24',
			display : '����һ������',
			able : true,
			width : 100,
			process : function(v, row) {
				if (v == '') {
					return "0.00";
				} else {
					return moneyFormat2(v);
				}
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '������˾',
			name : 'formBelongName'
		}, {
			display : '��������',
			name : 'areaName'
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
                text:'�������ڽڵ�',
                key: 'overPoint',
                data: periodArr
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
