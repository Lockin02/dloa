var show_page = function (page) {
    $("#templateGrid").yxgrid("reload");
};

$(function () {
    var licenseArr = {};
    $.ajax({
        url : '?model=yxlicense_license_baseinfo&action=getLicenseAll',
        type : "POST",
        async : false,
        success: function(data) {
            data = eval("(" + data + ")");
            for (var i = 0; i < data.length; i++) {
                licenseArr[data[i].value] = data[i].name;
            }
        }
    });

    $("#templateGrid").yxgrid({
        model: 'yxlicense_license_template',
        title: 'licenseģ��',
        sortname: "updateTime",
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'licenseId',
            display: '����licenseid',
            sortable: true,
            hide: true
        }, {
            name: 'name',
            display: 'ģ������',
            sortable: true,
            width: 150,
            process: function (v, row) {
                return "<a href='javascript:void(0)' " +
                "onclick='showModalWin(\"?model=yxlicense_license_template&action=init&perm=view&id=" +
                row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
        }, {
            name: 'licenseType',
            display: 'ģ������',
            sortable: true,
            process: function (v) {
                var thisType = "";
                switch (v) {
                    case 'PIO' :
                        thisType = 'Pioneer';
                        break;
                    case 'NAV' :
                        thisType = 'Navigator';
                        break;
                    case 'FL2' :
                        thisType = 'Fleet';
                        break;
                    case 'FL' :
                        thisType = 'Fleet';
                        break;
                    case 'RCU' :
                        thisType = 'RCU';
                        break;
                    case 'WT' :
                        thisType = 'Walktour';
                        break;
                    case 'WISER' :
                        thisType = 'Wiser';
                        break;
                    default :
                        return licenseArr[v] ? licenseArr[v] : v;
                }
                return thisType;
            },
            width: 120
        }, {
            name: 'status',
            display: '״̬',
            sortable: true,
            process: function (v) {
                if (v == 0) {
                    return '����';
                } else {
                    return '����';
                }
            },
            width: 80
        }, {
            name: 'createName',
            display: '������',
            sortable: true
        }, {
            name: 'createTime',
            display: '��������',
            sortable: true,
            width: 130
        }, {
            name: 'updateName',
            display: '�޸���',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '�޸�����',
            sortable: true,
            width: 130
        }, {
            name: 'remark',
            display: '��ע',
            sortable: true,
            width: 200
        }],
        toAddConfig: {
            toAddFn: function (p) {
                showModalWin("?model=yxlicense_license_template&action=toAdd", 1);
            }
        },
        toViewConfig: {
            toViewFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=yxlicense_license_template&action=init&perm=view&id=" + +rowData[p.keyField], 1);
            }
        },
        toEditConfig: {
            toEditFn: function (p, g) {
                var rowObj = g.getSelectedRow();
                var rowData = rowObj.data('data');
                showModalWin("?model=yxlicense_license_template&action=init&id=" + +rowData[p.keyField], 1);
            }
        },
        comboEx: [{
            text: '����',
            key: 'licenseType',
            data: [{
                text: 'Pioneer',
                value: 'PIO'
            }, {
                text: 'Navigator',
                value: 'NAV'
            }, {
                text: 'Fleet',
                value: 'FL'
            }, {
                text: 'RCU',
                value: 'RCU'
            }, {
                text: 'Walktour',
                value: 'Walktour'
            }
            ]
        }],
        searchitems: [{
            display: 'ģ������',
            name: 'name'
        }, {
            display: '������',
            name: 'createName'
        }]
    });
});