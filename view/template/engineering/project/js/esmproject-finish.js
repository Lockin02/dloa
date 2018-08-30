$(function () {
    if ($("#TO_ID").length != 0) {
        $("#TO_NAME").yxselect_user({
            mode: 'check',
            hiddenId: 'TO_ID',
            formCode: 'income'
        });
    }

    if ($("#ADDIDS").length != 0) {
        $("#ADDNAMES").yxselect_user({
            mode: 'check',
            hiddenId: 'ADDIDS',
            formCode: 'income'
        });
    }
});

function checkform() {
    var actBeginDate = $("#actBeginDate").val();
    var actEndDate = $("#actEndDate").val();

    if (actBeginDate == "") {
        alert("请输入实际开始日期");
        return false;
    }

    if (actEndDate == "") {
        alert("请输入实际结束日期");
        return false;
    }

    if (DateDiff(actBeginDate, actEndDate) < 0) {
        alert("实际结束日期不能大于实际开始日期");
        return false;
    }

    return confirm("确认要将项目转入完工状态码？");
}
