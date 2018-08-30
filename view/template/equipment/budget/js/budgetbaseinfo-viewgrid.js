var show_page = function(page) {
    $("#budgetbaseinfoGrid").yxgrid("reload");
};
$(function() {
    $("#budgetTypeTree").yxtree({
        url : '?model=equipment_budget_budgetType&action=getTreeData',
        event : {
            "node_click" : function(event, treeId, treeNode) {
                var goodsbaseinfoGrid = $("#budgetbaseinfoGrid").data('yxgrid');
                goodsbaseinfoGrid.options.param['budgetTypeId'] = treeNode.id;
                $("#parentName").val(treeNode.name);
                $("#parentId").val(treeNode.id);
                goodsbaseinfoGrid.reload();
            }
        }
    });
        $("#budgetbaseinfoGrid").yxgrid({
            model : 'equipment_budget_budgetbaseinfo',
            param : {
                goodsTypeId : -1
            },
            showcheckbox : false,
            isDelAction : false,
            isEditAction : false,
            isViewAction : false,
            isAddAction : false,
            title : '设备管理',
            // 列信息
            colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            }, {
                name : 'budgetTypeName',
                display : '所属分类',
                sortable : true,
                width : 100
            }, {
                name : 'equCode',
                display : '物料编号',
                sortable : true,
                width : 100
            }, {
                name : 'equName',
                display : '物料名称',
                sortable : true,
                width : 100
            }, {
                name : 'pattern',
                display : '规格型号',
                sortable : true,
                width : 100
            }, {
                name : 'brand',
                display : '品牌',
                sortable : true,
                width : 60
            }, {
                name : 'quotedPrice',
                display : '报价',
                sortable : true,
                width : 100,
                process : function(v) {
                    return moneyFormat2(v);
                }
            }, {
                name : 'useEndDate',
                display : '报价有效期',
                sortable : true
            }, {
                name : 'unitName',
                display : '单位',
                sortable : true
            }, {
                name : 'remark',
                display : '备注',
                sortable : true,
                width : 200
            }, {
                name : 'latestPrice',
                display : '最新采购价格',
                sortable : true,
                width : 80,
                process : function(v){
                    return v;
                }
            }, {
                name : 'useStatus',
                display : '是否启用',
                sortable : true,
                process:function(v){
                    if(v == '0' || v == ''){
                        return "关闭";
                    }else if(v == '1'){
                        return "启用";
                    }
                }
            }],
            searchitems : [{
                display : "所属分类",
                name : 'budgetTypeName'
            }, {
                display : "物料名称",
                name : 'equName'
            }, {
                display : "物料编号",
                name : 'equCode'
            }]
        });
});