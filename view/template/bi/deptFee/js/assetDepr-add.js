$(function () {
    //单选办事处
    $("#officeNames").yxcombogrid_office({
        hiddenId: 'officeIds',
        height: 250,
        gridOptions: {
            showcheckbox: true,
            isTitle: true,
            event: {
                'row_click': function (e, row, data) {
                    // 初始化分摊
                    $("#grid").yxeditgrid('remove');
                }
            }
        }
    });

    // 绑定加载事件
    $("#loadBtn").click(function() {
        // 记载分摊数据
        loadDepr();
    });
});

// 初始化分摊
var loadDepr = function () {
    var deprMoney = $("#deprMoney").val();
    if (deprMoney == "") {
        alert("请输入财务折旧金额");
        return false;
    }

    var officeNames = $("#officeNames").val();
    if (officeNames == "") {
        alert("请选择分摊区域");
        return false;
    }
    var thisYear = $("#thisYear").val();
    if (thisYear == "") {
        alert("请选择年份");
        return false;
    }
    var thisMonth = $("#thisMonth").val();
    if (thisMonth == "") {
        alert("请选择月份");
        return false;
    }

    $("#grid").yxeditgrid('remove').yxeditgrid({
        objName: 'assetDepr[assetShare]',
        title: '折旧分摊清单',
        tableClass: 'form_in_table',
        url: "?model=engineering_resources_esmdevicefee&action=getOfficeEquFee",
        param: {
            officeIds: $("#officeIds").val(),
            deprMoney: deprMoney,
            thisYear: thisYear,
            thisMonth: thisMonth
        },
        isAddAndDel: false,
        colModel: [
            {
                display: '分摊区域',
                name: 'officeName',
                readonly: true,
                tclass: 'readOnlyTxtMiddle'
            },
            {
                display: '分摊区域ID',
                name: 'officeId',
                type: 'hidden'
            },
            {
                display: '归属部门',
                name: 'deptName'
            },
            {
                display: '项目折旧',
                name: 'projectDepr',
                readonly: true,
                type: 'money',
                tclass: 'readOnlyTxtMiddle'
            },
            {
                display: '折旧比例（%）',
                name: 'projectDeprRate',
                type: 'money',
                readonly: true,
                tclass: 'readOnlyTxtMiddle'
            },
            {
                display: '承担差额',
                name: 'feeIn',
                type: 'money'
            }
        ]
    });
    return true;
}