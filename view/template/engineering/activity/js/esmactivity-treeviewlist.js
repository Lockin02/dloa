$(function() {
	var thisHeight = document.documentElement.clientHeight - 5;
    $('#esmactivityGrid').treegrid({
		url : '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val(),
		title : '项目任务',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
		animate : true,
		collapsible : true,
		idField : 'id',
		treeField : 'activityName',//树级索引
		fitColumns : false,//宽度适应
		pagination : false,//分页
		showFooter : true,//显示统计
		columns : [[
			{
				title : '任务名称',
				field : 'activityName',
				width : 210,
				formatter : function(v,row) {
					if(row.id == 'noId') return v;
					if((row.rgt - row.lft) == 1){
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNode&id=" + row.id + '&skey=' + row.skey_  + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
					}
				}
			},{
                field : 'workRate',
                title : '工作占比',
                width : 65,
                formatter : function(v,row) {
                    if(!row._parentId){
                        return "<font style='font-weight:bold;'>" + v + " %</font>";
                    }else{
                        return v + " %";
                    }
                }
            },{
                field : 'process',
                title : '工作进度',
                width : 70,
                formatter : formatProgress
            },{
                field : 'waitConfirmProcess',
                title : '待确认进度',
                width : 70,
                formatter : function(v) {
                    if(v){
                        return v + " %";
                    }
                }
            },{
                field : 'countProcess',
                title : '累计进度',
                width : 65,
                formatter : function(v) {
                    if(v){
                        return v + " %";
                    }
                }
            },{
                field : 'planProcess',
                title : '计划进度',
                width : 65,
                formatter : function(v) {
                    if(v){
                        return v + " %";
                    }
                }
            },{
                field : 'diffProcess',
                title : '进度差异',
                width : 65,
                formatter : function(v) {
                    v = ($("#isACatWithFallOutsourcing").val() == "1")? 0 : v;
                    if(v){
                        if(v*1 > 0){
                            return "<span class='red'>" + v + " %</span>";
                        }else{
                            return v + " %";
                        }
                    }
                }
            },{
                field : 'status',
                title : '任务状态',
                width : 60,
                formatter : function(v){
                    switch (v) {
                        case '0' : return '正常';
                        case '1' : return '<span class="blue">关闭</span>';
                        case '2' : return '<span class="red">暂停</span>';
                    }
                }
            },{
                field : 'planBeginDate',
                title : '预计开始',
                width : 80
            },{
                field : 'planEndDate',
                title : '预计结束',
                width : 80
            },{
                field : 'days',
                title : '预计工期',
                width : 60
            },{
                field : 'workload',
                title : '工作量',
                width : 50,
                formatter : function(v,row) {
                    return row.isTrial == '1' ? '--' : v;
                }
            },{
                field : 'workloadDone',
                title : '完成量',
                width : 50,
                formatter : function(v,row) {
                    if($("#isACatWithFallOutsourcing").val() == "1"){
                        return row.workloadCount;
                    }else {
                        if(row.isTrial == '1'){
                            return '--';
                        }
                        if((row.rgt - row.lft) == 1){
                            if(row.confirmDays*1 != 0){
                                return '<span class="blue" style="font-weight:bold;" title="进度修正日期：'
                                    + row.confirmDate +'\n修正人：'+ row.confirmName +'\n修正值：'+ row.confirmDays +'">' + v + '</span>';
                            }else{
                                return v;
                            }
                        }
                    }
                }
            },{
                field : 'workloadUnitName',
                title : '单位',
                width : 50
            },{
                field : 'workContent',
                title : '工作内容',
                width : 200
            }
        ]]
	});
});