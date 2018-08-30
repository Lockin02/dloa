$(document).ready(function () {
    // 新增分类信息 选择物料类型
    $("#goodsTypeName").yxcombotree({
        hiddenId: 'goodsTypeId',
        treeOptions: {
            url: "?model=goods_goods_goodstype&action=getTreeData"
        }
    });
    // 表单验证
    validate({
        "goodsName": {
            required: true
        },
        "exeDeptName": {
            required: true
        }
    });
    // 审批部门
    $("#auditDeptName").yxselect_dept({
        hiddenId: 'auditDeptCode',
        mode: 'check'
    });

    // 初始化属性
    initProperties();

    // 初始化属性的选项
    initItem();

    // 初始化其他属性
    initOther();
});

// 初始化属性
function initProperties() {
    $("#properties").yxeditgrid({
        objName: 'properties[items]',
        isAddOneRow: true,
        colModel: [
            {
                name: 'staticAssitem',
                display: '属性配置',
                type: 'statictext',
                event: {
                    'click': function (e) {
                        $(this).find("input").attr('checked',true);
                        initItem();
                    }
                },
                html: '<input type="radio" name="itemSet" class="txt_btn_a"/>'
            },
            {
                name: 'itemContent',
                tclass: 'txt',
                display: '属性名称',
                validation: {
                    required: true
                }
            },
            {
                name: 'isNeed',
                display: '排序',
                tclass: 'txtshort'
            },
            {
                name: 'isDefault',
                display: '配置项类型',
                type: 'select',
                tclass: 'txtshort',
                options: [
                    {
                        name: "单项选择",
                        value: 'TC'
                    },
                    {
                        name: "多项选择",
                        value: 'ZC1'
                    },
                    {
                        name: "文本输入",
                        value: 'ZC2'
                    }
                ]
            },
            {
                name: 'isDefault',
                display: '至少选中<br/>一项',
                type: 'checkbox',
                width : 60
            },
            {
                name: 'isDefault',
                display: '允许直接<br/>输入值',
                type: 'checkbox',
                width : 60
            },
            {
                name: 'isDefault',
                display: '允许输入<br/>数量',
                type: 'checkbox',
                width : 60
            },
            {
                name: 'remark',
                tclass: 'txt',
                display: '备注'
            }
        ]
    });

    $("#properties").yxeditgrid('setRowColValue',0,'itemContent','软件').yxeditgrid('getCmpByRowAndCol',0,'staticAssitem').find("input").attr('checked',true);;
    $("#properties").yxeditgrid('addRow',1).yxeditgrid('setRowColValue',1,'itemContent','自定义内容').yxeditgrid('setRowColValue',1,'isDefault','ZC2');
}

