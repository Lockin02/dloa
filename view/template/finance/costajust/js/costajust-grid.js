var show_page = function () {
    $("#costajustGrid").yxgrid("reload");
};

$(function () {
    $("#costajustGrid").yxgrid({
        model: 'finance_costajust_costajust',
        title: '�ɱ�������',
        isDelAction: false,
        isEditAction: false,
        //����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'stockbalId',
                display: '�ڳ����id',
                hide: true,
                process: function (v, row) {
                    return v + "<input type='hidden' id='isLater" + row.id + "' value='unde'/>";
                }
            },
            {
                name: 'formNo',
                display: '���ݺ�',
                sortable: true,
                width: 140
            },
            {
                name: 'formType',
                display: '��������',
                sortable: true,
                datacode: 'CBTZ'
            },
            {
                name: 'formDate',
                display: '����',
                sortable: true
            },
            {
                name: 'stockName',
                display: '�ֿ�����',
                sortable: true
            },
            {
                name: 'deptName',
                display: '����',
                sortable: true
            },
            {
                name: 'salesman',
                display: 'ҵ��Ա',
                sortable: true
            },
            {
                name: 'createName',
                display: '����������',
                sortable: true
            },
            {
                name: 'createTime',
                display: '����ʱ��',
                sortable: true,
                width: 140
            }
        ],
        toAddConfig: {
            formWidth: 900,
            formHeight: 500
        },
        toViewConfig: {
            formWidth: 900,
            formHeight: 500
        },
        // ��չ�б�ť
        buttonsEx: [
            {
                text: '����',
                icon: 'excel',
                action: function () {
                    showThickboxWin("?model=finance_costajust_costajust&action=toImport"
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=600")
                }
            }
        ],
        menusEx: [
            {
                text: "�༭",
                icon: 'edit',
                showMenuFn: function (row) {
                    var thisLater = $("#isLater" + row.id);
                    if (thisLater.val() == 'unde') {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_period_period&action=isLaterPeriod",
                            data: {"thisDate": row.formDate },
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    thisLater.val(1);
                                } else {
                                    thisLater.val(0);
                                }
                            }
                        });
                    }
                    if (thisLater.val() == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    showThickboxWin("?model=finance_costajust_costajust"
                        + "&action=init"
                        + "&id="
                        + row.id
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
                        + "&width=900");
                }
            },
            {
                text: "ɾ��",
                icon: 'delete',
                showMenuFn: function (row) {
                    var thisLater = $("#isLater" + row.id);
                    if (thisLater.val() == 'unde') {
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_period_period&action=isLaterPeriod",
                            data: {"thisDate": row.formDate },
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    thisLater.val(1);
                                } else {
                                    thisLater.val(0);
                                }
                            }
                        });
                    }
                    if (thisLater.val() == 1) {
                        return true;
                    }
                    return false;
                },
                action: function (row) {
                    if (window.confirm(("ȷ��Ҫɾ��?"))) {
                        if (row.stockbalId != "") {
                            $.ajax({
                                type: "POST",
                                url: "?model=finance_costajust_costajust&action=deleteChange",
                                data: {
                                    "id": row.id,
                                    "stockbalId": row.stockbalId
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('ɾ���ɹ���');
                                        show_page();
                                    } else {
                                        alert('ɾ��ʧ��');
                                    }
                                }
                            });
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "?model=finance_costajust_costajust&action=ajaxdeletes",
                                data: {
                                    id: row.id
                                },
                                success: function (msg) {
                                    if (msg == 1) {
                                        alert('ɾ���ɹ���');
                                        show_page();
                                    } else {
                                        alert('ɾ��ʧ��');
                                    }
                                }
                            });
                        }
                    }
                }
            }
        ]
    });
});