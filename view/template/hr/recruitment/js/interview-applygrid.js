var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {


	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		title : '面试记录',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton:false,
		param : {
			parentId : $("#applyid").val(),
			interviewType : $("#interviewtype").val()
		},
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
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'formDate',
				display : '单据日期',
				sortable : true
			}, {
				name : 'userName',
				display : '姓名',
				sortable : true
			}, {
				name : 'sexy',
				display : '性别',
				sortable : true,
				width : 70
			}, {
				name : 'positionsName',
				display : '应聘岗位',
				sortable : true
			}, {
				name : 'deptState',
				display : '部门确认状态',
				sortable : true,
				process : function(v){
					if(v==1)
						return "已确认";
					else
					    return "未确认";
				}
			}, {
				name : 'hrState',
				display : '人力资源确认状态',
				sortable : true,
				process : function(v){
					if(v==1)
						return "已确认";
					else
					    return "未确认";
				}
			}, {
				name : 'stateC',
				display : '状态'
			}, {
				name : 'ExaStatus',
				display : '审核状态',
				sortable : true
			}, {
				name : 'deptName',
				display : '用人部门',
				sortable : true
			}, {
				name : 'hrRequire',
				display : '招聘需求',
				sortable : true
			}, {
				name : 'useInterviewResult',
				display : '面试结果',
				sortable : true,
				process : function(v){
					if(v==0)
						return "储备人才";
					else
						return "立即录用";
				}
			}, {
				name : 'hrSourceType1Name',
				display : '简历来源大类',
				sortable : true
			}, {
				name : 'hrSourceType2Name',
				display : '简历来源小类',
				sortable : true
			}],
		lockCol:['formCode','formDate','userName'],//锁定的列名
		toEditConfig : {
			toEditFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=lastedit&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=toView&id=" + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
//		menusEx : [{
//			text : '发送入职通知',
//			icon : 'edit',
//			action : function(row) {
//					showOpenWin("?model=hr_recruitment_entryNotice&action=toadd&id="+row.id);
//			}
//
//		}],
		searchitems : [{
			display : '姓名',
			name : 'userNameSearch'
		}, {
			display : '用人部门',
			name : 'deptNamSearche'
		}]
	});
});