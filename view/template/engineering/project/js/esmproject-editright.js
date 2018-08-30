$(document).ready(function () {
    //初始化省份城市信息
    initCity();

    //单选办事处
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
                    //重置产品线信息
                    $("#productLine").val(data.productLine);
                    $("#productLineName").val(data.productLineName).attr('title', data.productLineName);
                }
            }
        }
    });

    //单选项目经理
    $("#managerName").yxselect_user({
        hiddenId: 'managerId',
        mode: 'check',
        formCode: 'esmManagerEdit'
    });

    //单选项目经理
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });

    /**
     * 验证信息
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

    //如果项目没有项目属性，可以设置项目属性
    var attributeHiddenObj = $("#attributeHidden");
    if (attributeHiddenObj.length == 1) {
        if (attributeHiddenObj.val() == "") {
            var selectStr = '<select class="select" name="esmproject[attribute]" id="attribute"></select>';

            $("#attributeShow").html(selectStr);

            //获取数据字典信息
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

    // 检验项目是否可以免录日志 - 以及初始化免录赋值
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

    // 如果状态为
    if ($("#nowStatus").val() == "GCXMZT00") {
        $("#showStatus").hide();
        $("#status").show();
    }
});