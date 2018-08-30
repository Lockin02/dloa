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
        title: 'license模板',
        sortname: "updateTime",
        //列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'licenseId',
            display: '关联licenseid',
            sortable: true,
            hide: true
        }, {
            name: 'name',
            display: '模板名称',
            sortable: true,
            width: 150,
            process: function (v, row) {
                return "<a href='javascript:void(0)' " +
                "onclick='showModalWin(\"?model=yxlicense_license_template&action=init&perm=view&id=" +
                row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
        }, {
            name: 'licenseType',
            display: '模板类型',
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
            display: '状态',
            sortable: true,
            process: function (v) {
                if (v == 0) {
                    return '保存';
                } else {
                    return '启用';
                }
            },
            width: 80
        }, {
            name: 'createName',
            display: '创建人',
            sortable: true
        }, {
            name: 'createTime',
            display: '创建日期',
            sortable: true,
            width: 130
        }, {
            name: 'updateName',
            display: '修改人',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '修改日期',
            sortable: true,
            width: 130
        }, {
            name: 'remark',
            display: '备注',
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
            text: '类型',
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
            display: '模板名称',
            name: 'name'
        }, {
            display: '创建人',
            name: 'createName'
        }]
    });
});