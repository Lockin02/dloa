var show_page = function(page) {
	$("#esmworklogGrid").yxgrid("reload");
};

$(function() {
    $("#esmworklogGrid").yxgrid({
        model : 'engineering_worklog_esmworklog',
        title : '工作日志',
		showcheckbox : false,
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }         ,{
            name : 'weekId',
            display : '周日志id',
            sortable : true,
            hide : true
        }         ,{
            name : 'executionDate',
            display : '执行日期',
            sortable : true
        }         ,{
            name : 'proCode',
            display : '所在城市编码',
            sortable : true,
            hide : true
        }         ,{
            name : 'proName',
            display : '所在城市',
            sortable : true
        }         ,{
            name : 'workloadDay',
            display : '当日投入工作量',
            sortable : true
        }         ,{
            name : 'description',
            display : '工作描述',
            sortable : true,
            width : 200
//        }         ,{
//            name : 'problem',
//            display : '存在问题',
//            sortable : true,
//            width : 200
        }         ,{
            name : 'createId',
            display : '创建人Id',
            sortable : true,
            hide : true
        }         ,{
            name : 'createName',
            display : '创建人名称',
            sortable : true,
            hide : true
        }         ,{
            name : 'createTime',
            display : '创建时间',
            sortable : true,
            hide : true
        }         ,{
            name : 'updateId',
            display : '修改人Id',
            sortable : true,
            hide : true
        }         ,{
            name : 'updateName',
            display : '修改人名称',
            sortable : true,
            hide : true
        }         ,{
            name : 'updateTime',
            display : '修改时间',
            sortable : true,
            width : 150
        }         ],
		searchitems : [{
				display : "执行日期",
				name : 'executionDate'
			}, {
				display : "所在城市",
				name : 'proName'
			}]
    });
});