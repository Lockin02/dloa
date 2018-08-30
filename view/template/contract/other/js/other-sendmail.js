/**
 * Created by Kuangzw on 2017/7/18.
 */

$(function () {
    //签约单位
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

    // 验证信息
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

// 加载合同
function loadContract() {
    var signCompanyName = $("#signCompanyName").val();
    if (signCompanyName == "") {
        alert("签约公司不能为空");
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
                alert("没有查询到此公司的相关合同信息。");
            } else {
                $("#mailUserId").val(msg.mailUserId);
                $("#mailUser").val(msg.mailUser);
                $("#mailContent").val(msg.mailContent);
            }
        }
    });
}