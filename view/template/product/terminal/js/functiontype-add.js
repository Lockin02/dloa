$(function() {
    /**
     * ���Ψһ����֤
     */
//    var url = "?model=product_terminal_functiontype&action=checkRepeat";
//    var url1 = "?model=product_terminal_functiontype&action=checkRepeat&id=" + $('#id').val();
//    var urlVal = $('#isEdit').val() == 1 ? url1 : url;
//    $("#typeName").ajaxCheck({
//        url: urlVal,
//        alertText: "* �÷����Ѵ���",
//        alertTextOk: "* �÷������"
//    });
//    validate({
//        "productName": {
//            required: true
//        }
//    });
    $("#productName").yxcombogrid_terminalProduct({
        nameCol: 'productName',
        hiddenId: 'productId',
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function(e, row, data) {
                    $("#productName").val(data.productName);
                    $("#productId").val(data.id);
                }
            }
        }
    });


});
