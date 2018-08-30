var show_page = function () {
    $("#salesListGrid").yxgrid("reload");
};
$(function () {

    // ���ܰ�ť
    var buttonsArr = [];
    // �Ҽ��˵�
    var menuArr = [{
        text: '�鿴',
        icon: 'view',
        action: function (row) {
            showModalWin('?model=projectmanagent_trialproject_trialproject&action=viewTab&id='
                + row.id
                + "&skey="
                + row['skey_']
                + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
        }
    }, {
        text: '�������',
        icon: 'view',
        showMenuFn: function (row) {
            return row.ExaStatus == '��������';
        },
        action: function (row) {
            showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_trialproject&pid='
                + row.id
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
        }
    }];

    $.ajax({
        type: 'POST',
        url: '?model=projectmanagent_trialproject_trialproject&action=getLimitArr',
        async: false,
        success: function (data) {
            if (data != "") {
                data = eval("(" + data + ")");

                // ��������Ȩ��
                if (data['����Ȩ��'] && data['����Ȩ��'] == 1) {
                    buttonsArr = [{
                        name: 'add',
                        text: "����",
                        icon: 'excel',
                        action: function () {
                            var searchConditionKey = "";
                            var searchConditionVal = "";
                            for (var t in $("#salesListGrid").data('yxgrid').options.searchParam) {
                                if (t != "") {
                                    searchConditionKey += t;
                                    searchConditionVal += $("#salesListGrid").data('yxgrid').options.searchParam[t];
                                }
                            }
                            var i = 1;
                            var colId = "";
                            var colName = "";
                            $("#salesListGrid_hTable").children("thead").children("tr")
                                .children("th").each(function () {
                                    if ($(this).css("display") != "none"
                                        && $(this).attr("colId") != undefined) {
                                        colName += $(this).children("div").html() + ",";
                                        colId += $(this).attr("colId") + ",";
                                        i++;
                                    }
                                });
                            var searchSql = $("#salesListGrid").data('yxgrid').getAdvSql();
                            var searchArr = [];
                            searchArr[0] = searchSql;
                            searchArr[1] = searchConditionKey;
                            searchArr[2] = searchConditionVal;
                            window.open("?model=projectmanagent_trialproject_trialproject&action=exportExcel&colId="
                                + colId
                                + "&colName="
                                + colName
                                + "&searchConditionKey="
                                + searchConditionKey
                                + "&searchConditionVal="
                                + searchConditionVal)
                        }
                    }];
                }

                // �Ҽ�����Ȩ��
                if (data['����Ȩ��'] && data['����Ȩ��'] == 1) {
                    menuArr.push({
                        text: 'ȷ�����÷��ý��',
                        icon: 'add',
                        showMenuFn: function (row) {
                            if (!(row.serCon == "0" || row.serCon == "2") && (row.ExaStatus == "δ����" || row.ExaStatus == "���" ) && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            showModalWin('?model=projectmanagent_trialproject_trialproject&action=serConedit&id='
                                + row.id
                                + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                        }
                    }, {
                        text: '��ص���',
                        icon: 'delete',
                        showMenuFn: function (row) {
                            if (!(row.serCon == "0" || row.serCon == "2") && (row.ExaStatus == "δ����" || row.ExaStatus == "���" || row.serCon == "3") && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            if (window.confirm(("ȷ�������"))) {
                                showThickboxWin('?model=projectmanagent_trialproject_trialproject&action=toBackBill&id='
                                    + row.id
                                    + "&applyName="
                                    + row.applyName
                                    + "&serCon="
                                    + row.serCon
                                    + '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=700');
                            }
                        }
                    }, {
                        text: '������Ŀ',
                        icon: 'add',
                        showMenuFn: function (row) {
                            if ((row.ExaStatus == "���" && row.serCon == "1") && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            if (row.status != '2') {
                                alert('����ʧ�ܣ�ֻ�д�ִ�е���Ŀ����������Ŀ��');
                                return false;
                            }
                            showModalWin('?model=engineering_project_esmproject&action=toAddProject&contractId='
                                + row.id
                                + "&contractCode="
                                + row.projectCode
                                + "&contractType=GCXMYD-04"
                            );
                        }
                    }, {
                        text: '��������ȷ��',
                        icon: 'add',
                        showMenuFn: function (row) {
                            if (row.serCon == '3' && row.isFail == '0') {
                                return true;
                            }
                            return false;
                        },
                        action: function (row) {
                            showModalWin('?model=projectmanagent_trialproject_extension&action=toEdit&id='
                                + row.id
                                + "&skey="
                                + row['skey_']
                                + '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
                        }
                    });
                }
            }
        }
    });

    $("#salesListGrid").yxgrid({
        model: 'projectmanagent_trialproject_trialproject',
        action: 'trialprojectPageJson',
        title: '������Ŀ',
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        isOpButton: false,
        customCode: 'trialprojectGrid',
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '���ú�',
            sortable: true
        }, {
            name: 'projectName',
            display: '������Ŀ����',
            sortable: true
        }, {
            name: 'beginDate',
            display: '���ÿ�ʼʱ��',
            sortable: true,
            width: 80
        }, {
            name: 'closeDate',
            display: '���ý���ʱ��',
            sortable: true,
            width: 80
        }, {
            name: 'budgetMoney',
            display: 'Ԥ�ƽ��',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'affirmMoney',
            display: 'ȷ��Ԥ����',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'serCon',
            display: '�ύ״̬',
            sortable: true,
            process: function (v, row) {
                if (row.id == "noId") {
                    return '';
                }
                switch (v) {
                    case '0' :
                        return 'δ�ύ';
                        break;
                    case '1' :
                        return '���ύ';
                        break;
                    case '2' :
                        return '���';
                        break;
                    case '3' :
                        return '��������';
                        break;
                    case '4' :
                        return '����������';
                        break;
                    case '5' :
                        return '������������';
                        break;
                    default :
                        return v;
                }
            },
            width: 80
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true,
            width: 80
        }, {
            name: 'status',
            display: '��Ŀ״̬',
            sortable: true,
            process: function (v, row) {
                if (row.id == "noId") {
                    return '';
                }
                switch (v) {
                    case '0' :
                        if (row.serCon == '1') {
                            return '�ɱ�ȷ����';
                        } else {
                            return 'δ�ύ';
                        }
                    case '1' :
                        return '������';
                    case '2' :
                        return '��ִ��';
                    case '3' :
                        return 'ִ����';
                    case '4' :
                        return '�����';
                    case '5' :
                        return '�ѹر�';
                    default :
                        return v;
                }
            },
            width: 80
        }, {
            name: 'applyName',
            display: '������',
            sortable: true,
            width: 80
        }, {
            name: 'applyNameId',
            display: '������ID',
            sortable: true,
            hide: true
        }, {
            name: 'customerName',
            display: '�ͻ�����',
            sortable: true
        }, {
            name: 'customerType',
            display: '�ͻ�����Type',
            sortable: true,
            hide: true
        }, {
            name: 'customerTypeName',
            display: '�ͻ�����',
            sortable: true
        }, {
            name: 'customerWay',
            display: '�ͻ���ϵ��ʽ',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'province',
            display: 'ʡ��',
            sortable: true,
            hide: true
        }, {
            name: 'city',
            display: '����',
            sortable: true,
            hide: true
        }, {
            name: 'areaName',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'areaPrincipal',
            display: '��������',
            sortable: true,
            width: 80
        }, {
            name: 'areaPrincipalId',
            display: '��������Id',
            sortable: true,
            hide: true
        }, {
            name: 'areaCode',
            display: '�����ţ�ID��',
            sortable: true,
            hide: true
        }, {
            name: 'projectDescribe',
            display: '����Ҫ������',
            sortable: true,
            width: 100,
            hide: true
        }, {
            name: 'updateTime',
            display: '�޸�ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '�޸�������',
            sortable: true,
            hide: true
        }, {
            name: 'updateId',
            display: '�޸���Id',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '����ʱ��',
            sortable: true,
            hide: true
        }, {
            name: 'createName',
            display: '����������',
            sortable: true,
            hide: true
        }, {
            name: 'createId',
            display: '������ID',
            sortable: true,
            hide: true
        }, {
            name: 'contractCode',
            display: '��ͬ���',
            sortable: true,
            width: 120,
            process: function (v, row) {
                return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
                    + row.contractId
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
                    + v
                    + '</a>';
            }
        }, {
            name: 'contractId',
            display: '��ͬid',
            sortable: true,
            hide: true
        }, {
            name: 'isFail',
            display: '�Ƿ���Ч',
            sortable: true,
            process: function (v) {
                switch (v) {
                    case '0' :
                        return '��Ч';
                    case '1' :
                        return '��ת��ͬ';
                    case '2' :
                        return '�ֹ��ر�';
                    default :
                        return v;
                }
            },
            width: 80
        }, {
            name: 'turnStatus',
            display: 'ת��״̬',
            sortable: true,
            width: 80
        }, {
            name: 'turnDate',
            display: 'ת��ʱ��',
            sortable: true,
            width: 80
        }, {
            name: 'turnProject',
            display: 'ת����Ŀ���',
            sortable: true,
            process: function (v) {
                return initProjectCode(v);
            },
            width: 120
        }, {
            name: 'projectDays',
            display: '����',
            sortable: true,
            width: 80
        }, {
            name: 'actDate',
            display: '��ִ������',
            sortable: true,
            width: 80
        }, {
            name: 'budgetAll',
            display: 'Ԥ��',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'feeAllCount',
            display: '����',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            },
            width: 80
        }, {
            name: 'extensionNum',
            display: '���ڴ���',
            sortable: true,
            process: function (v) {
                return v + " ��"
            },
            width: 80
        }, {
            name: 'winRate',
            display: '�̻�Ӯ��',
            sortable: true,
            process: function (v) {
                return v + "%"
            },
            width: 80
        }],
        comboEx: [
            {
                text: '��Ŀ״̬',
                key: 'status',
                data: [{
                    text: 'δ�ύ/ȷ����',
                    value: '0'
                }, {
                    text: '������',
                    value: '1'
                }, {
                    text: '��ִ��',
                    value: '2'
                }, {
                    text: 'ִ����',
                    value: '3'
                }, {
                    text: '�����',
                    value: '4'
                //}, {
                //    text: '�ѹر�',
                //    value: '5'
                }]
            },{
                text : '�ύ״̬',
                key : 'serCon',
                data : [{
                    text : 'δ�ύ',
                    value : '0'
                }, {
                    text : '���ύ',
                    value : '1'
                }, {
                    text : '���',
                    value : '2'
                }, {
                    text : '��������',
                    value : '3'
                }, {
                    text : '����������',
                    value : '4'
                }, {
                    text : '������������',
                    value : '5'
                }]
            }, {
                text: 'ת��״̬',
                key: 'turnStatus',
                data: [{
                    text: '��',
                    value: '��'
                }, {
                    text: 'δת��',
                    value: 'δת��'
                }, {
                    text: '��ת��',
                    value: '��ת��'
                }]
            }
        ],
        buttonsEx: buttonsArr,
        // ��չ�Ҽ��˵�
        menusEx: menuArr,
        /**
         * ��������
         */
        searchitems: [{
            display: '��Ŀ���',
            name: 'projectCode'
        }, {
            display: '��Ŀ����',
            name: 'projectName'
        }, {
            display: '�ͻ�����',
            name: 'customerName'
        }, {
            display: '������',
            name: 'applyName'
        }]
    });
});