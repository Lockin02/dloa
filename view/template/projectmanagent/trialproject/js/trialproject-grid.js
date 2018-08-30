var show_page = function (page) {
    $("#trialprojectGrid").yxgrid("reload");
};
$(function () {
    $("#trialprojectGrid").yxgrid({
        model: 'projectmanagent_trialproject_trialproject',
        action: "pageJsons",
        title: '������Ŀ',
        param: {
            'serConArr': '1,3,4',
            'isFail': '0'
        },
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        showcheckbox: false,
        isAddAction: false,
        customCode: 'trialprojectGrid',
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '������Ŀ���',
            sortable: true
        }, {
            name: 'projectName',
            display: '������Ŀ����',
            sortable: true
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
            }
        }, {
            name: 'beginDate',
            display: '���ÿ�ʼʱ��',
            sortable: true
        }, {
            name: 'closeDate',
            display: '���ý���ʱ��',
            sortable: true
        }, {
            name: 'budgetMoney',
            display: 'Ԥ�ƽ��',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'affirmMoney',
            display: 'ȷ�����÷��ý��',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            name: 'ExaStatus',
            display: '����״̬',
            sortable: true
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
                            break;
                        } else {
                            return 'δ�ύ';
                            break;
                        }
                    case '1' :
                        return '������';
                        break;
                    case '2' :
                        return '��ִ��';
                        break;
                    case '3' :
                        return 'ִ����';
                        break;
                    case '4' :
                        return '�����';
                        break;
                    case '5' :
                        return '�ѹر�';
                        break;
                    default :
                        return v;
                }
            }
        }, {
            name: 'applyName',
            display: '������',
            sortable: true
        }, {
            name: 'projectProcess',
            display: '��Ŀ����',
            sortable: true,
            process: function (v) {
                return moneyFormat2(v) + ' %';
            }
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
            sortable: true
        }, {
            name: 'areaPrincipal',
            display: '��������',
            sortable: true
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
            name: 'isFail',
            display: '�Ƿ���Ч',
            sortable: true,
            process: function (v, row) {
                switch (v) {
                    case '0' :
                        return '��Ч';
                        break;
                    case '1' :
                        return '��ת��ͬ';
                        break;
                    case '2' :
                        return '�ֹ��ر�';
                        break;
                    default :
                        return v;
                }
            }
        }, {
            name: 'productLine',
            display: 'ִ������',
            sortable: true,
            datacode: 'GCSCX',
            hide: true
        }],
        // ��չ�Ҽ��˵�
        menusEx: [{
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
                if (row.ExaStatus == '��������' && row.isFail == '0') {
                    return true;
                }
                return false;
            },
            action: function (row) {

                showThickboxWin('controller/projectmanagent/trialproject/readview.php?itemtype=oa_trialproject_trialproject&pid='
                    + row.id
                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
            }
        }, {
            text: 'ȷ�����÷��ý��',
            icon: 'add',
            showMenuFn: function (row) {
                if ((row.ExaStatus == "δ����" || row.ExaStatus == "���" ) && row.isFail == '0') {
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
                if ((row.ExaStatus == "δ����" || row.ExaStatus == "���" || row.serCon == "3") && row.isFail == '0') {
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
        }],
        comboEx: [{
            text: '����״̬',
            key: 'ExaStatus',
            data: [{
                text: 'δ����',
                value: 'δ����'
            }, {
                text: '��������',
                value: '��������'
            }, {
                text: '���',
                value: '���'
            }, {
                text: '���',
                value: '���'
            }]
        }, {
            text: 'ȷ��״̬',
            key: 'ExaStatusArr',
            data: [{
                text: 'δȷ��',
                value: 'δ����,���'
            }, {
                text: '��ȷ��',
                value: '��������,���'
            }]
        }, {
            text: '�ύ״̬',
            key: 'serCon',
            data: [{
                text: 'δ�ύ',
                value: '0'
            }, {
                text: '���ύ',
                value: '1'
            }, {
                text: '���',
                value: '2'
            }, {
                text: '��������',
                value: '3'
            }, {
                text: '����������',
                value: '4'
            }, {
                text: '������������',
                value: '5'
            }]
        }],
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