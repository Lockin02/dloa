var show_page = function () {
    $("#productSelectGrid").yxsubgrid("reload");
};

/**
 * �鿴������ϸ��Ϣ
 * @param {} productId
 */
function viewProDetail(productId) {
    showThickboxWin("?model=stock_productinfo_productinfo&action=view&id="
        + productId
        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
}

$(function () {
    var showcheckbox = $("#showcheckbox").val();
    var showButton = $("#showButton").val();
    var combogrid = window.dialogArguments[0];
    var opener = window.dialogArguments[1];
    var p = combogrid.options;
    var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
    var titleVal = "<b>���ϵ�ѡ : ˫��ѡ������</b>";

    if (!showcheckbox) { // ����ǵ�ѡ���������ı���
        if (eventStr.row_dblclick) {
            var dbclickFunLast = eventStr.row_dblclick;
            eventStr.row_dblclick = function (e, row, data) {
                if (p.closeCheck && (data['ext1'] == "WLSTATUSGB")) {
                    alert("�����ѹر�,������ѡ��!");
                    return;
                } else if (p.closeAndStockCheck && data['ext1'] == "WLSTATUSGB") {
                    var checkResult = true;
                    $.ajax({// �������к�
                        type: "POST",
                        async: false,
                        url: "?model=stock_inventoryinfo_inventoryinfo&action=getSouceInvent",
                        dataType: "json",
                        data: {
                            "productId": data['id']
                        },
                        success: function (result) {
                            var tipStr = "����Ϊ�ر�״̬(" + result['closeReson'] + ")��\n";
                            if (result['stock'].length > 0) {
                                for (var i = 0; i < result['stock'].length; i++) {
                                    tipStr += result['stock'][i]['stockName']
                                        + "ʣ����Ϊ"
                                        + result['stock'][i]['actNum'] + "\n";
                                }
                            } else {
                                tipStr += "���û�д����ϣ�"
                            }
                            alert(tipStr);
                        }
                    });
                    if (!checkResult) {
                        return;
                    }
                }
                try {
                    dbclickFunLast(e, row, data);
                } catch (ex) {
                    try {
                        console.log(ex);
                    } catch (exx) {

                    }
                }
                window.returnValue = row.data('data');
                window.close();
            };
        } else {
            eventStr.row_dblclick = function (e, row, data) {
                window.returnValue = row.data('data');
                window.close();
            };
        }
    } else {// ��ѡ
        titleVal = "<b>���϶�ѡ : �빴ѡ��Ҫѡ�������</b>";
        var rowCheckFunLast = function () {
        };
        if (eventStr.row_check) {
            rowCheckFunLast = eventStr.row_check;
        }
        eventStr.row_check = function (e, checkbox, row, rowData) {
            var el = combogrid.el;
            if (p.hiddenId) {
                if (checkbox.attr('checked')) {
                    if (p.idArr.indexOf(rowData[p.valueCol]) == -1) {
                        p.idArr.push(rowData[p.valueCol]);
                        p.nameArr.push(rowData[p.nameCol]);
                    }
                } else {
                    // ���ֵ���ڣ�ɾ��������
                    var index = p.idArr.indexOf(rowData[p.valueCol]);
                    if (index != -1) {
                        p.idArr.splice(index, 1);
                        p.nameArr.splice(index, 1);
                    }
                }
                p.nameStr = p.nameArr.toString();
                p.idStr = p.idArr.toString();
                if (p.isShowName == true) {
                    opener.$("#" + el.attr('id')).val(p.nameStr);
                }
                opener.$("#" + p.hiddenId).val(p.idStr);
                // ѡ���ٷ��¼�
                row.trigger('after_row_check', [checkbox, row, rowData]);
            }
            rowCheckFunLast(e, checkbox, row, rowData);
        };
        var checkIds = $("#checkIds").val();
        eventStr.row_success = function (e, rows, g) {
            for (var i = 0, l = rows.size(); i < l; i++) {
                var rowData = $(rows[i]).data('data');
                var v = rowData[combogrid.options.valueCol];
                if (v) {
                    if (("," + checkIds + ",").indexOf("," + v + ",") != -1) {
                        var checkbox = g.getCheckboxByRow(rows[i]);
                        checkbox.trigger('click', [true]);
                    }
                }
            }
        }
    }

    //��ȡ��˾ѡ��
    var companyOptions = [];
    $.ajax({
        type: "POST",
        url: "?model=deptuser_branch_branch&action=listForSelect",
        async: false,
        success: function (data) {
            if (data != "") {
                data = eval("(" + data + ")");
                for (var i = 0; i < data.length; i++) {
                    companyOptions.push({
                        text: data[i].name,
                        value: data[i].value
                    });
                }
            }
        }
    });

    var gridOptions = combogrid.options.gridOptions;
    $("#productSelectGrid").yxsubgrid({
        model: 'stock_productinfo_productinfo',
        action: gridOptions.action,
        title: titleVal,
        isToolBar: true,
        isViewAction: false,
        isDelAction: false,
        isEditAction: false,
        isAddAction: false,
        showcheckbox: showcheckbox,
        param: gridOptions.param,
        pageSize: 15,
        imSearch: true,// ��ʱ����
        isOpButton: false,
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '״̬',
            name: 'ext1',
            process: function (v) {
                if (v == "WLSTATUSKF") {
                    return '<img src="images/icon/cicle_green.png" title="����"/>';
                } else {
                    return '<img src="images/icon/cicle_grey.png" title="�ر�"/>';
                }
            },
            align: 'center',
            width: 20
        }, {
            display: '���ϱ���',
            name: 'productCode',
            sortable: true,
            process: function (v, row) {
                return "<a title='" + row.remark + "' href='#' onclick='viewProDetail(" + row.id + ")' >" + v + "</a>";
            },
            width: 80
        }, {
            display: 'k3����',
            name: 'ext2',
            sortable: true,
            width: 70
        }, {
            display: '��������',
            name: 'productName',
            sortable: true,
            width: 180,
            process: function (v, row) {
                return "<div title='" + row.remark + "'>" + v + "</div>"
            }
        }, {
            display: '��������',
            name: 'proType',
            width: 80,
            sortable: true
        }, {
            name: 'pattern',
            display: '����ͺ�',
            sortable: true,
            width: 80
        }, {
            name: 'priCost',
            display: '����',
            sortable: true,
            hide: true
        }, {
            name: 'unitName',
            display: '��λ',
            hide: true,
            sortable: true,
            width: 50
        }, {
            name: 'aidUnit',
            display: '������λ',
            sortable: true,
            hide: true
        }, {
            name: 'warranty',
            display: '������(��)',
            hide: true,
            sortable: true
        }, {
            name: 'arrivalPeriod',
            display: '��������(��)',
            hide: true,
            sortable: true
        }, {
            name: 'accountingCode',
            display: '��ƿ�Ŀ����',
            sortable: true,
            datacode: 'KJKM',
            hide: true
        }, {
            name: 'remark',
            display: '��ע',
            process: function (v) {
                if (v.length > 10) {
                    return "<div title='" + v + "'>"
                        + v.substr(0, 40)
                        + "....</div>";
                }
                return v
            }
        }, {
            name: 'businessBelongName',
            display: '������˾',
            sortable: true,
            width: 60
        }],
        // ���ӱ������
        subGridOptions: {
            dblclickAutoLoad: false,
            url: '?model=stock_productinfo_configuration&action=itemJson',
            param: [{
                paramId: 'hardWareId',
                colId: 'id'
            }],
            colModel: [{
                name: 'configName',
                display: '��������',
                width: 200
            }, {
                name: 'configNum',
                width: 50,
                display: '����'
            }, {
                name: 'explains',
                width: 300,
                display: '˵��'
            }]
        },
        comboEx: [{
            text: '������˾',
            key: 'businessBelong',
            value: $("#businessBelong").val(),
            data: companyOptions
        }],
        searchitems: [{
            display: '���ϱ���',
            name: 'productCode'
        }, {
            display: '��������',
            name: 'productName'

        }, {
            display: '��������',
            name: 'ext3'
        }, {
            display: 'Ʒ��',
            name: 'brand'
        }, {
            display: '����ͺ�',
            name: 'pattern'
        }, {
            name: 'ext2',
            display: 'K3����'
        }],
        sortname: gridOptions.sortname,
        sortorder: gridOptions.sortorder,
        // ���¼����ƹ���
        event: eventStr
    });
});