var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};

$(function () {
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : '转正考核评估表',
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		param : {
			userAccount : $("#userid").val(),
			statusArr : "1,2,3,4,5,6,7,8"
		},
		isOpButton:false,
		bodyAlign:'center',

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
			},
			width : 130
		},{
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width : 80
		},{
			name : 'statusC',
			display : '状态',
			width : 60
		},{
			name : 'ExaStatus',
			display : '审核状态',
			sortable : true,
			width : 70
		},{
			name : 'userNo',
			display : '员工编号',
			sortable : true//,
			// process : function(v,row){
			// 	return "<a href='#' onclick='showOpenWin(\"?model=hr_personnel_personnel&action=toCodeView&userNo="+v+"\")'>"+v+"</a>"
			// },
			width : 80
		},{
			name : 'useName',
			display : '姓名',
			sortable : true,
			width : 70
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width:30
		},{
			name : 'deptName',
			display : '部门',
			sortable : true
		},{
			name : 'positionName',
			display : '职位',
			sortable : true,
			width : 80
		},{
			name : 'permanentType',
			display : '转正类型',
			sortable : true,
			width : 80
		},{
			name : 'begintime',
			display : '入职时间',
			sortable : true,
			width : 80
		},{
			name : 'finishtime',
			display : '计划转正时间',
			sortable : true,
			width : 80
		},{
			name : 'permanentDate',
			display : '实际转正日期',
			width : 80,
			process : function(v ,row) {
				if(row.ExaStatus == '完成') {
					return v;
				}else{
					return '';
				}
			}
		},{
			name : 'selfScore',
			display : '考核总分',
			sortable : true,
			width : 80
		},{
			name : 'totalScore',
			display : '复核成绩',
			sortable : true,
			width : 80
		}],

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.status == 1) {
					return true;
				} else
				return false;
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toEdit&id=" + rowData[p.keyField]);
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
				}
			}
		},

		menusEx : [{
			text : '提交导师审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 1&& row.planstatus == 0&& row.summarystatus == 0&& row.tutorId!='') {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveMaster",
						data : {
							id : g.getCheckedRowIds().toString()
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								g.reload();
							} else {
								alert('提交失败！');
							}
						}
					});
				}
			}
		},{
			text : '提交领导审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 1 && row.planstatus == 0 && row.summarystatus == 0 && row.tutorId == '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("确定要提交?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=giveLeader",
						data : {
							id : g.getCheckedRowIds().toString()
						},
						success : function(msg) {
							if (msg == 1) {
								alert('提交成功！');
								g.reload();
							}else{
								//alert(msg);
								alert('提交失败！');
							}
						}
					});
				}
			}
		},{
			text : '提交总监审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 1 && row.planstatus == 1 && row.summarystatus == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				parent.location = "?model=hr_permanent_examine&action=isAccept&id=" + row.id + "&schemeId=" + row.schemeId;
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.status == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(rowData, rows, rowIds, g) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_permanent_examine&action=ajaxdeletes",
						data : {
							id : g.getCheckedRowIds().toString()
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								g.reload();
							}
						}
					});
				}
			}
		},{
			text : '填写员工意见',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 6) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=hr_permanent_examine&action=tolastedit&id=" + row.id);
			}
		}]
	});
});