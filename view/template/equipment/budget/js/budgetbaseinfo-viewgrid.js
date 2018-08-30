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
            title : '�豸����',
            // ����Ϣ
            colModel : [{
                display : 'id',
                name : 'id',
                sortable : true,
                hide : true
            }, {
                name : 'budgetTypeName',
                display : '��������',
                sortable : true,
                width : 100
            }, {
                name : 'equCode',
                display : '���ϱ��',
                sortable : true,
                width : 100
            }, {
                name : 'equName',
                display : '��������',
                sortable : true,
                width : 100
            }, {
                name : 'pattern',
                display : '����ͺ�',
                sortable : true,
                width : 100
            }, {
                name : 'brand',
                display : 'Ʒ��',
                sortable : true,
                width : 60
            }, {
                name : 'quotedPrice',
                display : '����',
                sortable : true,
                width : 100,
                process : function(v) {
                    return moneyFormat2(v);
                }
            }, {
                name : 'useEndDate',
                display : '������Ч��',
                sortable : true
            }, {
                name : 'unitName',
                display : '��λ',
                sortable : true
            }, {
                name : 'remark',
                display : '��ע',
                sortable : true,
                width : 200
            }, {
                name : 'latestPrice',
                display : '���²ɹ��۸�',
                sortable : true,
                width : 80,
                process : function(v){
                    return v;
                }
            }, {
                name : 'useStatus',
                display : '�Ƿ�����',
                sortable : true,
                process:function(v){
                    if(v == '0' || v == ''){
                        return "�ر�";
                    }else if(v == '1'){
                        return "����";
                    }
                }
            }],
            searchitems : [{
                display : "��������",
                name : 'budgetTypeName'
            }, {
                display : "��������",
                name : 'equName'
            }, {
                display : "���ϱ��",
                name : 'equCode'
            }]
        });
});