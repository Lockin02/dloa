var show_page = function () {
    $("#baseinfoGrid").yxgrid("reload");
};

$(function () {
    $("#baseinfoGrid").yxgrid({
        model: 'yxlicense_license_baseinfo',
        title: 'license������Ϣ',
        isEditAction: false,
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
                name: 'isUse',
                display: '����',
                sortable: true,
                width: 30,
                align: 'center',
                process: function (v, row) {
                    if (row.isUse == '1') {
                        return '<img src="images/icon/ok3.png" title="��Ч"/>';
                    }
                }
            },
            {
                name: 'name',
                display: '����',
                sortable: true,
                width: 150,
                process: function (v, row) {
                    return "<a href='javascript:void(0)' " +
                    "onclick='showModalWin(\"?model=yxlicense_license_category&action=preview&id=" + row.id
                    + "&licenseName=" + row.name + "\")'>" + v + "</a>";
                }
            },
            {
                name: 'remark',
                display: '��ע',
                sortable: true,
                width: 280
            },
            {
                name: 'createName',
                display: '������',
                sortable: true
            },
            {
                name: 'createTime',
                display: '��������',
                sortable: true,
                width: 130
            }
        ],
        toViewConfig: {
            formHeight: 200,
            formWidth: 400
        },
        menusEx: [
            {
                text: '�༭',
                icon: 'edit',
                action: function (row, rows, grid) {
                    $.post('?model=yxlicense_license_baseinfo&action=checkExists', {'id': row.id},
                        function (data) {
                            if (data == '1') {
                                alert('��ģ���ѱ�ʹ�ã��޷��༭���븴�ƺ��ٽ��б༭');
                            } else {
                                showThickboxWin("?model=yxlicense_license_baseinfo&action=init&id="
                                + row.id
                                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=850");
                            }
                        });
                }
            },
            {
                text: 'ɾ��',
                icon: 'delete',
                action: function (row, rows, grid) {
                    $.post('?model=yxlicense_license_baseinfo&action=checkExists', {'id': row.id},
                        function (data) {
                            if (data == '1') {
                                alert('��ģ���ѱ�ʹ�ã��޷�ɾ��');
                            } else {
                                if (confirm('ȷ��ɾ����')) {
                                    $.post('?model=yxlicense_license_baseinfo&action=ajaxdeletes',
                                        {id: row.id},
                                        function (data) {
                                            if (data == '1') {
                                                alert('ɾ���ɹ�');
                                                show_page();
                                            }
                                        }
                                    );
                                }
                            }
                        });
                }
            },
            {
                text: '����',
                icon: 'view',
                action: function (row, rows, grid) {
                    $.post('?model=yxlicense_license_baseinfo&action=checkExists', {'id': row.id},
                        function (data) {
                            if (data == '1') {
                                showModalWin("?model=yxlicense_license_category&action=page&id="
                                + row.id + "&name="
                                + row.name
                                + "&isUse=1");
                            } else {
                                showModalWin("?model=yxlicense_license_category&action=page&id="
                                + row.id + "&name="
                                + row.name);
                            }
                        });
                }
            },
            {
                text: '�ر�',
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.isUse == '1';
                },
                action: function (row, rows, grid) {
                    if (confirm("ȷ�Ϲر�ģ�壿")) {
                        $.post('?model=yxlicense_license_baseinfo&action=closeTemp', {'id': row.id},
                            function (data) {
                                if (data == '1') {
                                    show_page();
                                }
                            });
                    }
                }
            },
            {
                text: '����',
                icon: 'edit',
                showMenuFn: function (row) {
                    return row.isUse == '0';
                },
                action: function (row, rows, grid) {
                    var appendStr = row.nXmlFileName != "" ? "���Ƶ�license�Ḳ��ԭlicense�Ĺ�������" : "";
                    if (confirm(appendStr + "ȷ������license��")) {
                        $.post('?model=yxlicense_license_baseinfo&action=openTemp', {'id': row.id},
                            function (data) {
                                if (data == '1') {
                                    show_page();
                                }
                            });
                    }
                }
            },
            {
                text: 'Ԥ��',
                icon: 'view',
                action: function (row, rows, grid) {
                    showModalWin("?model=yxlicense_license_category&action=preview&id="
                        + row.id + "&licenseName=" + row.name
                    );
                }
            },
            {
                text: '����',
                icon: 'add',
                action: function (row, rows, grid) {
                    showThickboxWin("?model=yxlicense_license_baseinfo&action=toCopy&id="
                    + row.id
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=700");
                }
            },
            {
                text: '����',
                icon: 'excel',
                action: function (row, rows, grid) {
                    window.open(
                        "?model=yxlicense_license_baseinfo&action=exportExcel&id="
                        + row.id + "&licenseName=" + row.name,
                        "", "width=200,height=200,top=200,left=200");
                }

            }
        ],
        searchitems: [
            {
                display: "����",
                name: 'name'
            }
        ],
        //��������
        comboEx: [{
            text: '����',
            key: 'isUse',
            data: [{
                text: '��',
                value: '1'
            }, {
                text: '��',
                value: '0'
            }]
        }]
    });
});