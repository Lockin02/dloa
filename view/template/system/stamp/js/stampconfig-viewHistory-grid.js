var show_page = function(page) {
    $("#stampconfigVHGrid").yxgrid("reload");
};
$(function() {
    $("#stampconfigVHGrid").yxgrid({
        model : 'system_stamp_stampconfig',
        action : 'jsonHistory',
        param: {id:$("#parentId").val()},
        title : '������ʷ���ñ�',
        isOpButton : false,
        isViewAction: false,
        isEditAction: false,
        isDelAction: false,
        isAddAction: false,
        showcheckbox : false,
        //����Ϣ
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
            display : 'ӡ������',
            sortable : true,
            width : 120
        },{
            name : 'principalName',
            display : '������',
            sortable : true,
            width : 95
        },{
            name : 'businessBelongId',
            display : '��˾ID',
            sortable : true,
            hide : true
        },{
            name : 'businessBelongName',
            display : '��˾',
            width : 60,
            sortable : false
        },{
            name : 'typeName',
            display : 'ӡ�����',
            width : 70,
            sortable : true
        },{
            name : 'typeId',
            display : 'ӡ�����ID',
            sortable : true,
            hide : true
        },{
            name : 'startTime',
            display : '��ʼʱ��',
            sortable : true,
            width : 80
        },{
            name : 'endTime',
            display : '����ʱ��',
            sortable : true,
            width : 80
        },{
            name : 'remark',
            display : '��ע',
            sortable : true,
            width : 170
        }],
        searchitems : [{
            display : 'ӡ������',
            name : 'stampNameSearch1'
        },{
            display : '������',
            name : 'principalNameSearch1'
        }]
    });
});