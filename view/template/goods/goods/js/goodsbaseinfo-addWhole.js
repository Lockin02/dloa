$(document).ready(function () {
    // ����������Ϣ ѡ����������
    $("#goodsTypeName").yxcombotree({
        hiddenId: 'goodsTypeId',
        treeOptions: {
            url: "?model=goods_goods_goodstype&action=getTreeData"
        }
    });
    // ����֤
    validate({
        "goodsName": {
            required: true
        },
        "exeDeptName": {
            required: true
        }
    });
    // ��������
    $("#auditDeptName").yxselect_dept({
        hiddenId: 'auditDeptCode',
        mode: 'check'
    });

    // ��ʼ������
    initProperties();

    // ��ʼ�����Ե�ѡ��
    initItem();

    // ��ʼ����������
    initOther();
});

// ��ʼ������
function initProperties() {
    $("#properties").yxeditgrid({
        objName: 'properties[items]',
        isAddOneRow: true,
        colModel: [
            {
                name: 'staticAssitem',
                display: '��������',
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
                display: '��������',
                validation: {
                    required: true
                }
            },
            {
                name: 'isNeed',
                display: '����',
                tclass: 'txtshort'
            },
            {
                name: 'isDefault',
                display: '����������',
                type: 'select',
                tclass: 'txtshort',
                options: [
                    {
                        name: "����ѡ��",
                        value: 'TC'
                    },
                    {
                        name: "����ѡ��",
                        value: 'ZC1'
                    },
                    {
                        name: "�ı�����",
                        value: 'ZC2'
                    }
                ]
            },
            {
                name: 'isDefault',
                display: '����ѡ��<br/>һ��',
                type: 'checkbox',
                width : 60
            },
            {
                name: 'isDefault',
                display: '����ֱ��<br/>����ֵ',
                type: 'checkbox',
                width : 60
            },
            {
                name: 'isDefault',
                display: '��������<br/>����',
                type: 'checkbox',
                width : 60
            },
            {
                name: 'remark',
                tclass: 'txt',
                display: '��ע'
            }
        ]
    });

    $("#properties").yxeditgrid('setRowColValue',0,'itemContent','���').yxeditgrid('getCmpByRowAndCol',0,'staticAssitem').find("input").attr('checked',true);;
    $("#properties").yxeditgrid('addRow',1).yxeditgrid('setRowColValue',1,'itemContent','�Զ�������').yxeditgrid('setRowColValue',1,'isDefault','ZC2');
}

