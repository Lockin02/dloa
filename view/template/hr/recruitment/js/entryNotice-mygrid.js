var show_page = function(page) {
	$("#entryNoticeGrid").yxgrid("reload");
};
$(function() {
	$("#entryNoticeGrid").yxgrid({
		model : 'hr_recruitment_entryNotice',
		title : '我的入职名单',
		param:{
			createId: $("#userid").val()
		},
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		}, {
			name : 'formDate',
			display : '单据日期',
			sortable : true
		}, {
			name : 'interviewType',
			display : '面试类型',
			sortable : true,
			process : function(v){
				if(v==1)
					return "增员申请";
				else if(v==2)
					return "内部推荐";
			}
		}, {
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\")'>" + v + "</a>";
				}
		}, {
			name : 'userName',
			display : '姓名',
			sortable : true
		}, {
			name : 'sex',
			display : '性别',
			sortable : true
		}, {
			name : 'phone',
			display : '联系电话',
			sortable : true
		}, {
			name : 'applyCode',
			display : '职位申请编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" + row.applyId +"\")'>" + v + "</a>";
			}
		}, {
			name : 'email',
			display : '电子邮箱',
			sortable : true
		}, {
			name : 'positionsName',
			display : '应聘岗位',
			sortable : true
		}, {
			name : 'developPositionName',
			display : '研发职位',
			sortable : true
		}, {
			name : 'deptName',
			display : '用人部门',
			sortable : true
		}, {
			name : 'useHireTypeName',
			display : '录用形式',
			sortable : true

		}, {
			name : 'useJobName',
			display : '职位名称',
			sortable : true
		}, {
			name : 'useAreaName',
			display : '归属区域或支撑中心',
			sortable : true
		}, {
			name : 'useDemandEqu',
			display : '需求设备',
			sortable : true
		}, {
			name : 'useSign',
			display : '签订《竞业限制协议》',
			sortable : true
		}, {
			name : 'probation',
			display : '试用期',
			sortable : true
		}, {
			name : 'contractYear',
			display : '合同期限',
			sortable : true
		}, {
			name : 'addType',
			display : '增员类型',
			sortable : true
		}, {
			name : 'hrSourceType1Name',
			display : '简历来源大类',
			sortable : true
		}, {
			name : 'hrJobName',
			display : '录用职位名称',
			sortable : true
		}, {
			name : 'hrIsManageJob',
			display : '是否管理岗',
			sortable : true
		}, {
			name : 'entryDate',
			display : '入职日期',
			sortable : true
		}, {
			name : 'stateC',
			display : '状态'
		}],
		toViewConfig : {
			toViewFn : function (p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_recruitment_entryNotice&action=toView&id=" + rowData[p.keyField]);
				} else {
					alert('请选择一行记录！');
				}
			}
		},

		toEditConfig : {
			action : 'toEdit'
		},
		searchitems : [{
			display : "用人部门",
			name : 'deptName'
		},
		{
			display : "应聘岗位",
			name : 'positionsName'
		},
		{
			display : "姓名",
			name : 'userName'
		},
		{
			display : "增员类型",
			name : 'addType'
		}]
	});
});