$(document).ready(function () {
    //��ʼ��ʡ�ݳ�����Ϣ
    initCity();

    //��ѡ���´�
    $("#officeName").yxcombogrid_office({
        hiddenId: 'officeId',
        height: 300,
        gridOptions: {
            showcheckbox: false,
            isTitle: true,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#deptId").val(data.feeDeptId);
                    $("#deptName").val(data.feeDeptName);
                    //���ò�Ʒ����Ϣ
                    $("#productLine").val(data.productLine);
                    $("#productLineName").val(data.productLineName).attr('title', data.productLineName);
                }
            }
        }
    });

    //��ѡ��Ŀ����
    $("#managerName").yxselect_user({
        hiddenId: 'managerId',
        mode: 'check',
        formCode: 'esmManagerEdit'
    });

    //��ѡ��Ŀ����
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });

    /**
     * ��֤��Ϣ
     */
    validate({
        projectName: {
            required: true,
            length: [0, 100]
        },
        officeName: {
            required: true
        },
        category: {
            required: true
        },
        managerName: {
            required: true
        }
    });

    //�����Ŀû����Ŀ���ԣ�����������Ŀ����
    var attributeHiddenObj = $("#attributeHidden");
    if (attributeHiddenObj.length == 1) {
        if (attributeHiddenObj.val() == "") {
            var selectStr = '<select class="select" name="esmproject[attribute]" id="attribute"></select>';

            $("#attributeShow").html(selectStr);

            //��ȡ�����ֵ���Ϣ
            var responseText = $.ajax({
                url: 'index1.php?model=system_datadict_datadict&action=ajaxGetForEasyUI',
                type: "POST",
                data: {"parentCode": "GCXMSS", "expand1": "1"},
                async: false
            }).responseText;

            var dataArr = eval("(" + responseText + ")");
            var optionsStr = "<option value=''></option>";
            for (var i = 0, l = dataArr.length; i < l; i++) {
                optionsStr += "<option title='" + dataArr[i].text
                + "' value='" + dataArr[i].id + "'>" + dataArr[i].text
                + "</option>";
            }
            $("#attribute").append(optionsStr);
        }
    }

    // ������Ŀ�Ƿ������¼��־ - �Լ���ʼ����¼��ֵ
    $('#isWithoutLog').bind('change', function() {
        var temp = $(this);

        if (temp.val() == 0) {
            $.ajax({
                url: '?model=engineering_project_esmproject&action=checkCanWithoutLog',
                data: {
                    projectId: $('#id').val()
                },
                async: false,
                type: 'post',
                success: function(data) {
                    if (data != 'ok') {
                        alert(data);
                        temp.val(1);
                    }
                }
            });
        }
    }).val($("#isWithoutLogVal").val());

    // ���״̬Ϊ
    if ($("#nowStatus").val() == "GCXMZT00") {
        $("#showStatus").hide();
        $("#status").show();
    }
});