$(document).ready(function () {
    //�ʼ���������Ⱦ
    $("#TO_NAME").yxselect_user({
        hiddenId: 'TO_ID',
        mode: 'check',
        formCode: 'payablesapply'
    });

    // ����Ĭ������֧��ֵ
    if ($("#isEntrustHidden").val() == 1) {
        $("#isEntrustShow").show();
        $("#yes").attr('checked', true);
        $("#no").attr('disabled', true);
    }
});

function payFun() {
    if (!confirm('����ǰѡ�����������֧��ѡ�ȷ��ѡ����')) {
        $("#no").attr('checked', true);
        $("#isEntrustShow").hide();
    } else {
        $("#isEntrustShow").show();
    }
}