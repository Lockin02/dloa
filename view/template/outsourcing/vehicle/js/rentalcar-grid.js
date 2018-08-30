var show_page = function () {
    $("#rentalcarGrid").yxgrid("reload");
};

$(function () {
    var buttonsArr = [];
    var excelOutCustom = {
        name: 'exportOut',
        text: "����",
        icon: 'excel',
        action: function () {
            showThickboxWin("?model=outsourcing_vehicle_rentalcar&action=toExcelOutCustom"
                + "&createId=" + $("#createId").val()
                + "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=800");
        }
    };
    $.ajax({
        type: 'POST',
        url: '?model=outsourcing_vehicle_rentalcar&action=getLimits',
        data: {
            'limitName': '����Ȩ��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {
                buttonsArr.push(excelOutCustom);
            }
        }
    });

    $("#rentalcarGrid").yxgrid({
        model: 'outsourcing_vehicle_rentalcar',
        param: {
            'createId': $("#createId").val()
        },
        title: '�⳵����',
        bodyAlign: 'center',
        showcheckbox: false,
        isDelAction: false,
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'formCode',
                display: '���ݱ��',
                sortable: true,
                width: 160,
                process: function (v, row) {
                    return "<a href='#' onclick='showModalWin(\"?model=outsourcing_vehicle_rentalcar&action=toView&id=" + row.id + "\",1)'>" + v + "</a>";
                }
            },
            {
                name: 'ExaStatus',
                display: '����״̬',
                sortable: true,
                width: 60,
                process: function (v) {
                    if (v == 0) {
                        return 'δ�ύ';
                    }
                    return v;
                }
            },
            {
                name: 'projectCode',
                display: '��Ŀ���',
                sortable: true,
                width: 200
            },
            {
                name: 'projectName',
                display: '��Ŀ����',
                sortable: true,
                width: 200
            },
            {
                name: 'projectType',
                display: '��Ŀ����',
                sortable: true,
                width: 60
            },
            {
                name: 'rentalProperty',
                display: '�⳵����',
                sortable: true,
                width: 60
            },
            {
                name: 'createName',
                display: '������',
                sortable: true,
                width: 80
            },
            {
                name: 'createTime',
                display: '����ʱ��',
                sortable: true,
                width: 130
            },
            {
                name: 'applicantPhone',
                display: '�����˵绰',
                sortable: true,
                width: 80
            },
            {
                name: 'province',
                display: '�ó��ص�',
                sortable: true,
                process: function (v, row) {
                    if (row.provinceId == 43) { //CDMA�Ŷ�
                        return row.usePlace;
                    } else {
                        return v + "-" + row.city;
                    }
                }
            },
            {
                name: 'useCarAmount',
                display: '�ó�����',
                sortable: true,
                width: 50
            },
            {
                name: 'expectStartDate',
                display: 'Ԥ�ƿ�ʼ�ó�ʱ��',
                sortable: true
            },
            {
                name: 'useCycle',
                display: '�ó�����',
                sortable: true
            }
        ],

        buttonsEx: buttonsArr,

        menusEx: [
            {
                text: 'ɾ��',
                icon: 'delete',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '0' || row.ExaStatus == '���') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        $.ajax({
                            type: "POST",
                            url: "?model=outsourcing_vehicle_rentalcar&action=ajaxdeletes",
                            data: {
                                id: row.id
                            },
                            success: function (msg) {
                                if (msg == 1) {
                                    alert('ɾ���ɹ���');
                                    $("#rentalcarGrid").yxgrid("reload");
                                }
                            }
                        });
                    }
                }
            },
            {
                text: "�ύ����",
                icon: 'add',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '0' || row.ExaStatus == '���') {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    $.ajax({
                        type: "POST",
                        url: "?model=outsourcing_vehicle_rentalcar&action=getOfficeInfoForId",
                        data: {
                            'projectId': row.projectId
                        },
                        async: false,
                        success: function (data) {
                            if (data) {
                                showThickboxWin('controller/outsourcing/vehicle/ewf_index.php?actTo=ewfSelect&billId='
                                    + row.id + "&billArea=" + data + "&flowMoney=" + row.estimateAmonut
                                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            } else {
                                showThickboxWin('controller/outsourcing/vehicle/ewf_index.php?actTo=ewfSelect&billId='
                                    + row.id + "&flowMoney=" + row.estimateAmonut
                                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            }
                        }
                    });
                }
            },
            {
                name: 'aduit',
                text: '�������',
                icon: 'view',
                showMenuFn: function (row) {
                    if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
                        return true;
                    }
                    return false;
                },
                action: function (row, rows, grid) {
                    if (row) {
                        showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_rentalcar&pid="
                            + row.id
                            + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                    }
                }
            }
        ],

        comboEx: [
            {
                text: "����״̬",
                key: 'ExaStatus',
                data: [
                    {
                        text: 'δ�ύ',
                        value: '0'
                    },
                    {
                        text: '��������',
                        value: '��������'
                    },
                    {
                        text: '���',
                        value: '���'
                    },
                    {
                        text: '���',
                        value: '���'
                    }
                ]
            },
            {
                text: "�⳵����",
                key: 'rentalPropertyCode',
                datacode: 'WBZCXZ'
            }
        ],

        toAddConfig: {
            toAddFn: function (p, g) {
                showModalWin("?model=outsourcing_vehicle_rentalcar&action=toAdd", '1');
            }
        },
        toEditConfig: {
            showMenuFn: function (row) {
                if (row.ExaStatus == 0 || row.ExaStatus == "���") {
                    return true;
                }
                return false;
            },
            toEditFn: function (p, g) {
                if (g) {
                    var get = g.getSelectedRow().data('data');
                    showModalWin("?model=outsourcing_vehicle_rentalcar&action=toEdit&id=" + get[p.keyField], '1');
                }
            }
        },
        toViewConfig: {
            toViewFn: function (p, g) {
                if (g) {
                    var get = g.getSelectedRow().data('data');
                    showModalWin("?model=outsourcing_vehicle_rentalcar&action=toView&id=" + get[p.keyField], '1');
                }
            }
        },
        searchitems: [
            {
                display: "���ݱ��",
                name: 'formCode'
            },
            {
                display: "��Ŀ���",
                name: 'projectCode'
            },
            {
                display: "��Ŀ����",
                name: 'projectName'
            },
            {
                display: "��Ŀ����",
                name: 'projectType'
            },
            {
                display: "������",
                name: 'createNameSea'
            },
            {
                display: "����ʱ��",
                name: 'createTimeSea'
            },
            {
                display: "����绰",
                name: 'applicantPhone'
            },
            {
                display: "�ó��ص�",
                name: 'useCarPlace'
            },
            {
                display: "�ó�����",
                name: 'useCarAmountSea'
            },
            {
                display: "Ԥ�ƿ�ʼ�ó�ʱ��",
                name: 'expectStartDateSea'
            },
            {
                display: "�ó�����",
                name: 'useCycleSea'
            }
        ]
    });
});