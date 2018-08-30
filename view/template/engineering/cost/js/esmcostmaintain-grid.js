$(function () {
    //获取权限
    $.ajax({
        type: 'POST',
        url: '?model=engineering_project_esmproject&action=getLimits',
        data: {
            'limitName': '项目费用维护'
        },
        async: false,
        success: function (data) {
            if (data == 1) {//拥有对应权限
                //加载删除及导入按钮
                $("#showAllBtn").after("<hr style='margin: 3px 0'/>" +
                    "<input type='button' class='txt_btn_a' value='导 入' style='cursor: pointer;' onclick='toImport();'/>");
            }
        }
    });
    initCostlog();
});

function show_page() {
    initCostlog();
}

//初始化项目费用维护表
function initCostlog() {
    var projectCode = $("#projectCode").val();
    var costType = $("#costType").val();
    var startMonth = $("#startMonth").val();
    var endMonth = $("#endMonth").val();
    var startYear = $("#startYear").val();
    var endYear = $("#endYear").val();
    var ExaStatus = $("#ExaStatus").val();

    if (startMonth != "" && endMonth != "") {	//根据月份过滤
        var s = DateDiff(startMonth + "-01", endMonth + "-01");//日期格式必须为Y-m-d
        if (s < 0) {
            alert("查询起始月份不能比查询结束月份晚！");
            return false;
        }
    }
    if (startYear != "" && endYear != "") { //根据年份过滤
        if (startYear > endYear) {
            alert("查询起始年份不能比查询结束年份晚！");
            return false;
        }
    }
    showLoading(); // 显示加载图标
    //请求数据
    $.ajax({
        url: '?model=engineering_cost_esmcostmaintain&action=searchJson',
        data: {
            projectCode: projectCode,
            costType: costType,
            startMonth: startMonth,
            endMonth: endMonth,
            startYear: startYear,
            endYear: endYear,
            ExaStatus: ExaStatus
        },
        type: 'POST',
        success: function (data) {
            var objGrid = $("#esmcostmaintainGrid");
            if (objGrid.html() != "") {
                objGrid.empty();
            }
            objGrid.html(data);
            hideLoading(); // 隐藏加载图标
        }
    });
}

//显示loading
function showLoading() {
    $("#loading").show();
}
//隐藏
function hideLoading() {
    $("#loading").hide();
}
//返回当月
function currentMonth() {
    var currentMonth = $("#currentMonth").val();
    //设置月份区间为当前月
    $("#startMonth").val(currentMonth);
    $("#endMonth").val(currentMonth);
    //清空其它搜索条件
    $("#projectCode").val("");
    $("#costType").val("");
    $("#startYear").val("");
    $("#endYear").val("");
    $("#ExaStatus").val("");
    //重载列表
    initCostlog();
}
//返回所有
function showAll() {
    //清空其它搜索条件
    $("#projectCode").val("");
    $("#costType").val("");
    $("#startMonth").val("");
    $("#endMonth").val("");
    $("#startYear").val("");
    $("#endYear").val("");
    $("#ExaStatus").val("");
    //重载列表
    initCostlog();
}
//查看项目明细
function searchDetail(projectId) {
    var skey = "";
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=md5RowAjax",
        data: { "id": projectId },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId + "&skey=" + skey, 1);
}
//删除
function deleteChecked() {
    //构建id数组
    var ids = [];
    $("input[type='checkbox']:checked").each(function () {
        id = $(this).val();
        if (id != "") {
            ids.push(id);
        }
    });
    if (ids.length > 0) {
        //将id数组转换成以逗号隔开的字符串
        ids = ids.join(",");
        if (confirm('确定要删除？')) {
            $.ajax({
                type: 'POST',
                url: '?model=engineering_cost_esmcostmaintain&action=ajaxdeletes',
                data: {
                    id: ids
                },
                success: function (data) {
                    if (data == 1) {
                        alert("删除成功");
                        //重载列表
                        initCostlog();
                    } else {
                        alert("删除失败");
                    }
                }
            });
        }
    } else {
        alert("请至少选择一条数据");
    }
}
//导入
function toImport() {
    showThickboxWin("?model=engineering_cost_esmcostmaintain&action=toImport"
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
}
//全选
function checkAll(obj) {
    var checked = $(obj).attr("checked");
    $(obj).parents(".main_table:first").find("tbody input[type='checkbox']").each(function () {
        $(this).attr("checked", checked);
    });
}