$(function() {
    /**
     * 编号唯一性验证
     */
//    var url = "?model=product_terminal_functioninfo&action=checkRepeat";
//    var url1 = "?model=product_terminal_functioninfo&action=checkRepeat&id=" + $('#id').val();
//    var urlVal = $('#isEdit').val() == 1 ? url1 : url;
//    $("#functionName").ajaxCheck({
//        url: urlVal,
//        alertText: "* 该名称已存在",
//        alertTextOk: "* 该名称可用"
//    });
//    validate({
//        "productName": {
//            required: true
//        },
//        "typeName": {
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
                    $("#typeName").yxcombogrid_functiontype("remove");
                    $("#typeName").val('');
                    $("#typeId").val('');
                    functiontype();
                }
            }
        }
    });
   functiontype();

});
function functiontype(){
     $("#typeName").yxcombogrid_functiontype({
        nameCol: 'typeName',
        hiddenId: 'typeId',
        gridOptions: {
            showcheckbox: false,
            param:{
                'productId':$("#productId").val()
            },
            event: {
                'row_dblclick': function(e, row, data) {
                    $("#typeName").val(data.typeName);
                    $("#typeId").val(data.id);
                }
            }
        }
    });
}
