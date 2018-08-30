$(document).ready(function () {
    //�����ܼ�
    $("#mainManager").yxselect_user({
        hiddenId: 'mainManagerId',
        mode: 'check',
        formCode: 'officeMainManager'
    });

    //������
    $("#head").yxselect_user({
        hiddenId: 'headId',
        mode: 'check',
        formCode: 'head'
    });

    //���ù�������
    $("#feeDeptName").yxselect_dept({
        hiddenId: 'feeDeptId',
        mode: 'check',
        unDeptFilter: ($('#unDeptFilter').val() != undefined) ? $('#unDeptFilter').val() : ''
    });

    var stateHiddenObj = $("#stateHidden");
    if (stateHiddenObj.length == 1) {
        $("#state" + stateHiddenObj.val()).attr("checked", "checked");
    }

    //������˾
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId: 'businessBelong',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    //��ʼ�����ṹ
                    initTree();
                    //�������η�Χ
                    reloadManager();
                }
            }
        }
    });

    //������
    $("#assistant").yxselect_user({
        hiddenId: 'assistantId',
        mode: 'check',
        formCode: 'assistant'
    });

    /**
     * ��֤��Ϣ
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

    //��ʼ�����ṹ
    initTree();
});