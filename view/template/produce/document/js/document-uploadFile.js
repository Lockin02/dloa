$(document).ready(function() {
    var canEditEqu = $('#produceplan_editEqu').val();// 判断是否能编辑物料
    // 物料编辑
    var templateObj = $('#templateData_Box');
    templateObj.yxeditgrid({
        url: '?model=produce_plan_produceplan&action=classify',
        param: {
            id: $("#produceplan_taskId").val(),
            productCode: $("#produceplan_productCode").val(),
            dir: 'ASC'
        },
        objName: 'produceplan[items]',
        isFristRowDenyDel: true,
        isAddAndDel: (canEditEqu == 'no')? false : true,
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '物料Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '物料类型',
            width : 70,
            name: 'proType',
            tclass: 'readOnlyTxtMiddle',
            readonly: true
        }, {
            display: '物料编码',
            width : 80,
            name: 'productCode',
            tclass: (canEditEqu == 'no')? 'readOnlyTxtMiddle' : 'txtshort',
            process: function ($input) {
                if(canEditEqu == 'ok'){
                    var rowNum = $input.data("rowNum");
                    $input.yxcombogrid_product({
                        hiddenId: 'templateData_cmp_productId' + rowNum,
                        width: 500,
                        height: 300,
                        gridOptions: {
                            showcheckbox: false,
                            event: {
                                row_dblclick: function (e, row, data) {
                                    templateObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productCode").val(data.productCode);
                                    templateObj.yxeditgrid("getCmpByRowAndCol", rowNum, "productName").val(data.productName);
                                    templateObj.yxeditgrid("getCmpByRowAndCol", rowNum, "pattern").val(data.pattern);
                                    templateObj.yxeditgrid("getCmpByRowAndCol", rowNum, "unitName").val(data.unitName);
                                    templateObj.yxeditgrid("getCmpByRowAndCol", rowNum, "proType").val(data.proType);
                                    templateObj.yxeditgrid("getCmpByRowAndCol", rowNum, "proTypeId").val(data.proTypeId);
                                }
                            }
                        }
                    });
                }
            },
            validation: {},
            readonly: (canEditEqu == 'no')? true : false
        }, {
            display: '物料名称',
            width : 250,
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '规格型号',
            width : 250,
            name: 'pattern',
            tclass: 'readOnlyTxtMiddle',
            readonly: true
        }, {
            display: '单位名称',
            width : 70,
            name: 'unitName',
            tclass: 'readOnlyTxtMiddle',
            readonly: true
        }, {
            display: '数量',
            width : 30,
            name: 'num',
            tclass: (canEditEqu == 'no')? 'readOnlyTxtMiddle' : 'txtshort',
            validation: {
                custom: ['onlyNumber']
            },
            readonly: (canEditEqu == 'no')? true : false
        }]
    });
});