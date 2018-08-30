var show_page = function(page) {
    $("#weeksGrid").yxgrid("reload");
};

var buttonsArr = [{
        text: "������ܴ�",
        icon: 'add',
        action: function (row) {
            showThickboxWin('?model=engineering_baseinfo_week&action=toAddWeeks'
                + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=620');
        }
    }];

$(function() {
    $("#weeksGrid").yxgrid({
        model : 'engineering_baseinfo_week',
        action : 'getAllWeeksJson',
        title : '��־�ܴ��б�',
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        showcheckbox: false,

        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            display : '�ܴγ���',
            name : 'longWeekNo',
            sortable : true,
            hide : true
        },{
            display : '�ܴα��',
            name : 'weekNo',
            sortable : true,
            width: 60
        },{
            display : '��ʼ����',
            name : 'beginDate',
            sortable : true
        },{
            display : '��������',
            name : 'endDate',
            sortable : true
        }],
        searchitems : [{
            display : "�ܴγ���",
            name : 'longWeekNoSearch'
        },{
            display : "�ܴα��",
            name : 'weekNoSearch'
        },{
            display : "���",
            name : 'yearsSearch'
        }],
        buttonsEx: buttonsArr
    });
});