// ��ʼ�������ڵ�ѡ��
function initItem() {
    $("#itemTable").yxeditgrid('remove').yxeditgrid({
        objName: 'properties[items]',
        isAddOneRow: true,
        colModel: [
            {
                name: 'itemContent',
                display: 'ֵ����',
                tclass: 'txtmiddle',
                validation: {
                    required: true
                }
            },
            {
                name: 'isNeed',
                display: '�Ƿ�<br/>��ѡ',
                type: 'checkbox',
                width : 30
            },
            {
                type: 'checkbox',
                display: '�Ƿ�<br/>Ĭ��',
                name: 'isDefault',
                width : 30
            },
            {
                name: 'defaultNum',
                display: '����',
                tclass: 'txtmin'
            },
            {
                name: 'productId',
                display: '��Ʒid',
                type: "hidden"
            },
            {
                name: 'productCode',
                display: '��Ӧ���ϱ��',
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
                display: '��Ӧ��������',
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
                display: '��Ӧ�����ͺ�',
                type : 'hidden'
            },
            {
                name: 'proNum',
                display: '��Ӧ��������',
                tclass: 'txtmin'
            },
            {
                name: 'status',
                display: '״̬',
                type: 'select',
                width : 60,
                tclass: 'txtshort',
                options: [
                    {
                        name: "�ڲ�",
                        value: 'ZC'
                    },
                    {
                        name: "ͣ��",
                        value: 'TC'
                    }
                ]
            },
            {
                name: 'isDefault',
                display: 'license<br/>��ѡ',
                type: 'checkbox',
                width : 40
            },
            {
                name: 'licenseTypeName',
                display: 'license����',
                type: 'hidden'
            },
            {
                name: 'licenseTypeCode',
                display: 'license����',
                type: 'select',
                width : 100,
                options: [
                    {
                        value: "",
                        name: "��license"
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
                                    .append("<option value=''>��ѡ��</option>");
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
                display: 'licenseģ��',
                type: 'select',
                width : 80,
                options: []
            },
            {
                name: 'rkey',
                display: '������ʶ',
                type: "hidden"
            },
            {
                name: 'remark',
                display: '����',
                type: "hidden"
            },
            {
                name: 'staticRemark',
                display: '����������ť',
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
                                '������Ϣ�༭',
                                'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="�� ��"  class="txt_btn_a"  />'
            },
            {
                name: 'assitem',
                display: '���������',
                type: "hidden"
            },
            {
                name: 'assitemIdStr',
                display: '������Id����',
                type: "hidden"
            },
            {
                name: 'assitemTipStr',
                display: '������Tip����',
                type: "hidden"
            },
            {
                name: 'staticAssitem',
                display: '���������',
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
                                '���������',
                                'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="�� ��"  class="txt_btn_a"  />'
            }
        ]
    });

    $("#itemTable").yxeditgrid('setRowColValue',0,'itemContent','Samsung Galaxy SIII SGH-i747');
    $("#itemTable").yxeditgrid('addRow',1).yxeditgrid('setRowColValue',1,'itemContent','iPhone5 GSM1428');
    $("#itemTable").yxeditgrid('addRow',2).yxeditgrid('setRowColValue',2,'itemContent','iPhone4SW');
}

// ��ʼ�������ڵ�ѡ��
function initOther() {
    $("#otherTable").yxeditgrid('remove').yxeditgrid({
        objName: 'properties[items]',
        isAddOneRow: true,
        colModel: [
            {
                name: 'itemContent',
                tclass: 'txtmiddle',
                display: 'ֵ����',
                validation: {
                    required: true
                }
            },
            {
                name: 'isNeed',
                display: '�Ƿ�<br/>��ѡ',
                type: 'checkbox',
                width : 30
            },
            {
                name: 'isDefault',
                display: '�Ƿ�<br/>Ĭ��',
                type: 'checkbox',
                width : 30
            },
            {
                name: 'defaultNum',
                display: '����',
                tclass: 'txtmin'
            },
            {
                name: 'productId',
                display: '��Ʒid',
                type: "hidden"
            },
            {
                name: 'productCode',
                display: '��Ӧ���ϱ��',
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
                display: '��Ӧ��������',
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
                display: '��Ӧ�����ͺ�',
                type : 'hidden'
            },
            {
                name: 'proNum',
                display: '��Ӧ��������',
                tclass: 'txtmin'
            },
            {
                name: 'status',
                display: '״̬',
                type: 'select',
                width : 60,
                tclass: 'txtshort',
                options: [
                    {
                        name: "�ڲ�",
                        value: 'ZC'
                    },
                    {
                        name: "ͣ��",
                        value: 'TC'
                    }
                ]
            },
            {
                name: 'isDefault',
                display: 'license<br/>��ѡ',
                type: 'checkbox',
                width : 40
            },
            {
                name: 'licenseTypeName',
                display: 'license����',
                type: 'hidden'
            },
            {
                name: 'licenseTypeCode',
                display: 'license����',
                type: 'select',
                width : 100,
                options: [
                    {
                        value: "",
                        name: "��license"
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
                                    .append("<option value=''>��ѡ��</option>");
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
                display: 'licenseģ��',
                type: 'select',
                width : 80,
                options: []
            },
            {
                name: 'rkey',
                display: '������ʶ',
                type: "hidden"
            },
            {
                name: 'remark',
                display: '����',
                type: "hidden"
            },
            {
                name: 'staticRemark',
                display: '����������ť',
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
                            '������Ϣ�༭',
                            'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="�� ��"  class="txt_btn_a"  />'
            },
            {
                name: 'assitem',
                display: '���������',
                type: "hidden"
            },
            {
                name: 'assitemIdStr',
                display: '������Id����',
                type: "hidden"
            },
            {
                name: 'assitemTipStr',
                display: '������Tip����',
                type: "hidden"
            },
            {
                name: 'staticAssitem',
                display: '���������',
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
                            '���������',
                            'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                    }
                },
                html: '<input type="button"  value="�� ��"  class="txt_btn_a"  />'
            }
        ]
    });

    $("#otherTable").yxeditgrid('setRowColValue',0,'itemContent','������������');
    $("#otherTable").yxeditgrid('addRow',1).yxeditgrid('setRowColValue',1,'itemContent','Pilot Navigator');
    $("#otherTable").yxeditgrid('addRow',2).yxeditgrid('setRowColValue',2,'itemContent','Pilot Walktour');
}