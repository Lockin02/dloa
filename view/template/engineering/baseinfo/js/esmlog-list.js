$(function(){
    // 初始化查询列表
    initView();
});

// 查询列表
function initView() {
    // 基本列表，根据类型不同加载后面的列
    $("#grid").yxeditgrid('remove').yxeditgrid({
        url: '?model=engineering_baseinfo_esmlog&action=listJson',
        param: {
            projectId: $("#projectId").val(),
            period: $("#period").val()
        },
        type: 'view',
        event: {
            reloadData: function(e, g, data) {
                if (data.length == 0) {
                    $("#grid tbody").append("<tr class='tr_even'><td colspan='6'>暂无数据</td></tr>");
                }
            }
        },
        colModel: [{
            display: '操作人',
            name: 'userName',
            width: 80
        }, {
            display: '操作类型',
            name: 'operationType',
            width: 80
        }, {
            display: '操作时间',
            name: 'operationTime',
            align: 'left',
            width: 130
        }, {
            display: '操作内容',
            name: 'description',
            width: 600,
            align: 'left',
            process: function(v) {
                return "<p style='padding: 0 0 15px 0'>" + v + "</p>";
            }
        }, {
            display: '',
            name: ''
        }]
    });
}