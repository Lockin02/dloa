$(document).ready(function () {
    //服务总监
    $("#mainManager").yxselect_user({
        hiddenId: 'mainManagerId',
        mode: 'check',
        formCode: 'officeMainManager'
    });

    //负责人
    $("#head").yxselect_user({
        hiddenId: 'headId',
        mode: 'check',
        formCode: 'head'
    });

    //费用归属部门
    $("#feeDeptName").yxselect_dept({
        hiddenId: 'feeDeptId',
        mode: 'check',
        unDeptFilter: ($('#unDeptFilter').val() != undefined) ? $('#unDeptFilter').val() : ''
    });

    var stateHiddenObj = $("#stateHidden");
    if (stateHiddenObj.length == 1) {
        $("#state" + stateHiddenObj.val()).attr("checked", "checked");
    }

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

    //负责人
    $("#assistant").yxselect_user({
        hiddenId: 'assistantId',
        mode: 'check',
        formCode: 'assistant'
    });

    /**
     * 验证信息
     */
    validate({
        officeName: {
            required: true
        },
        mainManager: {
            required: true
        },
        businessBelongName: {
            required: true
        },
        feeDeptName: {
            required: true
        },
        head: {
            required: true
        },
        productLine: {
            required: true
        }
    });

    //初始化树结构
    initTree();
});