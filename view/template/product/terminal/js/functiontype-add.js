$(function() {
    /**
     * 编号唯一性验证
     */
//    var url = "?model=product_terminal_functiontype&action=checkRepeat";
//    var url1 = "?model=product_terminal_functiontype&action=checkRepeat&id=" + $('#id').val();
//    var urlVal = $('#isEdit').val() == 1 ? url1 : url;
//    $("#typeName").ajaxCheck({
//        url: urlVal,
//        alertText: "* 该分类已存在",
//        alertTextOk: "* 该分类可用"
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