// 初始化属性内的选项
function initItem() {
    $("#itemTable").yxeditgrid('remove').yxeditgrid({
        objName: 'properties[items]',
        isAddOneRow: true,
        colModel: [
            {
                name: 'itemContent',
                display: '值内容',
                tclass: 'txtmiddle',
                validation: {
                    required: true
                }
            },
            {
                name: 'isNeed',
                display: '是否<br/>必选',
                type: 'checkbox',
                width : 30
            },
            {
                type: 'checkbox',
                display: '是否<br/>默认',
                name: 'isDefault',
                width : 30
            },
            {
                name: 'defaultNum',
                display: '数量',
                tclass: 'txtmin'
            },
            {
                name: 'productId',
                display: '产品id',
                type: "hidden"
            },
            {
                name: 'productCode',
                display: '对应物料编号',
                width : 70,
                process: function ($input, rowData) {
                    var rowNum = $input.data("rowNum");
                    var g = $input.data("grid");
                    $input.yxcombogrid_product({
                        hiddenId: 'itemTable_cmp_productId'
                            + rowNum,
                        nameCol: 'productCode',
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum,'productName').val(rowData.productName);
                                        g.getCmpByRowAndCol(rowNum,'pattern').val(rowData.pattern);
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            },
            {
                name: 'productName',
                display: '对应物料名称',
                tclass: 'txtmiddle',
                process: function ($input, rowData) {
                    var rowNum = $input.data("rowNum");
                    var g = $input.data("grid");
                    $input.yxcombogrid_product({
                        hiddenId: 'itemTable_cmp_productId'
                            + rowNum,
                        nameCol: 'productName',
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum,'productCode').val(rowData.productCode);
                                        g.getCmpByRowAndCol(rowNum,'pattern').val(rowData.pattern);
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            },
            {
                name: 'pattern',
                display: '对应物料型号',
                type : 'hidden'
            },
            {
                name: 'proNum',
                display: '对应物料数量',
                tclass: 'txtmin'
            },
            {
                name: 'status',
                display: '状态',
                type: 'select',
                width : 60,
                tclass: 'txtshort',
                options: [
                    {
                        name: "在产",
                        value: 'ZC'
                    },
                    {
                        name: "停产",
                        value: 'TC'
                    }
                ]
            },
            {
                name: 'isDefault',
                display: 'license<br/>必选',
                type: 'checkbox',
                width : 40
            },
            {
                name: 'licenseTypeName',
                display: 'license类型',
                type: 'hidden'
            },
            {
                name: 'licenseTypeCode',
                display: 'license类型',
                type: 'select',
                width : 100,
                options: [
                    {
                        value: "",
                        name: "无license"
                    },
                    {
                        value: "PIO",
                        name: "Pioneer"
                    },
                    {
                        value: "NAV",
                        name: "Navigator"
                    },
                    {
                        value: "Pioneer-Navigator",
                        name: "Pioneer-Navigator"
                    },
                    {
                        value: "WT",
                        name: "Walktour"
                    },
                    {
                        value: "Walktour Pack-Ipad",
                        name: "Walktour Pack-Ipad"
                    },
                    {
                        value: "FL2",
                        name: "Fleet"
                    }
                ],
                event: {
                    change: function (e) {
                        var rowNum = e.data.rowNum;
                        var g = e.data.gird;
                        var $cmp = g.getCmpByRowAndCol(rowNum,
                            'licenseTypeName');
                        var name = $(this).find("option:selected").text();
                        $cmp.val(name);
                        $.ajax({
                            type: "POST",
                            url: "?model=yxlicense_license_template&action=getTemplateByType",
                            data: {
                                'licenseType': $(this).val()
                            },
                            async: false,
                            success: function (data) {
                                var $cmp = g.getCmpByRowAndCol(rowNum,
                                    'licenseTemplateId');
                                $cmp.children().remove();
                                $cmp
                                    .append("<option value=''>请选择</option>");
                                dataRows = eval('(' + data + ')');
                                for (var i = 0, l = dataRows.length; i < l; i++) {
                                    $cmp.append("<option  value='"
                                        + dataRows[i].thisVal + "'>"
                                        + dataRows[i].name
                                        + "</option>");
                                }
                            }
                        });
                    }
                }
            },
            {
                name: 'licenseTemplateId',
                display: 'license模板',
                type: 'select',
                width : 80,
                options: []
            },
            {
                name: 'rkey',
                display: '描述标识',
                type: "hidden"
            },
            {
                name: 'remark',
                display: '描述',
                type: "hidden"
            },
            {
                name: 'staticRemark',
                display: '具体描述按钮',
                type: 'statictext',
                event: {
                    'click': function (e) {
                        var rowNum = $(this).data("rowNum");
                        var g = $(this).data("grid");
                        var rowData = $(this).data("rowData");
                        window.open(
                                "?model=goods_goods_properties&action=toEditRemark&rowNum="
                                    + rowNum
                                    + "&remark="
                                    + $("#itemTable_cmp_remark"
                                    + rowNum).val()
                                    + "&rkey="
                                    + $("#itemTable_cmp_rkey"
                                    + rowNum).val(),
                                '描述信息编辑',
                                'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="编 辑"  class="txt_btn_a"  />'
            },
            {
                name: 'assitem',
                display: '数据项关联',
                type: "hidden"
            },
            {
                name: 'assitemIdStr',
                display: '数据项Id关联',
                type: "hidden"
            },
            {
                name: 'assitemTipStr',
                display: '数据项Tip关联',
                type: "hidden"
            },
            {
                name: 'staticAssitem',
                display: '数据项关联',
                type: 'hidden',
                event: {
                    'click': function (e) {
                        var rowNum = $(this).data("rowNum");
                        var g = $(this).data("grid");
                        var rowData = $(this).data("rowData");
                        window
                            .open(
                                "?model=goods_goods_properties&action=toSetAssItem&goodsId="
                                    + $("#mainId").val()
                                    + "&orderNum="
                                    + $("#orderNum").val()
                                    + "&assitem="
                                    + $("#itemTable_cmp_assitem")
                                    .val()
                                    + "&rowNum="
                                    + rowNum
                                    + "&assItemIdStr="
                                    + $("#itemTable_cmp_assitemIdStr"
                                    + rowNum).val()
                                    + "&assitemTipStr="
                                    + $("#itemTable_cmp_assitemTipStr"
                                    + rowNum).val(),
                                '数据项关联',
                                'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="设 置"  class="txt_btn_a"  />'
            }
        ]
    });

    $("#itemTable").yxeditgrid('setRowColValue',0,'itemContent','Samsung Galaxy SIII SGH-i747');
    $("#itemTable").yxeditgrid('addRow',1).yxeditgrid('setRowColValue',1,'itemContent','iPhone5 GSM1428');
    $("#itemTable").yxeditgrid('addRow',2).yxeditgrid('setRowColValue',2,'itemContent','iPhone4SW');
}

// 初始化属性内的选项
function initOther() {
    $("#otherTable").yxeditgrid('remove').yxeditgrid({
        objName: 'properties[items]',
        isAddOneRow: true,
        colModel: [
            {
                name: 'itemContent',
                tclass: 'txtmiddle',
                display: '值内容',
                validation: {
                    required: true
                }
            },
            {
                name: 'isNeed',
                display: '是否<br/>必选',
                type: 'checkbox',
                width : 30
            },
            {
                name: 'isDefault',
                display: '是否<br/>默认',
                type: 'checkbox',
                width : 30
            },
            {
                name: 'defaultNum',
                display: '数量',
                tclass: 'txtmin'
            },
            {
                name: 'productId',
                display: '产品id',
                type: "hidden"
            },
            {
                name: 'productCode',
                display: '对应物料编号',
                width : 70,
                process: function ($input, rowData) {
                    var rowNum = $input.data("rowNum");
                    var g = $input.data("grid");
                    $input.yxcombogrid_product({
                        hiddenId: 'otherTable_cmp_productId'
                            + rowNum,
                        nameCol: 'productCode',
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum,'productName').val(rowData.productName);
                                        g.getCmpByRowAndCol(rowNum,'pattern').val(rowData.pattern);
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            },
            {
                name: 'productName',
                display: '对应物料名称',
                tclass: 'txtmiddle',
                process: function ($input, rowData) {
                    var rowNum = $input.data("rowNum");
                    var g = $input.data("grid");
                    $input.yxcombogrid_product({
                        hiddenId: 'otherTable_cmp_productId'
                            + rowNum,
                        nameCol: 'productName',
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum,'productCode').val(rowData.productCode);
                                        g.getCmpByRowAndCol(rowNum,'pattern').val(rowData.pattern);
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            },
            {
                name: 'pattern',
                display: '对应物料型号',
                type : 'hidden'
            },
            {
                name: 'proNum',
                display: '对应物料数量',
                tclass: 'txtmin'
            },
            {
                name: 'status',
                display: '状态',
                type: 'select',
                width : 60,
                tclass: 'txtshort',
                options: [
                    {
                        name: "在产",
                        value: 'ZC'
                    },
                    {
                        name: "停产",
                        value: 'TC'
                    }
                ]
            },
            {
                name: 'isDefault',
                display: 'license<br/>必选',
                type: 'checkbox',
                width : 40
            },
            {
                name: 'licenseTypeName',
                display: 'license类型',
                type: 'hidden'
            },
            {
                name: 'licenseTypeCode',
                display: 'license类型',
                type: 'select',
                width : 100,
                options: [
                    {
                        value: "",
                        name: "无license"
                    },
                    {
                        value: "PIO",
                        name: "Pioneer"
                    },
                    {
                        value: "NAV",
                        name: "Navigator"
                    },
                    {
                        value: "Pioneer-Navigator",
                        name: "Pioneer-Navigator"
                    },
                    {
                        value: "WT",
                        name: "Walktour"
                    },
                    {
                        value: "Walktour Pack-Ipad",
                        name: "Walktour Pack-Ipad"
                    },
                    {
                        value: "FL2",
                        name: "Fleet"
                    }
                ],
                event: {
                    change: function (e) {
                        var rowNum = e.data.rowNum;
                        var g = e.data.gird;
                        var $cmp = g.getCmpByRowAndCol(rowNum,
                            'licenseTypeName');
                        var name = $(this).find("option:selected").text();
                        $cmp.val(name);
                        $.ajax({
                            type: "POST",
                            url: "?model=yxlicense_license_template&action=getTemplateByType",
                            data: {
                                'licenseType': $(this).val()
                            },
                            async: false,
                            success: function (data) {
                                var $cmp = g.getCmpByRowAndCol(rowNum,
                                    'licenseTemplateId');
                                $cmp.children().remove();
                                $cmp
                                    .append("<option value=''>请选择</option>");
                                dataRows = eval('(' + data + ')');
                                for (var i = 0, l = dataRows.length; i < l; i++) {
                                    $cmp.append("<option  value='"
                                        + dataRows[i].thisVal + "'>"
                                        + dataRows[i].name
                                        + "</option>");
                                }
                            }
                        });
                    }
                }
            },
            {
                name: 'licenseTemplateId',
                display: 'license模板',
                type: 'select',
                width : 80,
                options: []
            },
            {
                name: 'rkey',
                display: '描述标识',
                type: "hidden"
            },
            {
                name: 'remark',
                display: '描述',
                type: "hidden"
            },
            {
                name: 'staticRemark',
                display: '具体描述按钮',
                type: 'statictext',
                event: {
                    'click': function (e) {
                        var rowNum = $(this).data("rowNum");
                        var g = $(this).data("grid");
                        var rowData = $(this).data("rowData");
                        window.open(
                            "?model=goods_goods_properties&action=toEditRemark&rowNum="
                                + rowNum
                                + "&remark="
                                + $("#itemTable_cmp_remark"
                                + rowNum).val()
                                + "&rkey="
                                + $("#itemTable_cmp_rkey"
                                + rowNum).val(),
                            '描述信息编辑',
                            'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="编 辑"  class="txt_btn_a"  />'
            },
            {
                name: 'assitem',
                display: '数据项关联',
                type: "hidden"
            },
            {
                name: 'assitemIdStr',
                display: '数据项Id关联',
                type: "hidden"
            },
            {
                name: 'assitemTipStr',
                display: '数据项Tip关联',
                type: "hidden"
            },
            {
                name: 'staticAssitem',
                display: '数据项关联',
                type: 'statictext',
                event: {
                    'click': function (e) {
                        var rowNum = $(this).data("rowNum");
                        var g = $(this).data("grid");
                        var rowData = $(this).data("rowData");
                        window.open(
                            "?model=goods_goods_properties&action=toSetAssItem&goodsId="
                                + $("#mainId").val()
                                + "&orderNum="
                                + $("#orderNum").val()
                                + "&assitem="
                                + $("#itemTable_cmp_assitem")
                                .val()
                                + "&rowNum="
                                + rowNum
                                + "&assItemIdStr="
                                + $("#itemTable_cmp_assitemIdStr"
                                + rowNum).val()
                                + "&assitemTipStr="
                                + $("#itemTable_cmp_assitemTipStr"
                                + rowNum).val(),
                            '数据项关联',
                            'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="设 置"  class="txt_btn_a"  />'
            }
        ]
    });

    $("#otherTable").yxeditgrid('setRowColValue',0,'itemContent','加密锁（狗）');
    $("#otherTable").yxeditgrid('addRow',1).yxeditgrid('setRowColValue',1,'itemContent','Pilot Navigator');
    $("#otherTable").yxeditgrid('addRow',2).yxeditgrid('setRowColValue',2,'itemContent','Pilot Walktour');
}