
$(function(){
    setSelect('beginYear');
    setSelect('endYear');
    setSelect('beginMonth');
    setSelect('endMonth');

    // ��Ӧ��
    $("#supplierName").yxcombogrid_supplier({
        hiddenId : 'supplierId',
        height : 300,
        width : 600,
        isShowButton : false,
        gridOptions : {
            showcheckbox : false
        }
    });
    // ��Ӧ��
    $("#productCode").yxcombogrid_product({
        hiddenId : 'productId',
        gridOptions : {
            showcheckbox : false
        }
    });
});

function confirm() {
    var productId = $("#productId").val();//���ϴ���
    var productCode = $("#productCode").val();//���ϴ���
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