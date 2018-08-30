$(document).ready(function () {
    //绑定更新人力决算点击事件
    $("#confirmSalary").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_person_esmperson&action=updateSalary",
                    data: {"thisYear": $("#year").val(), "thisMonth": $("#month").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });
    //绑定更新毛利率点击事件
    $("#confirmExgross").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_project_esmproject&action=updateExgross",
                    data: {"updateDate": $("#updateDate").val(), 'projectCode': $("#projectCode").val(), 'status': $("#status").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });
    //绑定更新PK项目映射点击事件
    $("#confirmRemapping").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_project_esmmapping&action=remapping",
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    //绑定更新设备决算点击事件
    $("#confirmEquFee").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_resources_esmdevicefee&action=updateFee",
                    data: {"thisYear": $("#yearEqu").val(), "thisMonth": $("#monthEqu").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    //绑定更新现场决算点击事件
    $("#confirmFieldFee").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_records_esmfieldrecord&action=updateFee",
                    data: {"thisYear": $("#yearField").val(), "thisMonth": $("#monthField").val(), "projectCode": $("#projectCode5").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    // 更新项目信息
    $("#confirmProjectInfo").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_project_esmproject&action=updateProjectFields",
                    data: {"projectCode": $("#projectCode8").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    // 收入数据存档
    $("#confirmProjectIncome").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_records_esmincome&action=updateIncome",
                    data: {projectCode: $("#incomeProjectCode").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    // 决算数据存档
    $("#confirmFeeVersion").click(function () {
        var year = $("#year7").val();
        if (year == "") {
            alert('年份不能为空');
            return false;
        }
        var month = $("#month7").val();
        if (month == "") {
            alert('月份不能为空');
            return false;
        }
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_records_esmfielddetail&action=saveFeeVersion",
                    data: {
                        budgetType: $("#budgetType").val(), year: year, month: month,
                        projectCode: $("#feeProjectCode").val()
                    },
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    // 产品项目 - 更新收入版本
    $("#confirmIncome_c").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=contract_conproject_conproject&action=confirmIncome",
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });
    // 产品项目 - 更新成本版本
    $("#confirmCost_c").click(function () {
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=contract_conproject_conproject&action=confirmCost",
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });


    $("#updateConproject").click(function(){
        if (confirm('更新数据会需要花费较长时间，确定进行此操作吗？')) {
            $("#showMsg").text('数据更新中......');
            var imgObj = $("#imgLoading");
            //显示进度图
            imgObj.show();
            //显示提示
            $("#trip").show();

            //禁用按钮
            var btnObj = $(this);
            btnObj.attr('disabled', true);

            setTimeout(function () {
                //调用更新功能
                $.ajax({
                    type: "POST",
                    url: "?model=contract_conproject_conproject&action=updateSaleProjectVal",
                    data: {"projectCode": $("#projectCodec3").val()},
                    success: function (data) {
                        if (data == '0') {
                            $("#showMsg").text('无数据更新');
                        } else {
                            if (data == "1") {
                                $("#showMsg").text('更新成功');
                            } else {
                                $("#showMsg").text(data);
                            }
                        }
                        //隐藏进度图
                        imgObj.hide();
                        btnObj.attr('disabled', false);
                    }
                });
            }, 200);
        }
    });

    // 更新产品项目数据
    $("#updateConprojectData").click(function(){
        if (confirm('确认更新数据吗？')) {
            showThickboxWin('?model=contract_conproject_conprojectRecord&action=updateRecord'
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=600');
        }
    });

    // 保存版本数据
    $("#saveConprojectVersion").click(function(){
        showThickboxWin('?model=contract_conproject_conprojectRecord&action=toSetUsing'
            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=280&width=550');
    });
});

// 变更操作类型
function changeInfo(thisVal) {
    $("span[id^='remarkInfo']").each(function () {
        var selectdVal = $(this).attr('val');
        if (selectdVal == thisVal) {
            $(this).show();
            $("#span" + selectdVal).addClass('green');
            $("#condition" + selectdVal).show();
            $("#range" + selectdVal).show();
        } else {
            $(this).hide();
            $("#span" + selectdVal).removeClass('green');
            $("#condition" + selectdVal).hide();
            $("#range" + selectdVal).hide();
        }
    });
}
