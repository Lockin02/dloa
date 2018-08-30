$(document).ready(function() {
    //缓存质检内容表
    var itemTableObj = $("#itemTable");

    itemTableObj.yxeditgrid({
        objName: 'protemplate[items]',
        url: "?model=stock_template_protemplateitem&action=listJson",
        param: {
            "mainId": $("#id").val()
        },
        event: {
            'reloadData': function(e) {

},
            'removeRow': function() {

}
        },
        type: 'view',
        title: '物料清单',
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        },
        {
            name: 'productId',
            display: '物料Id',
            type: 'hidden'
        },
        {
            name: 'productCode',
            display: '物料编号'
        },
        {
            name: 'productName',
            display: '物料名称'
        },
        {
            name: 'pattern',
            display: '规格号',
        },
        {
            name: 'unitName',
            display: '单位',
        },
        {
            name: 'actNum',
            display: '数量',
        }]
    });
});