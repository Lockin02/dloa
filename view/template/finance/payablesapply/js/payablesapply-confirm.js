$(document).ready(function () {
    //邮件接收人渲染
    $("#TO_NAME").yxselect_user({
        hiddenId: 'TO_ID',
        mode: 'check',
        formCode: 'payablesapply'
    });

    // 设置默认线下支付值
    if ($("#isEntrustHidden").val() == 1) {
        $("#isEntrustShow").show();
        $("#yes").attr('checked', true);
        $("#no").attr('disabled', true);
    }
});

function payFun() {
    if (!confirm('您当前选择的是已线下支付选项，确认选择吗？')) {
        $("#no").attr('checked', true);
        $("#isEntrustShow").hide();
    } else {
        $("#isEntrustShow").show();
    }
}