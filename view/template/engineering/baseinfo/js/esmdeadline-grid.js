var show_page = function() {
	$("#grid").yxgrid("reload");
};

$(function() {
	//表格部分处理
	$("#grid").yxgrid({
		model : 'engineering_baseinfo_esmdeadline',
		title : '日志填报截止期限 - {截止月份} 月 {截止日} 日 为上个月日志截止填报日期 ',
		isDelAction : false,
        isAddAction : false,
        isViewAction : false,
		//列信息
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'month',
            display : '截止月份',
            sortable : true,
            width : 80
        }, {
            name : 'day',
            display : '截止日',
            sortable : true,
            width : 80
        }, {
            name : 'saveDayForSer',
            display : '服务保护期',
            sortable : true,
            width : 80
        }, {
            name : 'saveDayForPro',
            display : '产品保护期',
            sortable : true,
            width : 80
        }, {
            name : 'useRange',
            display : '应用范围',
            sortable : true,
            width : 150
        }, {
            name : 'useRangeId',
            display : '应用范围ID',
            sortable : true,
            width : 150,
            hide : true
        }, {
            name : 'remark',
            display : '备注',
            sortable : true,
            width : 300
        }],
        toEditConfig: {
            action: 'toEdit'
        },
		sortorder : "ASC",
		sortname : "month"
	});
});