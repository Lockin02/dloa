var show_page = function () {
    $("#productSelectGrid").yxsubgrid("reload");
};

/**
 * 查看物料详细信息
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
    var titleVal = "<b>物料单选 : 双击选择物料</b>";

    if (!showcheckbox) { // 如果是单选，则隐藏文本域
        if (eventStr.row_dblclick) {
            var dbclickFunLast = eventStr.row_dblclick;
            eventStr.row_dblclick = function (e, row, data) {
                if (p.closeCheck && (data['ext1'] == "WLSTATUSGB")) {
                    alert("物料已关闭,不可以选择!");
                    return;
                } else if (p.closeAndStockCheck && data['ext1'] == "WLSTATUSGB") {
                    var checkResult = true;
                    $.ajax({// 缓存序列号
                        type: "POST",
                        async: false,
                        url: "?model=stock_inventoryinfo_inventoryinfo&action=getSouceInvent",
                        dataType: "json",
                        data: {
                            "productId": data['id']
                        },
                        success: function (result) {
                            var tipStr = "物料为关闭状态(" + result['closeReson'] + ")：\n";
                            if (result['stock'].length > 0) {
                                for (var i = 0; i < result['stock'].length; i++) {
                                    tipStr += result['stock'][i]['stockName']
                                        + "剩余库存为"
                                        + result['stock'][i]['actNum'] + "\n";
                                }
                            } else {
                                tipStr += "库存没有此物料！"
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
    } else {// 多选
        titleVal = "<b>物料多选 : 请勾选需要选择的物料</b>";
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
                    // 如果值存在，删除数组项
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
                // 选择后促发事件
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

    //获取公司选项
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
        imSearch: true,// 即时搜索
        isOpButton: false,
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            display: '状态',
            name: 'ext1',
            process: function (v) {
                if (v == "WLSTATUSKF") {
                    return '<img src="images/icon/cicle_green.png" title="开放"/>';
                } else {
                    return '<img src="images/icon/cicle_grey.png" title="关闭"/>';
                }
            },
            align: 'center',
            width: 20
        }, {
            display: '物料编码',
            name: 'productCode',
            sortable: true,
            process: function (v, row) {
                return "<a title='" + row.remark + "' href='#' onclick='viewProDetail(" + row.id + ")' >" + v + "</a>";
            },
            width: 80
        }, {
            display: 'k3编码',
            name: 'ext2',
            sortable: true,
            width: 70
        }, {
            display: '物料名称',
            name: 'productName',
            sortable: true,
            width: 180,
            process: function (v, row) {
                return "<div title='" + row.remark + "'>" + v + "</div>"
            }
        }, {
            display: '所属分类',
            name: 'proType',
            width: 80,
            sortable: true
        }, {
            name: 'pattern',
            display: '规格型号',
            sortable: true,
            width: 80
        }, {
            name: 'priCost',
            display: '单价',
            sortable: true,
            hide: true
        }, {
            name: 'unitName',
            display: '单位',
            hide: true,
            sortable: true,
            width: 50
        }, {
            name: 'aidUnit',
            display: '辅助单位',
            sortable: true,
            hide: true
        }, {
            name: 'warranty',
            display: '保修期(月)',
            hide: true,
            sortable: true
        }, {
            name: 'arrivalPeriod',
            display: '到货周期(月)',
            hide: true,
            sortable: true
        }, {
            name: 'accountingCode',
            display: '会计科目代码',
            sortable: true,
            datacode: 'KJKM',
            hide: true
        }, {
            name: 'remark',
            display: '备注',
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
            display: '归属公司',
            sortable: true,
            width: 60
        }],
        // 主从表格设置
        subGridOptions: {
            dblclickAutoLoad: false,
            url: '?model=stock_productinfo_configuration&action=itemJson',
            param: [{
                paramId: 'hardWareId',
                colId: 'id'
            }],
            colModel: [{
                name: 'configName',
                display: '配置名称',
                width: 200
            }, {
                name: 'configNum',
                width: 50,
                display: '数量'
            }, {
                name: 'explains',
                width: 300,
                display: '说明'
            }]
        },
        comboEx: [{
            text: '归属公司',
            key: 'businessBelong',
            value: $("#businessBelong").val(),
            data: companyOptions
        }],
        searchitems: [{
            display: '物料编码',
            name: 'productCode'
        }, {
            display: '物料名称',
            name: 'productName'

        }, {
            display: '归属类型',
            name: 'ext3'
        }, {
            display: '品牌',
            name: 'brand'
        }, {
            display: '规格型号',
            name: 'pattern'
        }, {
            name: 'ext2',
            display: 'K3编码'
        }],
        sortname: gridOptions.sortname,
        sortorder: gridOptions.sortorder,
        // 把事件复制过来
        event: eventStr
    });
});