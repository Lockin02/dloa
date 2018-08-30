$(function () {
    if ($("#isStock").val() == '1') {
        $("#isStock").attr("checked", "checked");
    }
});

function searchBtn() {
    var year = $("#year").val();
    var month = $("#month").val();
    if (year == "") {
        alert("期初年份不能为空");
        return false;
    }
    if (month == "") {
        alert("期初月份不能为空");
        return false;
    }
    if ($("#isStock").attr("checked")) {
        location.href = "?model=stock_report_stockreport&action=toProSFItemReport&year="
            + year
            + "&month=" + month
            + "&nextYear=" + $("#nextYear").val()
            + "&nextMonth=" + $("#nextMonth").val()
            + "&productNo=" + $("#productNo").val()
            + "&productName=" + $("#productName").val()
            + "&k3Code=" + $("#k3Code").val()
            + "&isStock=1";
    } else {
        location.href = "?model=stock_report_stockreport&action=toProSFItemReport&year="
            + year
            + "&month=" + month
            + "&nextYear=" + $("#nextYear").val()
            + "&nextMonth=" + $("#nextMonth").val()
            + "&productNo=" + $("#productNo").val()
            + "&productName=" + $("#productName").val()
            + "&k3Code=" + $("#k3Code").val();
    }
}

//响应内容行双击事件，打开当前行对应的明细报表
function OnContentCellDblClick(Sender) {
    //交叉表应该从 RunningDetailGrid 取穿透数据
    var RunningDetailGrid = ReportViewer.Report.RunningDetailGrid;
    var year = RunningDetailGrid.Recordset.Fields.Item("periodYear").AsString;
    var month = RunningDetailGrid.Recordset.Fields.Item("periodMonth").AsString;
    var productNo = RunningDetailGrid.Recordset.Fields.Item("productNo").AsString;
    var productName = RunningDetailGrid.Recordset.Fields.Item("productName").AsString;
    var k3Code = RunningDetailGrid.Recordset.Fields.Item("k3Code").AsString;
    var stockId = "";
    if ($("#isStock").val() == '1') {
        stockId = RunningDetailGrid.Recordset.Fields.Item("stockId").AsString;
    }
    showModalWin("?model=stock_report_stockreport&action=toProSFDetailReport"
        + "&year=" + year
        + "&month=" + month
        + "&endYear=" + year
        + "&endMonth=" + month
        + "&productNo=" + productNo
        + "&productName=" + productName
        + "&k3Code=" + k3Code
        + "&stockId=" + stockId);
}