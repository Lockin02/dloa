$(function() {
    /**
     * ���Ψһ����֤
     */
    var url = "?model=product_terminal_product&action=checkRepeat";
    var url1 = "?model=product_terminal_product&action=checkRepeat&id="+$('#id').val();
    var urlVal=$('#isEdit').val()==1 ? url1 : url;
    $("#productCode").ajaxCheck({
        url: urlVal,
        alertText: "* �ñ���Ѵ���",
        alertTextOk: "* �ñ�ſ���"
    });
    $("#productName").ajaxCheck({
        url: urlVal,
        alertText: "* �������Ѵ���",
        alertTextOk: "* �����ֿ���"
    });

    
});
