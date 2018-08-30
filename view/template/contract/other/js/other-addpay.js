$(document).ready(function() {
    //签约单位
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        isFocusoutCheck: false,
        height: 300,
        gridOptions: {
            isTitle: true,
            event: {
                'row_dblclick': function(e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
                }
            }
        }
    });

    //把省份数组provinceArr赋值给proCode
    addDataToProvince(getProvince(), 'proCode');

    //初始化省份名称
    setProName();
    //单选负责人
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"]
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
        }
    });

    //判断系统合同号生成规则
    if ($("#isSysCode").val() == 0) {
        $("#orderCodeNeed").html("[*]");

        /**
         * 合同号唯一性验证
         */
        $("#orderCode").attr("class", "txt").attr("readonly", false).ajaxCheck({
            url: "?model=contract_other_other&action=checkRepeat",
            alertText: "* 该合同号已存在",
            alertTextOk: "* 该合同号可用"
        });
    }

    loadShareGrid();
});

// 加载分摊列表
function loadShareGrid() {
    var fundType = $("#fundType").val();
    if (fundType == 'KXXZB') {
        //显示费用分摊明细
        var shareGridObj = $("#shareGrid");
        if (shareGridObj.length > 0) {
            $("#shareGridTr").show();
            $('#st').tabs();
            shareGridObj.costShareGrid({
                url: "?model=finance_cost_costshare&action=listjsonForFlight",
                param: {'projectId': $("#projectId").val(),"businessBelong" : $("#businessBelong").val()},
                type: 'view',
                isShowCountRow: true,
                title: '',
                event: {
                    'reloadData': function (e, g, data) {
                        if (!data) {
                            shareGridObj.find("tbody").append("<tr><td colspan='10'> -- 暂无分摊数据 --</td></tr>");
                        } else {
                            g.costShareMoneyView(data);
                        }
                    }
                }
            });
        }
    }
}