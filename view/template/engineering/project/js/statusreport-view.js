$(function () {
    //项目进展状况表
    viewWeekStatus();

    //项目预警
    viewWeekWarning();

    //绑定审批时的审核的验证
    if ($("#audit").length == 1) {
        $(window.parent.document.getElementById("sub")).bind("click", function () {
            if (document.getElementById("score").value == "") {
                alert('周报考核分数不能为空');
                return false;
            }
        });
    }
});

//设置考核分数
function setScore() {
    var scoreObj = $("#score");
    if (isNaN(scoreObj.val()) || (scoreObj.val() * 1 > 10 || scoreObj.val() * 1 < 0)) {
        alert('请输入 0 到 10 以内的数字');
        scoreObj.val('');
    }

    if (scoreObj.val() !== "") {
        $.ajax({
            type: "POST",
            url: "?model=engineering_project_statusreport&action=updateScore",
            data: {'id': $("#id").val(), 'score': scoreObj.val()},
            async: false,
            success: function (result) {

            }
        });
    }
}