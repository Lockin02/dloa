var show_page = function (page) {
    $("#conprojectListGrid").yxsubgrid("reload");
};
$(function () {

});
$(function () {
    buttonsArr = [],
        SJGX = {
            name: 'edit',
            text: "���ݲ���",
            icon: 'copy',
            items: [
                {
                    text: '���ݸ���',
                    icon: 'copy',
                    action: function (row) {
                        showThickboxWin('?model=contract_conproject_conproject&action=progressView'
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
                    }
                },
                {
                    name: 'edit',
                    text: "����汾",
                    icon: 'save',
                    action: function (row) {
                        var version = $("#version").val();
                        showThickboxWin('?model=contract_conproject_conproject&action=saveVersionView'
                            + '&versionNum=' + version
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
                    }
                }
            ]
        };
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '���ݲ���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(SJGX);
            }
        }
    });
    LBDC = {
        name: 'export',
        text: "�б����ݵ���",
        icon: 'excel',
        action: function (row) {
            var searchConditionKey = "";
            var searchConditionVal = "";
            for (var t in $("#conprojectListGrid").data('yxsubgrid').options.searchParam) {
                if (t != "") {
                    searchConditionKey += t;
                    searchConditionVal += $("#conprojectListGrid")
                        .data('yxsubgrid').options.searchParam[t];
                }
            }
            var i = 1;
            var colId = "";
            var colName = "";
            var version = $("#nowVersion").val();
            $("#conprojectListGrid_hTable").children("thead").children("tr")
                .children("th").each(function () {
                    if ($(this).css("display") != "none"
                        && $(this).attr("colId") != undefined && $(this).attr("axis") != 'col1') {
                        colName += $(this).children("div").html() + ",";
                        colId += $(this).attr("colId") + ",";
                        i++;
                    }
                })
            var searchSql = $("#conprojectListGrid").data('yxsubgrid').getAdvSql();
            var searchArr = [];
            searchArr[0] = searchSql;
            searchArr[1] = searchConditionKey;
            searchArr[2] = searchConditionVal;
            window
                .open("?model=contract_conproject_conproject&action=exportExcel&colId="
                    + colId
                    + "&colName="
                    + colName
                    + "&searchConditionKey="
                    + searchConditionKey
                    + "&searchConditionVal=" + searchConditionVal
                    + "&version="
                    + version)
        }
    }
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '�б���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(LBDC);
            }
        }
    });
    DR = {
        name: 'excel',
        text: "�������",
        icon: 'excel',
        items: [
            {
                text: "��Ŀ���㵼��",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toLeadfinanceMoney"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            },
            {
                text: "��ͬ��Ŀ����",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toExcel"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            },
            {
                text: "����ȷ�Ϸ�ʽ����",
                icon: 'excel',
                action: function (row) {
                    showThickboxWin("?model=contract_conproject_conproject&action=toExcelExtend"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
                }
            }
        ]
    };
    $.ajax({
        type: 'POST',
        url: '?model=contract_conproject_conproject&action=getLimits',
        data: {
            'limitName': '���ݵ���'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(DR);
            }
        }
    });

    SX = {
        text: "����",
        icon: 'delete',
        action: function (row) {
            history.go(0)
        }
    }
    buttonsArr.push(SX);


    $("#conprojectListGrid").yxsubgrid({
        model: 'contract_conproject_conproject',
        action: 'conprojectStoreJson',
        param: {'version': $("#nowVersion").val()},
        customCode: 'conprojectStoreListNewList',
        title: '��ͬ��Ŀ��',
        isOpButton: false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox: false,
        leftLayout: true,
//		lockCol : ['conflag','checkTip'],// ����������
        //����Ϣ
        colModel: [
            {
                display: '��Ŀ����',
                name: 'proType',
                sortable: true,
                width: 50,
                align: 'center',
                process: function (v, row) {
//				return "<img src='images/icon/kong.gif' style='width:15px;height:15px;'>";
                    if (row.esmProjectId != '' && row.esmProjectId != '0') {
                        return "<img src='images/icon/service.jpg' ></span>";
                    } else {
                        return "<img src='images/icon/devi.jpg' ></span>";
                    }
                }
            },
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                display: 'pid',
                name: 'pid',
                sortable: true,
                hide: true
            },
            {
                name: 'contractId',
                display: '��ͬid',
                sortable: true,
                hide: true
            },
            {
                name: 'contractCode',
                display: '��ͬ���',
                sortable: true,
                width: 100,
                process: function (v, row) {
                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                        + row.contractId
                        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                        + "<font color = '#4169E1'>"
                        + row.contractCode
                        + "</font>" + '</a>';
                }
            },
            {
                display: 'esmProjectId',
                name: 'esmProjectId',
                sortable: true,
                hide: true
            },
            {
                name: 'projectCode',
                display: '��Ŀ���',
                sortable: true,
                width: 130,
                process: function (v, row) {
                    if (row.esmProjectId != '' && row.esmProjectId != '0') {
                        var skey = "";
                        $.ajax({
                            type: "POST",
                            url: "?model=engineering_project_esmproject&action=md5RowAjax",
                            data: { "id": row.esmProjectId },
                            async: false,
                            success: function (data) {
                                skey = data;
                            }
                        });
                        return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=engineering_project_esmproject&action=viewTab&id='
                            + row.esmProjectId
                            + '&skey='
                            + skey
                            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                            + "<font color = '#4169E1'>"
                            + v
                            + "</font>" + '</a>';
                    } else {
                        return v;

                    }
                }
            },
            {
                name: 'projectName',
                display: '��Ŀ����',
                sortable: true,
                hide: true
            },
            {
                name: 'proLineName',
                display: '��Ʒ��',
                sortable: true
            },
            {
                name: 'proLineCode',
                display: '��Ʒ�߱���',
                sortable: true,
                hide: true
            },
            {
                name: 'proportionTrue',
                display: '����ռ��',
                sortable: true,
                align: 'right',
                width: 50,
                hide: true,
                process: function (v) {
                    return v + "%";
                }
            },
            {
                name: 'officeName',
                display: 'ִ������',
                sortable: true
            },
            {
                name: 'officeId',
                display: 'ִ������id',
                sortable: true,
                hide: true
            },
            {
                name: 'proMoney',
                display: '��Ŀ��ͬ��',
                sortable: true,
                width: 60,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'rateMoney',
                display: '����˰���',
                sortable: true,
                align: 'right',
                width: 80,
                hide: true,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'proportion',
                display: '��Ŀռ��',
                sortable: true,
                align: 'right',
                width: 50,
                process: function (v) {
                    return v + "%";
                }
            },
            {
                name: 'contractMoney',
                display: '��ͬ���',
                sortable: true,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'status',
                display: '��Ŀ״̬',
                sortable: true,
                align: 'center',
                width: 50,
                datacode: 'GCXMZT'
            },
            {
                name: 'schedule',
                display: '��Ŀ����',
                sortable: true,
                width: 80,
                process: function (v) {
                    var v = formatProgress(v);
                    return v;
                }
            },
            {
                name: 'exgross',
                display: 'Ԥ��ë����',
                sortable: true,
                align: 'right',
                width: 70,
                process: function (v) {
                    if (v){
                        if (v < 0){
                            return "<span class='red'>" + v + "%</span>";
                        }else if(v==0){
                            return "-";
                        }else{
                            return v + "%";
                        }
                    }else{
                        return "-";
                    }
                }
            },
            {
                name: 'exgrossTrue',
                display: 'ë����',
                width: 50,
                sortable: true,
                align: 'right',
                process: function (v, row) {
                    if (v && (row.cost != '0.00' || row.costAct != '0.00') && row.schedule != '0.00')
                        if (v < 0 || ((v - row.exgross) < 0 && v != 0))
                            return "<span class='red'>" + v + "%</span>";
                        else
                            return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'estimates',
                display: '����',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'budget',
                display: 'Ԥ��',
                sortable: true,
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.estimates) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'cost',
                display: '����',
                sortable: true,
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.budget) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'costAct',
                display: '�������',
                sortable: true,
                align: 'right',
                width: 60,
                process: function (v, row) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else if ((v - row.budget) > 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'earnings',
                display: '����',
                sortable: true,
                width: 70,
                process: function (v) {
                    if (v == '0.00' || v == '0') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right'
            },
            {
                name: 'grossTrue',
                display: 'ë��',
                sortable: true,
                align: 'right',
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'gross',
                display: 'Ԥ��ë��',
                sortable: true,
                align: 'right',
                hide: true,
                process: function (v) {
                    if (v == '0.00') {
                        return "-";
                    } else if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    }
                    return moneyFormat2(v);
                }
            },
            {
                name: 'earningsTypeName',
                display: '����ȷ�Ϸ�ʽ',
                width: 80,
                sortable: true
            },
            {
                name: 'earningsTypeCode',
                display: '����ȷ�Ϸ�ʽ����',
                sortable: true,
                hide: true
            },
            {
                name: 'reserveEarnings',
                display: 'Ԥ��Ӫ��',
                sortable: true,
                width: 70,
                process: function (v) {
                    if (v == '0.00' || v == '0') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                align: 'right',
                hide: true
            },
            {
                name: 'txaRate',
                display: '˰��',
                sortable: true,
                align: 'right',
                width: 50,
                hide: true,
                process: function (v) {
                    if (v)
                        return v + "%";
                    else
                        return "-";
                }
            },
            {
                name: 'moduleName',
                display: '���',
                sortable: true,
                hide: true
            },
            {
                name: 'module',
                display: '������',
                sortable: true,
                hide: true
            },
            {
                name: 'planBeginDate',
                display: 'Ԥ�ƿ�ʼ����',
                sortable: true,
                hide: true
            },
            {
                name: 'planEndDate',
                display: 'Ԥ�ƽ�������',
                sortable: true,
                hide: true
            },
            {
                name: 'actBeginDate',
                display: 'ʵ�ʿ�ʼ����',
                sortable: true,
                hide: true
            },
            {
                name: 'actEndDate',
                display: 'ʵ�ʽ�������',
                sortable: true,
                hide: true
            }
            ,
            {
                display: '���˱�ʶ',
                name: 'checkTip',
                sortable: true,
                width: 50,
                align: 'center',
                hide: true,
                process: function (v, row) {
//				return "<img src='images/icon/kong.gif' style='width:15px;height:15px;'>";
                    if (v == '0') {
                        return "<span  onclick='checkTip(\"" + row.id + "\",\"" + row.pid + "\",1)'><img src='images/icon/shi.gif' style='width:20px;height:20px;'></span>";
                    } else if (v == '1') {
                        return "<span  onclick='checkTip(\"" + row.id + "\",\"" + row.pid + "\",0)'><img src='images/icon/kong.gif' style='width:20px;height:20px;'></span>";
                    }
                }
            },
            {
                name: 'deductMoney',
                display: '�ۿ�',
                align: 'right',
                width: 60,
                process: function (v,row) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            },
            {
                name: 'badMoney',
                display: '����',
                align: 'right',
                width: 60,
                process: function (v,row) {
                    if (v == '0.00') {
                        return "-";
                    }
                    return moneyFormat2(v);
                },
                hide: true
            }
        ],
        sortname: "contractId",
        // Ĭ������˳��
        sortorder: "desc",
        searchitems: [
            {
                display: "��ͬ���",
                name: 'contractCode'
            },
            {
                display: "��Ŀ���",
                name: 'projectCode'
            },
            {
                display: "ִ�в���",
                name: 'proLineName'
            }
        ],
        menusEx: [
//            {
//                text: 'ȷ��������㷽ʽ',
//                icon: 'add',
//                action: function (row) {
//                    showThickboxWin('?model=contract_conproject_conproject&action=incomeAcc&id='
//                        + row.id
//                        + '&pid='
//                        + row.pid
//                        + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
//                }
//            }
        ],
        comboEx: [
            {
                text: '��Ʒ��',
                key: 'proLineCode',
                datacode: 'HTCPX'
            },
            {
                text: 'ִ������',
                key: 'officeId',
                datacode: 'GCSCX'
            },
            {
                text: '��Ŀ״̬',
                key: 'status',
                datacode: 'GCXMZT'
            },
            {
                text: 'Ԥ������',
                key: 'warningStr',
                data: [
                    {
                        text: 'Ԥ������',
                        value: '1'
                    }
                ]
            }
        ],
        // ���ӱ������
        subGridOptions: {
            url: '?model=contract_conproject_conproject&action=conProsubJson',// ��ȡ�ӱ�����url
            // ���ݵ���̨�Ĳ�����������
            param: [
                {
                    paramId: 'pid',// ���ݸ���̨�Ĳ�������
                    colId: 'pid'// ��ȡ���������ݵ�������

                }
            ],
            // ��ʾ����
            colModel: [
                {
                    name: 'indexName',
                    display: 'ָ��',
                    sortable: true,
                    width: 60
                },
                {
                    name: 'January',
                    display: 'һ�·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'February',
                    display: '���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'March',
                    display: '���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'April',
                    display: '���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'May',
                    display: '���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'June',
                    display: '���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'July',
                    display: '���·�',
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'August',
                    display: '���·�',
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'September',
                    display: '���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'October',
                    display: 'ʮ�·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'November',
                    display: 'ʮһ�·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                },
                {
                    name: 'December',
                    display: 'ʮ���·�',
                    sortable: true,
                    width: 60,
                    process: function (v) {
                        return moneyFormat2(v);
                    }
                }
            ]
        },
        buttonsEx: buttonsArr
    });

//	 document.getElementById("storeYear").options.remove(0);
//	 document.getElementById("storeMon").options.remove(0);

    $("#view").append("<br/><div id='versionNum' class='red'>���°汾��: V<span>" + $("#maxVersion").val() + "</span></div>");
    var M = new Date()
    var Year = M.getFullYear();
    var Year2 = Year - 2;
    var Year1 = Year - 1;
    var month = M.getMonth() + 1;
    $("#view").append("<tr><td class='form_text_left'>�汾���</td>" +
        "<td class='form_view_right'>" +
        "<select class='selectauto' id='storeYear' style='width: 100%;' onchange='createVersionNum()'>" +
        "<option value='0'>" + "...ѡ��..." + "</option>" +
        "<option value='" + Year + "'>" + Year + "��</option>" +
        "<option value='" + Year1 + "'>" + Year1 + "��</option>" +
        "<option value='" + Year2 + "'>" + Year2 + "��</option>" +
        "</select></td></tr>  ");
    $("#view").append("<tr><td class='form_text_left'>�汾�·�</td>" +
        "<td class='form_view_right'>" +
        "<select class='selectauto' id='storeMon' style='width: 100%;' onchange='createVersionNum()'>" +
        "<option value='0'>" + "...ѡ��..." + "</option>" +
        "<option value='1'>1��</option><option value='2'>2��</option><option value='3'>3��</option><option value='4'>4��</option>" +
        "<option value='5'>5��</option><option value='6'>6��</option><option value='7'>7��</option><option value='8'>8��</option>" +
        "<option value='9'>9��</option><option value='10'>10��</option><option value='11'>11��</option><option value='12'>12��</option>" +
        "</select></td></tr>  ");

});
//�����鿴�汾��
function createVersionNum() {
    var year = $("#storeYear").val();
    var mon = $("#storeMon").val();

    varsionArr = [];
    $.ajax({
        type: "POST",
        url: "?model=contract_conproject_conproject&action=getVarsionArr",
        data: {"year": year, "mon": mon},
        async: false,
        success: function (data) {
            $("#view").append("<div id='verSelect'></div>");
            if (data != 0) {
                $("#verSelect").html("<tr><td class='form_text_left'>�汾��</td>" +
                    "<td class='form_view_right'>" +
                    "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
                    data +
                    "</select></td></tr>  ");
            } else {
                $("#verSelect").html("<tr><td class='form_text_left'>�汾��</td>" +
                    "<td class='form_view_right'>" +
                    "<select class='selectauto' id='version' style='width: 100%;' onchange='setVersion()'>" +
                    "<option>��������</option>" +
                    "</select></td></tr>  ");
            }

        }
    });

}
//���ò�ѯ�汾����
function setVersion() {
    var version = $("#version").val();
    if (version != '0') {
        $("#versionNum").html("<div id='versionNum' class='red'>���°汾��: V<span>" + $("#maxVersion").val() + "</span></div>" +
            "<div id='versionNum' class='blue'>��ǰ�汾��: V<span>" + version + "</span></div>");
    }

    $("#nowVersion").val(version);
    var listGrid = $("#conprojectListGrid").data('yxsubgrid');
    listGrid.options.extParam['version'] = version;
    listGrid.reload();

}

//�����б������ʾ
function formatProgress(value) {
    if (value) {
        var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
            + '<div style="width:'
            + value
            + '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
            + value + '%' + '</div>'
        '</div>';
        return s;
    } else {
        return '';
    }
}

//���˱�ʶ
function checkTip(id, pid, val) {
    $.ajax({
        type: "POST",
        url: "?model=contract_conproject_conproject&action=ajaxCheckTip",
        data: { "id": id, "pid": pid, "val": val},
        async: false,
        success: function (data) {
            skey = data;
            $("#conprojectListGrid").yxsubgrid("reload");
        }
    });
}
//���浱ǰ�汾����
function saveVersion() {
    $.ajax({
        type: "POST",
        url: "?model=contract_conproject_conproject&action=saveVersion",
        data: {},
        async: false,
        success: function (data) {
            alert("����ɹ�!");
            $("#conprojectListGrid").yxsubgrid("reload");
        }
    });
}