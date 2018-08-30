var show_page = function(page) {
	$("#applyResumeGrid").yxgrid("reload");
};

$(function() {
	buttonArr = [{
		name : 'inone',
		text : "添加简历",
		icon : 'add',
		action : function(row) {
			showModalWin ("?model=hr_recruitment_applyResume&action=toSelect&gridName=resumeGrid"
				+ "&id=" + $("#id").val(),"1")
		}
	},{
		name : 'add',
		text : "新增简历",
		icon : 'add',
		action : function(row) {
			showModalWin ("?model=hr_recruitment_resume&action=toAdd"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700&type=apply&id=" + $("#id").val(),"1")
		}
	}];

	//隐藏添加简历和新增简历数组
	var state = new Array('取消' ,'暂停' ,'提交' ,'未下达');
	if($.inArray($("#stateC").val() ,state) != '-1' || $("#ExaStatus").val() == "打回") {
		buttonArr = [];
	}

	$("#applyResumeGrid").yxgrid({
		model : 'hr_recruitment_applyResume',
		title : '增员申请简历库',
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isOpButton : false,
		bodyAlign : 'center',
		param : {
			parentId : $("#id").val()
		},

		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '申请编号',
			sortable : true,
			width:120
		},{
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id=' + row.resumeId + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		},{
			name : 'applicantName',
			display : '应聘者姓名',
			sortable : true,
			width:60
		},{
			name : 'sex',
			display : '性别',
			sortable : true,
			width:60
		},{
			name : 'workSeniority',
			display : '工作年限',
			sortable : true,
			width:60
		},{
			name : 'phone',
			display : '联系电话',
			sortable : true
		},{
			name : 'email',
			display : '电子邮箱',
			sortable : true,
			width : 150
		},{
			name : 'stateC',
			display : '状态'
		}],

		menusEx : [{
			text : '查看简历',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
					+ row.resumeId + "&skey=" + row['skey_'],'1');
			}
		},{
			text : '通知面试',
			icon : 'edit',
			showMenuFn: function(row) {
				if (row.state == 1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showModalWin('?model=hr_recruitment_invitation&action=toapplyAdd&id='+row.id+'&applyid='
					+ row.parentId + "&resumeid=" + row.resumeId,'1');
			}
		},{
			text:"发送录用通知",
			icon:'add',
			showMenuFn:function(row){
				if(row.stateC=='人才初选'){
					return true;
				}
				return false;
			},
			action:function(row){
				showModalWin('?model=hr_recruitment_invitation&action=sendNotify&interviewType=1&resumeId='+row.resumeId+'&applyid='
					+ row.parentId + "&resumeid=" + row.resumeId+"&applyResumeId="+row.id,'1');
			}
		},{
			text : '加入黑名单',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.stateC != '黑名单'&&row.stateC !='已入职')
					return true;
				else
					return false;
			},
			action : function(row) {
				$.ajax({
					type : "POST",
					url : "?model=hr_recruitment_applyResume&action=toBlack",
					data : {
						id : row.id
					},
					success:function(msg){
						if(msg == 1) {
							if (window.show_page != "undefined") {
								show_page();
							} else {
								g.reload();
							}
							alert("加入黑名单成功！~");
						} else if (msg == 3) {
							alert("加入失败，因为已经加入了！");
						} else {
							alert("加入失败！~");
						}
					}
				});
			}
		},{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_applyResume&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								show_page();
							}
						}
					});
				}
			}
		}],

		buttonsEx : buttonArr,
		toEditConfig : {
			action : 'toEdit'
		},

		toViewConfig : {
			action : 'toView'
		},

		searchitems : [{
			display : "简历编号",
			name : 'resumeCode'
		},{
			display : "应聘者名称",
			name : 'applicantName'
		}]
	});
});