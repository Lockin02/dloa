/**
 * Created by Kuangzw on 2017/7/18.
 */

$(function () {
    //ǩԼ��λ
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            event: {
                'row_dblclick': function (e, row, data) {

                }
            }
        }
    });

    $("#mailUser").yxselect_user({
        mode: 'check',
        hiddenId: 'mailUserId',
        formCode: 'otherMailUser'
    });

    // ��֤��Ϣ
    validate({
        signCompanyName: {
            required: true,
            length: [0, 100]
        },
        mailUser: {
            required: true
        },
        mailContent: {
            required: true
        }
    });

    $('#mailContent').ckeditor();
});

// ���غ�ͬ
function loadContract() {
    var signCompanyName = $("#signCompanyName").val();
    if (signCompanyName == "") {
        alert("ǩԼ��˾����Ϊ��");
        return false;
    }
    $.ajax({
        url: "?model=contract_other_other&action=getSendMailInfo",
        data: {
            signCompanyName: signCompanyName
        },
        type: "POST",
        success: function (msg) {
            msg = eval("(" + msg + ")");
            if (msg.mailContent == "") {
                $("#mailUserId").val("");
                $("#mailUser").val("");
                $("#mailContent").val("");
                alert("û�в�ѯ���˹�˾����غ�ͬ��Ϣ��");
            } else {
                $("#mailUserId").val(msg.mailUserId);
                $("#mailUser").val(msg.mailUser);
                $("#mailContent").val(msg.mailContent);
            }
        }
    });
}