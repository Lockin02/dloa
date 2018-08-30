$(function() {
	var userId = $('#userId').val();
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonMyPj',
		title : '我的项目',
		// 扩展右键菜单
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action :function(row) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
						+ "&id="
						+ row.id);
				}else{
					alert("请选中一条数据");
				}
			}
		},
		{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if( row.managerId == userId ){
					if (row.status != '7' && row.status != '8' &&row.status != '2'&&row.status != '6' &&row.status != '9' ) {
						return true;
					}
				}
				return false;
			},
			action : function(row) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
						+ "&id="
						+ row.id);
				}else{
					alert("请选中一条数据");
				}
			}
		}, {
			text : '打开',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6'&& row.managerId == userId ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=openTab"
							+ "&id=" + row.id);
				} else {
					alert("请选中一条数据");
				}
			}
		},
		{
			text : '接收',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '9'&& row.managerId == userId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确确认接收?"))) {
					$.ajax({
						type : "POST",
						url : "?model=engineering_project_esmproject&action=receive",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('接收成功！');
								$("#esmprojectGrid").esmprojectgrid("reload");
							}else{
								alert("接收失败");
							}
						}
					});
				}
			}
		},
		{
			text : '填写进展',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6'&& row.managerId == userId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=toProgress"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 200 + "&width=" + 600
					);
			}
		},
		{
			text : '关闭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6'&& row.managerId == userId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=closeProject"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 200 + "&width=" + 600
					);
			}
		},
		{
			name : 'exam',
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row) {
				if( row.managerId == userId ){
					if (row.status == '1'||row.status == '10'||row.status == '4') {
						return true;
					}
				}
				return false;
			},
			action : function(row) {
				if(row.projectCode == "" || row.officeName ==""||row.planDateStart == "" || row.planDateClose == "" ){
					alert('相关信息未填写完毕,请先填写');
					return false;
				}else{
					location = 'controller/engineering/project/ewf_index.php?actTo=ewfSelect&formName=工程项目审核&examCode=oa_esm_project&billId='
						+ row.id
				}
			}
		}]

	});

});