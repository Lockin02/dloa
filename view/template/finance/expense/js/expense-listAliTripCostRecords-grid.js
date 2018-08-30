//显示loading
function showLoading(){
    $("#loading").show();
}

//隐藏
function hideLoading(){
    $("#loading").hide();
}

//初始化工作量统计表
function initDataGrid() {
    var beginDate = $("#beginDate").val();
    var endDate = $("#endDate").val();

    if (beginDate == "" || endDate == "") {
        alert('请选择日期区间');
        return false;
    }
    showLoading(); // 显示加载图标

    var objGrid = $("#dataListGrid");

    setTimeout(function(){
        //请求数据
        $.ajax({
            url: '?model=finance_expense_expense&action=searchAliGridJson',
            data: {
                userId: $("#userId").val(),
                beginDate: beginDate,
                endDate: endDate
            },
            type: 'POST',
            async: false,
            success: function(data) {
                if (objGrid.html() != "") {
                    objGrid.empty();
                }
                objGrid.html(data);
                hideLoading(); // 隐藏加载图标

                // 渲染 千分位金额
                $.each($(".formatMoney"), function(i, n) {
                    if ($(this).get(0).tagName == 'INPUT') {
                        var strHidden = "<input type='hidden' name='" + n.name
                            + "' id='" + n.id + "' value='" + n.value + "' />";
                        $(this).attr('name', '');
                        $(this).attr('id', n.id + '_v');
                        $(this).val(moneyFormat2(n.value, 2));
                        $(this).bind("blur", function() {
                            moneyFormat1(this, 2);
                            if (n.onblur)
                                n.onblur();
                        });
                        $(this).after(strHidden);
                    } else {
                        returnMoney = moneyFormat2($(this).text(), 2);
                        $(this).text(returnMoney);
                    }
                });
            }
        });
    },200);
}

//导出工作量统计
function exportExcel() {
    var url = "?model=finance_expense_expense&action=exportAliGridJson"
            + "&userId=" + $("#userId").val()
            + "&beginDate=" + $("#beginDate").val()
            + "&endDate=" + $("#endDate").val()
        ;
    showOpenWin(url, 1, 150, 300, 'exportExcel');
}

$(function(){
    showLoading();
    initDataGrid();
})
