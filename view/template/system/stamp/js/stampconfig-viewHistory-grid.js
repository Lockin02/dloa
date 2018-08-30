var show_page = function(page) {
    $("#stampconfigVHGrid").yxgrid("reload");
};
$(function() {
    $("#stampconfigVHGrid").yxgrid({
        model : 'system_stamp_stampconfig',
        action : 'jsonHistory',
        param: {id:$("#parentId").val()},
        title : '盖章历史配置表',
        isOpButton : false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox : false,
        //列信息
        colModel : [{
            name : 'id',
            display : 'ID',
            sortable : true,
            width : 20,
            hide : true
        },{
            name : 'pid',
            display : 'parentId',
            sortable : true,
            hide : true
        },{
            name : 'stampName',
            display : '印章名称',
            sortable : true,
            width : 120
        },{
            name : 'principalName',
            display : '负责人',
            sortable : true,
            width : 95
        },{
            name : 'businessBelongId',
            display : '公司ID',
            sortable : true,
            hide : true
        },{
            name : 'businessBelongName',
            display : '公司',
            width : 60,
            sortable : false
        },{
            name : 'typeName',
            display : '印章类别',
            width : 70,
            sortable : true
        },{
            name : 'typeId',
            display : '印章类别ID',
            sortable : true,
            hide : true
        },{
            name : 'startTime',
            display : '开始时间',
            sortable : true,
            width : 80
        },{
            name : 'endTime',
            display : '结束时间',
            sortable : true,
            width : 80
        },{
            name : 'remark',
            display : '备注',
            sortable : true,
            width : 170
        }],
        searchitems : [{
            display : '印章名称',
            name : 'stampNameSearch1'
        },{
            display : '负责人',
            name : 'principalNameSearch1'
        }]
    });
});