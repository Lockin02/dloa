var show_page = function(page) {
	$("#positiondescriptGrid").yxgrid("reload");
};
$(function() {
	$("#positiondescriptGrid").yxgrid({
		model : 'hr_position_positiondescript',
		title : '职位说明书',
		isOpButton:false,
		bodyAlign:'center',
		//列信息

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'positionName',
			display : '职位名称',
			sortable : true ,
			process : function(v, row) {
				return "<a href='#' onclick='showOpenWin(\"?model=hr_position_positiondescript&action=toView&id="+row.id+"\")'>"+v+"</a>";
			}
		}, {
			name : 'deptName',
			display : '所在部门',
			sortable : true
		}, {
			name : 'rewardGrade',
			display : '薪资等级',
			sortable : true
		}, {
			name : 'superiorPosition',
			display : '上级职位',
			sortable : true
		}, {
			name : 'suborPosition',
			display : '下属职位',
			sortable : true
		}, {
			name : 'parallelPosition',
			display : '平行职位',
			sortable : true
		}, {
			display : '创建人',
			name : 'createName',
			sortable : true
		}],
		toAddConfig : {
			toAddFn : function() {
				showOpenWin("?model=hr_position_positiondescript&action=toAdd" );
			}
		},

		toEditConfig : {
			toEditFn : function(p,g) {
				if (g) {
					var set = g.getSelectedRow().data("data");
					showOpenWin("?model=hr_position_positiondescript&action=toEdit&id=" + set.id);
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				if (g) {
					var set = g.getSelectedRow().data("data");
					showOpenWin("?model=hr_position_positiondescript&action=toView&id=" + set.id);
				}
			}
		},/*
		menusEx : [{
			text : '提交审批',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '未审核'){
					return true;
				}else
					return false;
			},
			action : function(row, rows, grid) {
					parent.location = "controller/hr/position/ewf_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_position_description&formName=职位说明书";
			}

		}],
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
						text : '部门审批',
						value : '部门审批'
					}, {
						text : '打回',
						value : '打回'
					}, {
						text : '未审核',
						value : '未审批'
					}, {
						text : '完成',
						value : '完成'
					}]
		}],*/
		searchitems : [{
			display : "职位名称",
			name : 'positionName'
		}, {
			display : "所在部门",
			name : 'deptName'
		}, {
			display : "薪资等级",
			name : 'rewardGrade'
		}]
	});
});