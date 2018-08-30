$(document).ready(function () {
    //签约单位
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
                    $("#payee").val(data.signCompanyName);
                }
            }
        }
    });

    //归属公司
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId: 'businessBelong',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function () {
                    //初始化树结构
                    initTree();
                    //重置责任范围
                    reloadManager();
                }
            }
        }
    });

    //获取省份数组并赋值给provinceArr
    var provinceArr = getProvince();

    //把省份数组provinceArr赋值给proCode
    addDataToProvince(provinceArr, 'proCode');

    //初始化省份名称
    setProName();
    //单选负责人
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"],
        event: {
            select: function (e, obj) {
                if ($("#isNeedPayapply").attr("checked")) {
                    $("#feeDeptId").val(obj.deptId);
                    $("#feeDeptName").val(obj.deptName);
                }
                $("#otherFeeDeptId").val(obj.deptId);
                $("#otherFeeDeptName").val(obj.deptName);
            }
        }
    });

    // 金额币别
    $("#currency").yxcombogrid_currency({
        valueCol: 'currency',
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false
        }
    });

    // 验证信息
    validate({
        orderName: {
            required: true,
            length: [0, 100]
        },
        signCompanyName: {
            required: true,
            length: [0, 100]
        },
        principalName: {
            required: true,
            length: [0, 20]
        },
        phone: {
            required: true
        },
        linkman: {
            required: true,
            length: [0, 100]
        },
        signDate: {
            custom: ['date']
        },
        proCode: {
            required: true
        },
        businessBelongName: {
            required: true
        }
    });

    //判断系统合同号生成规则
    if ($("#isSysCode").val() == 0) {
        $("#orderCodeNeed").html("[*]");
        // 验证信息
        validate({
            orderCode: {
                required: true,
                length: [0, 100]
            }
        });
        var url = "?model=contract_other_other&action=checkRepeat";
        $("#orderCode").attr("class", "txt").attr("readonly", false).ajaxCheck({
            url: url,
            alertText: "* 该合同号已存在",
            alertTextOk: "* 该合同号可用"
        });
    }

    // 发票事件绑定
    $("#invoiceType").bind("change", invoiceTypeChange);
});

// 加载分摊列表
function loadShareGrid() {
    //显示费用分摊明细
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        isShowExcelBtn: true,
        isShowCountRow: true,
        countKey: getMoneyKey()
    });
}