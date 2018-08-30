var show_page = function () {
    $("#goodsbaseinfoGrid").yxgrid("reload");
};
$(function () {
    $("#goodsTypeTree").yxtree({
        url: '?model=goods_goods_goodstype&action=getTreeData&isSale=' + $("#isSale").val(),
        event: {
            "node_click": function (event, treeId, treeNode) {
                var goodsbaseinfoGrid = $("#goodsbaseinfoGrid").data('yxgrid');
                goodsbaseinfoGrid.options.param['goodsTypeId'] = treeNode.id;
                $("#parentName").val(treeNode.name);
                $("#parentId").val(treeNode.id);
                goodsbaseinfoGrid.reload();
            }
        }
    });

    // 继承上面的事件
    var combogrid = window.dialogArguments[0];
    var p = combogrid.options;
    var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
    if (eventStr.row_dblclick) {
        var dbclickFunLast = eventStr.row_dblclick;
        eventStr.row_dblclick = function(e, row, data) {
            dbclickFunLast(e, row, data);
            window.returnValue = row.data('data');
            window.close();
        };
    } else {
        eventStr.row_dblclick = function(e, row, data) {
            window.returnValue = row.data('data');
            window.close();
        };
    }

    var gridOptions = combogrid.options.gridOptions;

    $("#goodsbaseinfoGrid").yxgrid({
        model: 'goods_goods_goodsbaseinfo',
        action : gridOptions.action,
        param : gridOptions.param,
        title: '双击选择产品',
        showcheckbox: false,
        isAddAction: false,
        isViewAction: false,
        isDelAction: false,
        isEditAction: false,
        autoload: false,
        isOpButton : false,
        // 列信息
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'goodsTypeName',
            display: '所属分类名称',
            sortable: true,
            width: 80
        }, {
            name: 'goodsName',
            display: '产品名称',
            sortable: true,
            width: 200,
            process : function(v,row){
                return "<a href='javascript:void(0);' onclick='showThickboxWin(\"?model=goods_goods_properties&action=toPreView&goodsId="
                + row.id
                + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\")'>"+ v +'</a>';
            }
        }, {
            name: 'exeDeptName',
            display: '产品线',
            sortable: true,
            width: 80
        }, {
            name: 'unitName',
            display: '单位',
            sortable: true,
            width: 50
        }, {
            name: 'useStatus',
            display: '状态',
            sortable: true,
            datacode: 'WLSTATUS',
            width: 50
        }, {
            name: 'remark',
            display: '备注',
            sortable: true,
            width: 250
        }, {
            name: 'createName',
            display: '创建人',
            sortable: true,
            hide: true
        }, {
            name: 'createTime',
            display: '创建日期',
            sortable: true,
            hide: true
        }, {
            name: 'updateName',
            display: '修改人',
            sortable: true,
            hide: true
        }, {
            name: 'updateTime',
            display: '修改日期',
            sortable: true,
            hide: true
        }],
        searchitems: [
            {
                display: "产品名称",
                name: 'goodsName'
            }
        ],
        sortname : gridOptions.sortname,
        sortorder : gridOptions.sortorder,
        // 把事件复制过来
        event : eventStr
    });
});