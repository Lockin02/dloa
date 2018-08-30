$(document).ready(function () {
    //付款信息渲染
    var isNeedPayapplyObj = $("#isNeedPayapply");
    if (isNeedPayapplyObj.val() == 1) {
        isNeedPayapplyObj.trigger('click');
        showPayapplyInfo(isNeedPayapplyObj[0]);
    }

    //签约单位
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        isFocusoutCheck: false,
        gridOptions: {
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
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
                'row_dblclick': function (e, row, data) {
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

    //单选负责人
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"]
    });

    //初始化项目信息
    //changeProjectClear();

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

    //是否委托付款
    if ($("#isEntrust").val() == "1") {
        $("#isEntrustYes").attr('checked', true);
        $("#bank").val('银行代扣').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
        $("#account").val('银行代扣').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
    } else {
        $("#isEntrustNo").attr('checked', true);
    }

    //是否开据发票
    if ($("#isInvoice").val() == "1") {
        $("#isInvoiceYes").attr('checked', true);
    } else {
        $("#isInvoiceNo").attr('checked', true);
    }

    // 发票事件绑定
    $("#invoiceType").bind("change", invoiceTypeChange);
});

// 加载分摊列表
function loadShareGrid() {
    //显示费用分摊明细
    $("#shareGridTr").show();
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {objType: 2, objId: $("#id").val()},
        isShowCountRow: true,
		isShowExcelBtn: true,
        countKey: getMoneyKey()
    });
}