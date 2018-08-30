var show_page = function(page) {
	$("#projectGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [
        {
			name : 'view',
			text : "高级查询",
			icon : 'view',
			action : function() {
				showThickboxWin("?model=hr_project_project&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=900');
			}
        }];

	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_project_project&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
       excelOutSelect = {
			name : 'excelOutAllArr',
			text : "自定义导出信息",
			icon : 'excel',
			action : function() {
				if($("#totalSize").val()<1){
					alert("没有可导出的记录");
				}else{
					document.getElementById("form2").submit();
				}
			}
        }

	$.ajax({
		type : 'POST',
		url : '?model=hr_project_project&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutSelect);
			}
		}
	});
	$("#projectGrid").yxgrid({
		model : 'hr_project_project',
		title : '项目经历',
		isAddAction : true,
		isEditAction : true,
		isOpButton:false,
		bodyAlign:'center',
      event:{'afterload':function(data,g){
      	$("#listSql").val(g.listSql);
      	$("#totalSize").val(g.totalSize);
      }},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userNo',
			display : '员工编号',
			sortable : true,
  			width:80,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_project_project&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		}, {
			name : 'userName',
			display : '员工姓名',
  			width:80,
			sortable : true
		}, {
			name : 'deptName',
			display : '部门',
			sortable : true
		}, {
			name : 'jobName',
			display : '职位',
			sortable : true
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true,
			width:150
		}, {
			name : 'projectManager',
			display : '项目经理',
			sortable : true
		},  {
			name : 'beginDate',
			display : '参加项目开始时间',
			sortable : true
		}, {
			name : 'closeDate',
			display : '参加项目结束时间',
			sortable : true
		}],
       buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
				// 默认搜索字段名
				sortname : "userNo",
				// 默认搜索顺序
				sortorder : "asc",
		/**
		 * 快速搜索
		 */
		searchitems : [{
						display : "员工编号",
						name : 'userNoSearch'
					},{
						display : "员工姓名",
						name : 'userNameSearch'
					},{
						display : "部门",
						name : 'deptNameSearch'
					},{
						display : "职位",
						name : 'jobNameSearch'
					},{
						display : "项目名称",
						name : 'projectNameSearch'
					},{
						display : "项目经理",
						name : 'projectManagerSearch'
					},{
						display : "参加项目开始时间",
						name : 'beginDateSearch'
					},{
						display : "参加项目结束时间",
						name : 'closeDateSearch'
					},{
						display : "在项目中的主要工作内容",
						name : 'projectContent'
					}]
	});
});