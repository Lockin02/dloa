$(document).ready(function () {

    // 新增分类信息 选择物料类型
    $("#goodsTypeName").yxcombotree({
        hiddenId: 'goodsTypeId',
        treeOptions: {
            event: {
                "node_click": function (event, treeId, treeNode) {
                },
                "node_change": function (event, treeId, treeNode) {

                }
            },
            url: "?model=goods_goods_goodstype&action=getTreeData"
        }
    });
    validate({
        goodsName: {
            required: true

        },
        exeDeptName: {
            required: true

        },
        osGoodsName: {
            required: true

        }
    });

//    $("#auditDeptName").yxselect_dept({
//        hiddenId: 'auditDeptCode',
//        mode: 'check'
//    });
    //执行部门
    $("#auditDeptName").yxcombogrid_datadict({
        height : 250,
        valueCol : 'dataCode',
        hiddenId : 'auditDeptCode',
        gridOptions : {
            isTitle : true,
            param : {"parentCode":"GCSCX"},
            showcheckbox : true,
            event : {
                'row_dblclick' : function(e, row, data){

                }
            },
            // 快速搜索
            searchitems : [{
                display : '名称',
                name : 'dataName'
            }]
        }
    });
});


function setOsGoodName(){
    var goodsName=$("#goodsName").val();
    var osGoodsName=$("#osGoodsName").val();
    if(osGoodsName==""){
        $("#osGoodsName").val(goodsName);
    }
}