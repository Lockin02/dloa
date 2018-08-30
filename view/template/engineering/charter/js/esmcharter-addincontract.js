/**
 * 初始省份区域
 * @param {} $t
 * @return {Boolean}
 */
function initOffice() {
    //获取省份对应的办事处
    $.ajax({
        type: "POST",
        url: "?model=engineering_officeinfo_range&action=getOfficeInfoForId",
        data: {
            "provinceId": $("#provinceId").val(),
            "businessBelong": $("#businessBelong").val(),
            "productLine": $("#productLine").val()
        },
        async: false,
        success: function (data) {
            if (data != "") {
                var dataObj = eval("(" + data + ")");
                $("#officeName").val(dataObj.officeName);
                $("#officeId").val(dataObj.officeId);
                $("#deptId").val(dataObj.feeDeptId);
                $("#deptName").val(dataObj.feeDeptName);
                $("#productLine").val(dataObj.productLine);
            }
        }
    });
}

//启动与结束关闭差验证
function timeCheck($t) {
    var startDate = $('#planBeginDate').val();
    var endDate = $('#planEndDate').val();
    if (startDate == "" || endDate == "") {
        return false;
    }
    var s = DateDiff(startDate, endDate) + 1;
    if (s < 0) {
        alert("预计开始不能比预计结束时间晚！");
        $t.value = "";
        return false;
    }
}

// 获取产线可用占比
function initWorkRate() {
    $("#workRate").attr('readonly', true).removeClass('txt').addClass('readOnlyText');
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=getWorkrateCanUse",
        data: {
            "contractId": $("#contractId").val(),
            "contractType": $("#contractType").val(),
            "productLine": $("#productLine").val()
        },
        async: false,
        success: function (data) {
            var workRateObj = $("#workRate");
            if (workRateObj.val() * 1 == 0 || data * 1 < workRateObj.val() * 1) {
                workRateObj.val(data);
            }
            $("#remainWorkRate").val(data);
            workRateObj.attr('readonly', false).removeClass('readOnlyText').addClass('txt');
        }
    });
}

//表单验证
function checkform() {
    var workRateObj = $("#workRate");
    var remainWorkRateObj = $("#remainWorkRate");

    if (workRateObj.val() * 1 == 0) {
        alert('工作占比不能为0');
        return false;
    }

    if (workRateObj.val() * 1 > remainWorkRateObj.val() * 1) {
        alert('工作占比已超出范围');

        workRateObj.val(remainWorkRateObj.val());
        return false;
    }

    //项目编号唯一校验
    var unRepeat = true;
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=checkIsRepeat",
        data: {projectCode: $("#projectCode").val()},
        async: false,
        success: function (data) {
            if (data == "1") {
                alert('项目编号已存在');
                unRepeat = false;
            }
        }
    });

    //  验证产品线
    if(unRepeat == true){
        $.ajax({
            type: "POST",
            url: "?model=contract_conproject_conproject&action=getisExistByLine",
            data: {cid: $("#contractId").val(), productLine: $("#productLine").val()},
            async: false,
            success: function (data) {
                if (data == "1") {
                    alert('该产品线在合同中不存在，不能进行立项操作');
                    unRepeat = false;
                }
            }
        });
    }

    return unRepeat;
}

$(document).ready(function () {
    // 设置区域
    initOffice();

    // 单选办事处
    $("#officeName").yxcombogrid_office({
        hiddenId: 'officeId',
        height: 250,
        gridOptions: {
            showcheckbox: false,
            isTitle: true,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#deptId").val(data.feeDeptId);
                    $("#deptName").val(data.feeDeptName);
                    $("#productLine").val(data.productLine);
                    initWorkRate();
                }
            }

        }
    });

    // 初始化产线可用占比
    initWorkRate();

    // 单选项目经理
    $("#managerName").yxselect_user({
        hiddenId: 'managerId',
        mode: 'check',
        formCode: 'esmcharter'
    });

    // 所属部门
    $("#deptName").yxselect_dept({
        hiddenId: 'deptId'
    });

    // 初始化城市信息
    initCity();

    $("#country").change(function () {
        if ($(this).find("option:selected").text() != '中国') {
            //	alert('海外');
            var cityUrl = "?model=system_procity_city&action=pageJson"; // 获取市的URL
            $("#province").val('32');
            var provinceId = 32;
            $.ajax({
                type: 'POST',
                url: cityUrl,
                data: {
                    provinceId: provinceId,
                    pageSize: 999
                },
                async: false,
                success: function (data) {
                    $('#city').children().remove("option[value!='']");
                    getCitys(data);
                    $('#city').find("option[text='海外']").attr("selected", true);
                }
            });
        }
    });

    // 验证信息
    validate({
        "projectCode": {	//项目名称
            required: true,
            length: [0, 100]
        },
        "projectName": {	//项目名称
            required: true,
            length: [0, 100]
        },
        "officeName": {	//办事处
            required: true,
            length: [0, 20]
        },
        "managerName": {	//项目经理
            required: true,
            length: [0, 20]
        },
        "planBeginDate": {	//预计启动日期
            custom: ['date']
        },
        "planEndDate": {	//预计结束日期
            custom: ['date']
        },
        "workRate": {	//工作占比
            required: true,
            custom: ['percentage']
        },
        "country": {
            required: true
        },
        "province": {
            required: true
        },
        "city": {
            required: true
        },
        "deptName": {
            required: true
        },
        "category": {
            required: true
        }
    });

    /**
     * 编号唯一性验证
     */
    var url = "?model=engineering_project_esmproject&action=checkRepeat";
    $("#projectCode").ajaxCheck({
        url: url,
        alertText: "* 该编号已存在",
        alertTextOk: "* 该编号可用"
    });
});