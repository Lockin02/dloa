$(function() {
    /**
     * 编号唯一性验证
     */
    var url = "?model=product_terminal_product&action=checkRepeat";
    var url1 = "?model=product_terminal_product&action=checkRepeat&id="+$('#id').val();
    var urlVal=$('#isEdit').val()==1 ? url1 : url;
    $("#productCode").ajaxCheck({
        url: urlVal,
        alertText: "* 该编号已存在",
        alertTextOk: "* 该编号可用"
    });
    $("#productName").ajaxCheck({
        url: urlVal,
        alertText: "* 该名字已存在",
        alertTextOk: "* 该名字可用"
    });

    
});
