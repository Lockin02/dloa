var show_page = function(page) {
	$("#recomResumeGrid").yxgrid("reload");
};
$(function() {
	buttonArr = [{
			name : 'inone',
			text : "添加简历",
			icon : 'add',
			action : function(row) {
				showModalWin ("?model=hr_recruitment_recomResume&action=toSelect&gridName=resumeGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700&id=" + $("#id").val())
			}
		},{
			name : 'add',
			text : "新增简历",
			icon : 'add',
			action : function(row) {
				showModalWin ("?model=hr_recruitment_resume&action=toAdd"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700&type=recommend&id=" + $("#id").val())
			}
		}];
	var state=['不通过','未审核'];
	if($.inArray($("#stateC").val(),state)!=-1){
		buttonArr=[];
	}
	$("#recomResumeGrid").yxgrid({
		model : 'hr_recruitment_recomResume',
		title : '内部推荐简历库',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',
		//列信息
		param:{
			parentId:$("#id").val()
		},
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '申请编号',
			sortable : true
		}, {
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\",1)'>" + v + "</a>";
				}

		}, {
			name : 'applicantName',
			display : '应聘者姓名',
			width:80,
			sortable : true
		}, {
			name : 'sex',
			display : '性别',
			width:60,
			sortable : true
		}, {
			name : 'workSeniority',
			display : '工作年限',
			width:60,
			sortable : true
		}, {
			name : 'phone',
			display : '联系电话',
			sortable : true
		}, {
			name : 'email',
			display : '电子邮箱',
			sortable : true
		}, {
			name : 'stateC',
			display : '状态'
		}],
		buttonsEx : buttonArr,
		menusEx : [{
			text : '查看简历',
			icon : 'view',
			action : function(row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id='
							+ row.resumeId + "&skey=" + row['skey_']);
			}
		},{
			text : '通知面试',
			icon : 'edit',
			showMenuFn: function(row) {
				if (row.state == 1)
					return true;
				 else
					return false;
			},
			action : function(row) {
					showModalWin('?model=hr_recruitment_invitation&action=torecomAdd&type=recommend&id='+row.id+'&applyid='
							+ row.parentId + "&resumeid=" + row.resumeId);
			}
		},{
			text : '发送录用通知',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.stateC == '人才初选'&&$("#stateC").val()!="不通过")
					return true;
				 else
					return false;
			},
			action : function(row) {
				showModalWin('?model=hr_recruitment_invitation&action=sendNotify&interviewType=2&resumeId='+row.resumeId+'&applyid='
						+ row.parentId + "&resumeid=" + row.resumeId+"&applyResumeId="+row.id);
			}
		},{
			text : '加入黑名单',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.stateC != '黑名单')
					return true;
				 else
					return false;
			},
			action : function(row) {
					$.ajax({
							type : "POST",
							url : "?model=hr_recruitment_recomResume&action=toBlack",
							data : {
								id : row.id
							},
							success:function(msg){
								    //alert(msg);
			    		            if(msg==1){
			    		            	if (window.show_page != "undefined") {
											show_page();
										} else {
											g.reload();
										}
										alert("加入黑名单成功！~");
			    		            }else if(msg==3){
			    		            	alert("加入失败，因为已经加入了！");
			    		            }else{
			    		            	alert("加入失败！~");
			    		            }
			    		         }
					});
			}
		}, {
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
						url : "?model=hr_recruitment_recomResume&action=ajaxdeletes",
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
			display : "应聘者姓名",
			name : 'applicantName'
		},{
			display : "申请编号",
			name : 'formCode'
		}]
	});
});