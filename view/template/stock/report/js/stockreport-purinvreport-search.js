
$(function(){
    setSelect('beginYear');
    setSelect('endYear');
    setSelect('beginMonth');
    setSelect('endMonth');

    // 供应商
    $("#supplierName").yxcombogrid_supplier({
        hiddenId : 'supplierId',
        height : 300,
        width : 600,
        isShowButton : false,
        gridOptions : {
            showcheckbox : false
        }
    });
    // 供应商
    $("#productCode").yxcombogrid_product({
        hiddenId : 'productId',
        gridOptions : {
            showcheckbox : false
        }
    });
});

function confirm() {
    var productId = $("#productId").val();//物料代码
    var productCode = $("#productCode").val();//物料代码
    var beginYear=$("#beginYear").val();
    var beginMonth=$("#beginMonth").val();
    var endYear=$("#endYear").val();
    var endMonth=$("#endMonth").val();
    var supplierId=$("#supplierId").val();
    var supplierName=$("#supplierName").val();

    var toUrl = "?model=stock_report_stockreport&action=toPurInvReport&productId="
        +productId+"&beginMonth="+beginMonth+"&beginYear="+beginYear+"&productCode="+productCode+"&supplierName="+supplierName
        +"&endMonth="+endMonth+"&endYear="+endYear+"&supplierId="+supplierId
    ;
    this.opener.location = toUrl;
    this.close();
}

function refresh(){
    $(".clear").val("");
}