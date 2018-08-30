$(function () {
    setSelect('beginYear');
    setSelect('endYear');
    setSelect('beginMonth');
    setSelect('endMonth');
    setSelect('isRed');
    // 客户
    $("#customerName").yxcombogrid_customer({
        hiddenId: 'customerId',
        height: 300,
        isShowButton: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {

                }
            }
        }
    });

    // 合同编号
    $("#objCode").yxcombogrid_allcontract({
        hiddenId: 'objId',
        nameCol: 'contractCode',
        searchName: '',
        isFocusoutCheck: false,
        height: 300,
        width: 600,
        gridOptions: {
            param: {
                "ExaStatusArr": "完成,变更审批中"
            },
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {

                }
            }
        }
    });
});

function confirm() {
    var beginYear = $("#beginYear").val();
    var beginMonth = $("#beginMonth").val();
    var endYear = $("#endYear").val();
    var endMonth = $("#endMonth").val();
    var customerId = $("#customerId").val();
    var customerName = $("#customerName").val();
    var isRed = $("#isRed").val();
    var objCode = $("#objCode").val();
    var objId = $("#objId").val();
    var productCode = $("#productCode").val();
    var k3Code = $("#k3Code").val();

    this.opener.location = "?model=stock_report_stockreport&action=toStockoutSales"
        + "&beginMonth=" + beginMonth
        + "&beginYear=" + beginYear
        + "&customerId=" + customerId
        + "&customerName=" + customerName
        + "&endMonth=" + endMonth
        + "&endYear=" + endYear
        + "&objCode=" + objCode
        + "&objId=" + objId
        + "&isRed=" + isRed
        + "&productCode=" + productCode
        + "&k3Code=" + k3Code
    ;
    this.close();
}

function refresh() {
    $(".clear").val("");
}