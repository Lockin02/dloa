$(function() {
	var thisHeight = document.documentElement.clientHeight - 40;
    $('#esmactivityGrid').treegrid({
		title : '项目任务',
		width : '98%',
		height : thisHeight,
		nowrap : false,
		rownumbers : true,
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
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "\",1,668,1000,"+row.id+")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNode&id=" + row.id + '&skey=' + row.skey_ + "\",1,668,1000,"+row.id+")'>" + v + "</a>";
					}
				}
			},{
				field : 'workRate',
				title : '工作占比',
				width : 70,
				formatter : function(v,row) {
					if(row.parentId == "-1"){
						return "<font style='font-weight:bold;'>" + v + " %</font>";
					}else{
						return v + " %";
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
				width : 60,
                formatter : function(v,row) {
                    return row.isTrial == '1' ? '--' : v;
                }
			},{
				field : 'workloadUnitName',
				title : '单位',
				width : 60
			},{
				field : 'workContent',
				title : '工作内容',
				width : 300
			}
		]],
		onBeforeLoad : function(row,param) {//动态设值取值路径
			if(row){
				if(row.id * 1 == row.id){
					$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + row.id;
				}else{
					$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val();
				}
			}else{
				$(this).treegrid('options').url =  '?model=engineering_activity_esmactivity&action=treeJson&projectId=' + $("#projectId").val() + "&parentId=" + $("#parentId").val();
			}
		},
		onContextMenu : function(e, row) {
			e.preventDefault();
			$(this).treegrid('unselectAll');
			$(this).treegrid('select', row.id);
			$('#menuDiv').menu('show', {
				left : e.pageX,
				top : e.pageY
			});
		}
	});
});

//原页面刷新方法
function show_page(){
	reload();
}

//刷新
function reload(){
	$('#esmactivityGrid').treegrid('reload');
}

//编辑任务
function editActivity(){
	var node = $('#esmactivityGrid').treegrid('getSelected');
	if (node){
		if(node.isTrial == 1){
			showOpenWin("?model=engineering_activity_esmactivity&action=toEditTrial&id="
					+ node.id  + "&skey=" + node.skey_ , 1, 400 ,800 ,node.id);
		}else{
			if((node.rgt - node.lft) == 1){
				showOpenWin("?model=engineering_activity_esmactivity&action=toEdit&id="
					+ node.id  + "&skey=" + node.skey_ , 1, 668 ,1000 ,node.id);
			}else{
				showOpenWin("?model=engineering_activity_esmactivity&action=toEditNode&id="
					+ node.id  + "&skey=" + node.skey_
					+ "&parentId=" + node._parentId , 1, 668 ,1000 ,node.id);
			}
		}
	}else{
		alert('请选择一个任务');
	}
}

//新增任务
function addActivity(){
	var node = $('#esmactivityGrid').treegrid('getSelected');
	if (node){
		if(node.isTrial == 1){
			alert('不能在此任务下新增任务！');
		}else{
			//如果选中任务没有子任务，则提示
			if((node.rgt - node.lft) == 1){
				if(confirm("新增第一个下级任务会将【" + node.activityName + "】的相关内容转入新任务中，确认建立吗？")){
					showOpenWin("?model=engineering_activity_esmactivity&action=toMove&parentId="
						+ node.id + "&projectId=" + $("#projectId").val() , 1, 668 ,1000 ,node.id );
				}
			}else{
				showOpenWin("?model=engineering_activity_esmactivity&action=toAdd&parentId="
					+ node.id + "&projectId=" + $("#projectId").val() , 1, 668 ,1000 ,node.id );
			}
		}
	}else{
		showOpenWin("?model=engineering_activity_esmactivity&action=toAdd&parentId=-1"
			+ "&projectId=" + $("#projectId").val() , 1, 668 ,1000 );
	}
}

//删除任务
function removeActivity(){
	var node = $('#esmactivityGrid').treegrid('getSelected');
	if (node){
		if(node.isTrial == 1){
			alert("该任务不能删除！");
		}else{
			//判断提示信息
			if((node.rgt - node.lft) == 1){
				var alertText = '确认要删除？';
			}else{
				var alertText = '删除此任务，会将下级任务一并删除，确认要执行此操作吗？';
			}
			if(confirm(alertText)){
				//异步删除任务
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_activity_esmactivity&action=ajaxdeletes",
				    data: {
				    	id : node.id
				    },
				    async: false,
				    success: function(data){
				   		if(data == "1"){
							alert('删除成功');
							reload();
				   	    }else{
							alert('删除失败');
							return false;
				   	    }
					}
				});
			}
		}
	}else{
		alert('请选择一个任务');
	}
}

//取消选中
function cancelSelect(){
	$('#esmactivityGrid').treegrid('unselectAll');
}