var show_page = function(page) {
    $("#weeksGrid").yxgrid("reload");
};

var buttonsArr = [{
        text: "添加新周次",
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
        title : '日志周次列表',
        isAddAction: false,
        isEditAction: false,
        isDelAction: false,
        isViewAction: false,
        showcheckbox: false,

        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            display : '周次长号',
            name : 'longWeekNo',
            sortable : true,
            hide : true
        },{
            display : '周次编号',
            name : 'weekNo',
            sortable : true,
            width: 60
        },{
            display : '开始日期',
            name : 'beginDate',
            sortable : true
        },{
            display : '结束日期',
            name : 'endDate',
            sortable : true
        }],
        searchitems : [{
            display : "周次长号",
            name : 'longWeekNoSearch'
        },{
            display : "周次编号",
            name : 'weekNoSearch'
        },{
            display : "年份",
            name : 'yearsSearch'
        }],
        buttonsEx: buttonsArr
    });
});