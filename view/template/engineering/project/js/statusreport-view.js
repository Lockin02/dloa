$(function () {
    //��Ŀ��չ״����
    viewWeekStatus();

    //��ĿԤ��
    viewWeekWarning();

    //������ʱ����˵���֤
    if ($("#audit").length == 1) {
        $(window.parent.document.getElementById("sub")).bind("click", function () {
            if (document.getElementById("score").value == "") {
                alert('�ܱ����˷�������Ϊ��');
                return false;
            }
        });
    }
});

//���ÿ��˷���
function setScore() {
    var scoreObj = $("#score");
    if (isNaN(scoreObj.val()) || (scoreObj.val() * 1 > 10 || scoreObj.val() * 1 < 0)) {
        alert('������ 0 �� 10 ���ڵ�����');
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