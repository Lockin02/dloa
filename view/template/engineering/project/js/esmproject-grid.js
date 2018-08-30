var show_page = function () {
    $("#esmprojectGrid").yxgrid("reload");
};

$(function () {
	// ����汾��ȡ
	var feeDates = [];

	$.ajax({
		type: 'POST',
		url: '?model=engineering_records_esmfielddetail&action=getDates',
		async: false,
		success: function(data) {
			if (data != "") {
				feeDates = eval("(" + data + ")");
			}
		}
	});

    // ����汾��ȡ
    var incomeDates = [];

    $.ajax({
        type: 'POST',
        url: '?model=engineering_records_esmincome&action=getDates',
        async: false,
        success: function(data) {
            if (data != "") {
                incomeDates = eval("(" + data + ")");
            }
        }
    });

    //��ͷ��ť����
    var buttonsArr = [];

    //��ͷ��ť����
    var excelInArr = {
        name: 'exportIn',
        text: "����",
        icon: 'excel',
        action: function () {
            showThickboxWin("?model=engineering_project_esmproject&action=toExcelIn"
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
        }
    };

    var projectArr = {
        name: 'project',
        text: "������Ŀ",
        icon: 'add',
        action: function () {
            showThickboxWin("?model=engineering_project_esmproject&action=toUpdateProject"
            + "&placeValuesBefore&TB_iframe=true&modal=false&height=405&width=800")
        }
    };

    $.ajax({
        type: 'POST',
        url: '?model=engineering_project_esmproject&action=getLimitArr',
        async: false,
        success: function (data) {
            if (data != "") {
                data = eval("(" + data + ")");

                if (data['������Ŀ'] && data['������Ŀ'] == 1) {
                    buttonsArr.push(projectArr);
                }

                if (data['����Ȩ��'] && data['����Ȩ��'] == 1) {
                    buttonsArr.push(excelInArr);
                }

                // ���벿��
                var excelArr = {
                    name: 'excel',
                    text: "����",
                    icon: 'excel',
                    items: []
                };

                if (data['����Ȩ��-��Ŀ����'] && data['����Ȩ��-��Ŀ����'] == 1) {
                    excelArr['items'].push({
                        text: '��Ŀ���ܱ�',
                        icon: 'excel',
                        action: function () {
                            var thisGrid = $("#esmprojectGrid").data('yxgrid');
                            var colName = [];
                            var colCode = [];
                            var colModel = thisGrid.options.colModel;
                            for (var i = 0; i < colModel.length; i++) {
                                if (!colModel[i].hide) {
                                    colName.push(colModel[i].display);
                                    colCode.push(colModel[i].name);
                                }
                            }
                            var url = "?model=engineering_project_esmproject&action=exportSummary"
                                + '&status=' + filterUndefined(thisGrid.options.param.status)
                                + '&attribute=' + filterUndefined(thisGrid.options.param.attribute)
                                + '&colName=' + colName.toString() + '&colCode=' + colCode.toString();
                            if (thisGrid.options.qtype) {
                                url += "&" + thisGrid.options.qtype + "=" + thisGrid.options.query;
                            }
                            window.open(url, "", "width=200,height=200,top=200,left=200");
                        }
                    });
                }

                if (data['����Ȩ��-��Ŀ'] && data['����Ȩ��-��Ŀ'] == 1) {
                    excelArr['items'].push({
                        text: '��Ŀ��Ϣ-����Ա',
                        icon: 'excel',
                        action: function () {
                            var advParam = $("#esmprojectGrid").yxgrid('getAdvSearchArr');

                            var feeBeginDate = '';
                            var feeEndDate = '';
                            var incomeBeginDate = '';
                            var incomeEndDate = '';
                            if (advParam) {
                                for (var i = 0; i < advParam.length; i++) {
                                    if (advParam[i].searchField == 'feeBeginDate') {
                                        feeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'feeEndDate') {
                                        feeEndDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeBeginDate') {
                                        incomeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeEndDate') {
                                        incomeEndDate = advParam[i].value;
                                    }
                                }
                            }
                            window.open(
                                "?model=engineering_project_esmproject&action=exportExcel&feeBeginDate="
                                    + feeBeginDate + "&feeEndDate=" + feeEndDate
                                    + "&incomeBeginDate=" + incomeBeginDate + "&incomeEndDate=" + incomeEndDate,
                                "", "width=200,height=200,top=200,left=200");
                        }
                    }, {
                        text: '��Ŀ��Ϣ(Excel07)-����Ա',
                            icon: 'excel',
                            action: function () {
                            var advParam = $("#esmprojectGrid").yxgrid('getAdvSearchArr');
                            var feeBeginDate = '';
                            var feeEndDate = '';
                            var incomeBeginDate = '';
                            var incomeEndDate = '';
                            if (advParam) {
                                for (var i = 0; i < advParam.length; i++) {
                                    if (advParam[i].searchField == 'feeBeginDate') {
                                        feeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'feeEndDate') {
                                        feeEndDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeBeginDate') {
                                        incomeBeginDate = advParam[i].value;
                                    } else if (advParam[i].searchField == 'incomeEndDate') {
                                        incomeEndDate = advParam[i].value;
                                    }
                                }
                            }
                            window.open(
                                "?model=engineering_project_esmproject&action=exportExcel&excelType=07&feeBeginDate="
                                    + feeBeginDate + "&feeEndDate=" + feeEndDate
                                    + "&incomeBeginDate=" + incomeBeginDate + "&incomeEndDate=" + incomeEndDate,
                                "", "width=200,height=200,top=200,left=200");
                        }
                    }, {
                        text: '��ĿԤ����-����Ա',
                        icon: 'excel',
                        action: function () {
                            show_page();
                            window.open(
                                "?model=engineering_budget_esmbudget&action=exportAllExcel",
                                "", "width=200,height=200,top=200,left=200");
                        }
                    }, {
                        text: '��ĿԤ����(CSV)-����Ա',
                        icon: 'excel',
                        action: function () {
                            show_page();
                            window.open(
                                "?model=engineering_budget_esmbudget&action=exportAllExcelCSV",
                                "", "width=200,height=200,top=200,left=200");
                        }
                    });
                }

                if (data['����Ȩ��-�豸'] && data['����Ȩ��-�豸'] == 1) {
                    excelArr['items'].push({
                        text: '�豸��Ϣ-����Ա',
                        icon: 'excel',
                        action: function () {
                            window.open(
                                "?model=engineering_device_esmdevice&action=exportDeviceExcel",
                                "", "width=200,height=200,top=200,left=200");
                        }
                    });
                }

                // ����е����������룬��ô�����õ����˵�
                if (excelArr['items'].length > 0) {
                    buttonsArr.push(excelArr);
                }
            }
        }
    });

    $("#esmprojectGrid").yxgrid({
        model: 'engineering_project_esmproject',
        title: '��Ŀ���ܱ�-ʵʱ',
        isDelAction: false,
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        showcheckbox: false,
        customCode: 'esmprojectGridNew',
        isOpButton: false,
        autoload: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'attributeName',
            display: '��Ŀ����',
            width: 70,
            hide: true
        }, {
            name: 'productLineName',
            display: 'ִ������',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'newProLineName',
            display: '��Ʒ��',
            sortable: true,
            width: 60,
            hide: true
        }, {
            name: 'contractId',
            display: '��ͬid',
            sortable: true,
            hide: true
        }, {
            name: 'projectName',
            display: '��Ŀ����',
            sortable: true,
            width: 140,
            process: function (v, row) {
                return (row.contractId == "0" || row.contractId == "") && row.contractType != 'GCXMYD-04' ? "<span style='color:blue' title='δ������ͬ�ŵ���Ŀ'>" + v + "</span>" : v;
            }
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            sortable: true,
            width: 120,
            process: function (v, row) {
                if(row.pType == 'esm')
                    return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
                else
                    var cid = row.id.substring(1);
                return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=contract_conproject_conproject&action=viewTab&id=" + cid + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            width: 160,
            process: function (v, row) {
                if(row.attribute == 'GCXMSS-01'){
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_trialproject_trialproject&action=viewTab&id='
                        + row.contractId
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + v
                        + "</font>"
                        + '</a>';
                }
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + "<font color = '#4169E1'>"
                    + v
                    + "</font>"
                    + '</a>';
            },
            hide: true
        }, {
            name: 'managerName',
            display: '��Ŀ����',
            sortable: true,
            width: 80,
            hide: true
        }, {
            name: 'planBeginDate',
            display: 'Ԥ�ƿ�ʼ����',
            sortable: true,
            width: 80
        }, {
            name: 'planEndDate',
            display: 'Ԥ�ƽ�������',
            sortable: true,
            width: 80
        }, {
            name: 'statusName',
            display: '��Ŀ״̬',
            sortable: true,
            align: 'center',
            width: 60
        }, {
            name: 'categoryName',
            display: '��Ŀ���',
            sortable: true,
            width: 50,
            hide: true
        }, {
            name: 'projectProcess',
            display: '��Ŀ����',
            process: function (v) {
                return v != "" ? v + ' %' : "--";
            },
            align: 'right',
            width: 70
        }, {
            name: 'contractMoney',
            display: '��ͬ���',
            sortable: true,
            process: function (v, row) {
                if (row.contractType == 'GCXMYD-01') {
                    return moneyFormat2(v);
                } else {
                    return '--';
                }
            },
            align: 'right',
            width: 70,
            hide: true
        }, {
            name: 'projectMoneyWithTax',
            display: '��Ŀ���',
            sortable: true,
            process: function (v, row) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'estimates',
            display: '��Ŀ����',
            process: function (v, row) {
                // if (row.contractType == 'GCXMYD-01') {
                //     return moneyFormat2(v);
                // }
                if(v != ''){
                    return moneyFormat2(v);
                }else {
                    return '--';
                }
            },
            align: 'right',
            width: 70,
            hide: true
        }, {
            name: 'budgetAll',
            display: '��Ԥ��',
            process: function (v) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'feeAll',
            display: '�ܳɱ�',
            process: function (v) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'curIncome',
            display: '��ǰ����',
            process: function (v, row) {
                if (row.contractType == 'GCXMYD-01') {
                    return moneyFormat2(v);
                } else {
                    return '0';
                }
            },
            align: 'right',
            width: 70
        }, {
            name: 'grossProfit',
            display: '��Ŀë��',
            process: function (v, row) {
                return moneyFormat2(v);
            },
            align: 'right',
            width: 70
        }, {
            name: 'exgross',
            display: 'ë����',
            process: function (v, row) {
                if (row.contractType == 'GCXMYD-01') {
                    return v + " %";
                } else {
                    return '--';
                }
            },
            align: 'right',
            width: 70
        }, {
            name: 'outsourcingTypeName',
            display: '�������',
            sortable: true,
            width: 70,
            hide: true
        }, {
            name: 'CPI',
            display: 'CPI',
            width: 70,
            align: 'right',
            hide: true
        }, {
            name: 'SPI',
            display: 'SPI',
            width: 70,
            align: 'right',
            hide: true
        }, {
            name: 'feeAllProcess',
            display: '���ý���',
            process: function (v) {
                return v != "" ? v + ' %' : "--";
            },
            align: 'right',
            width: 70,
            hide: true
        }],
        buttonsEx: buttonsArr,
        // ��չ�Ҽ��˵�
        menusEx: [{
            text: '�鿴��Ŀ',
            icon: 'view',
            action: function (row) {
            	if(row.pType == 'esm'){
            	   showModalWin("?model=engineering_project_esmproject&action=viewTab&id="+ row.id + "&skey=" + row.skey_, 1, row.id);
            	}else{
            		var cid = row.id.substring(1);
            	    showModalWin("?model=contract_conproject_conproject&action=viewTab&id="+ cid, 1, row.id);
            	}
            }
        }, {
            text: '�༭��Ŀ',
            icon: 'edit',
            showMenuFn: function (row) {
                return $("#editProjectLimit").val() == "1" && row.pType == 'esm';
            },
            action: function (row) {
            	  showModalWin("?model=engineering_project_esmproject&action=toEditRight&id="
                + row.id + "&skey=" + row.skey_, 1, row.id);
            }
        }, {
            name: 'aduit',
            text: '�������',
            icon: 'view',
            showMenuFn: function (row) {
                return (row.ExaStatus == "���" || row.ExaStatus == "���") && row.pType == 'esm';
            },
            action: function (row) {
                    showThickboxWin("controller/common/readview.php?itemtype=oa_esm_project&pid="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
            }
        }, {
            name: 'incomeType',
            text: '��������ȷ�Ϸ�ʽ',
            icon: 'edit',
            action: function (row) {
                showThickboxWin("?model=engineering_baseinfo_esmcommon&action=updateIncomeType&id="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
            }
        }, {
            name: 'outsourcing',
            text: '�������',
            icon: 'add',
            showMenuFn: function (row) {
                return row.pType == 'esm' && (row.status == "GCXMZT02" || row.status == "GCXMZT01");
            },
            action: function (row) {
                window.open("?model=outsourcing_outsourcing_apply&action=toAddFromProject&projectId="
                    + row.id);
            }
        }, {
            name: 'openClose',
            text: '�����ر���Ŀ',
            icon: 'edit',
            showMenuFn: function (row) {
                return row.pType == 'esm' && $("#openCloseLimit").val() == '1' && row.ExaStatus == '���';
            },
            action: function (row) {
                showThickboxWin("?model=engineering_project_esmproject&action=toOpenClose&id="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
            }
        }],
        // �߼�����
        advSearchOptions: {
            modelName: 'esmprojectSearch',
            // ѡ���ֶκ��������ֵ����
            selectFn: function ($valInput) {
                $valInput.yxselect_user("remove");
                $valInput.yxcombogrid_office("remove");
            },
            searchConfig: [{
                name: '��Ŀ����',
                value: 'c.projectName'
            }, {
                name: '��Ŀ���',
                value: 'c.projectCode'
            }, {
                name: '��������',
                value: 'c.officeName',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#officeId" + rowNum)[0]) {
                        var $hiddenCmp = $("<input type='hidden' id='officeId" + rowNum + "'/>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxcombogrid_office({
                        hiddenId: 'officeId' + rowNum,
                        height: 200,
                        width: 550,
                        gridOptions: {
                            showcheckbox: false
                        }
                    });
                }
            }, {
                name: '��Ŀ����',
                value: 'c.managerName',
                changeFn: function ($t, $valInput, rowNum) {
                    if (!$("#managerId" + rowNum)[0]) {
                        var $hiddenCmp = $("<input type='hidden' id='managerId" + rowNum + "'/>");
                        $valInput.after($hiddenCmp);
                    }
                    $valInput.yxselect_user({
                        hiddenId: 'managerId' + rowNum,
                        height: 200,
                        width: 550,
                        gridOptions: {
                            showcheckbox: false
                        }
                    });
                }
            }, {
                name: '��Ŀ״̬',
                value: 'c.status',
                type: 'select',
                datacode: 'GCXMZT'
            }, {
                name: '��������',
                value: 'nature',
                type: 'select',
                datacode: 'GCXMXZ'
            }, {
                name: '�������',
                value: 'outsourcing',
                type: 'select',
                datacode: 'WBLX'
            }, {
                name: '��/����',
                value: 'cycle',
                type: 'select',
                datacode: 'GCCDQ'
            }, {
                name: '��Ŀ���',
                value: 'category',
                type: 'select',
                datacode: 'XMLB'
            }, {
				name: '���㿪ʼ����',
				value: 'feeBeginDate',
				type: 'select',
				options: feeDates
			}, {
				name: '�����������',
				value: 'feeEndDate',
				type: 'select',
				options: feeDates
			}, {
                name: '���뿪ʼ����',
                value: 'incomeBeginDate',
                type: 'select',
                options: incomeDates
            }, {
                name: '�����������',
                value: 'incomeEndDate',
                type: 'select',
                options: incomeDates
            }]
        },
        searchitems: [{
            display: '���´�',
            name: 'officeName'
        }, {
            display: '��Ŀ���',
            name: 'projectCodeSearch'
        }, {
            display: '��Ŀ����',
            name: 'projectName'
        }, {
            display: '��Ŀ����',
            name: 'managerName'
        }, {
            display: 'ҵ����',
            name: 'rObjCodeSearch'
        }, {
            display: '������ͬ��',
            name: 'contractCodeSearch'
        }],
        // ����״̬���ݹ���
        comboEx: [{
            text: "��Ŀ����",
            key: 'attribute',
            datacode: 'GCXMSS'
        }, {
            text: "��Ŀ״̬",
            key: 'status',
            datacode: 'GCXMZT'
        }],
        // Ĭ�������ֶ���
        sortname: "c.updateTime",
        // Ĭ������˳�� ����
        sortorder: "DESC"
    });
});

//�����鿴�汾��
function createVersionNum() {
    var storeYear = $("#storeYear").val();
    var storeMonth = $("#storeMonth").val();

    // $.ajax({
    //     type: "POST",
    //     url: "?model=engineering_records_esmrecord&action=getVersionArr",
    //     data: {storeYear: storeYear, storeMonth: storeMonth},
    //     async: false,
    //     success: function(data) {
    //         $("#view").append("<div id='verSelect'></div>");
    //         if (data != 0) {
    //             $("#verSelect").html("<tr><td class='form_text_left'>�汾��</td>" +
    //                 "<td class='form_view_right'>" +
    //                 "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
    //                 data +
    //                 "</select></td></tr>");
    //         } else {
    //             $("#verSelect").html("<tr><td class='form_text_left'>�汾��</td>" +
    //                 "<td class='form_view_right'>" +
    //                 "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
    //                 "<option>��������</option>" +
    //                 "</select></td></tr>");
    //         }
    //     }
    // });
}