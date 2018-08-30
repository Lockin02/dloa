/**
 * Created by Kuangzw on 2017/3/30.
 */
$(function () {
    $("#confirm1").click(function () {
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
                    url: "?model=contract_counting_counting&action=update",
                    data: {
                        contractCode: $("#contractCode1").val(),
                        year: $("#year1").val(), "month": $("#month1").val()
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

    // 撤回删除
    $("#resetDel").click(function () {
        $("#showMsg").text('数据处理中......');
        var imgObj = $("#imgLoading");
        //显示进度图
        imgObj.show();
        //显示提示
        $("#trip").show();

        //禁用按钮
        var btnObj = $(this);
        btnObj.attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "?model=contract_counting_counting&action=update",
            data: {
                contractCode: $("#contractCode1").val(),
                year: $("#year1").val(),
                "month": $("#month1").val(),
                "resetField" : 'isDel'
            },
            success: function (data) {
                if (data == '0') {
                    $("#showMsg").text('更新失败');
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
    });

    // 撤回立项标记
    $("#resetTrue").click(function () {
        $("#showMsg").text('数据处理中......');
        var imgObj = $("#imgLoading");
        //显示进度图
        imgObj.show();
        //显示提示
        $("#trip").show();

        //禁用按钮
        var btnObj = $(this);
        btnObj.attr('disabled', true);
        $.ajax({
            type: "POST",
            url: "?model=contract_counting_counting&action=update",
            data: {
                contractCode: $("#contractCode1").val(),
                year: $("#year1").val(),
                "month": $("#month1").val(),
                "resetField" : 'isTrue'
            },
            success: function (data) {
                if (data == '0') {
                    $("#showMsg").text('更新失败');
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
    });
});