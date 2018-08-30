var show_page = function(page) {
	$("#applyResumeGrid").yxgrid("reload");
};
$(function() {
	$("#applyResumeGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '简历库',
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton:false,
		bodyAlign:'center',
		param:{
			resumeType_d:"1','2"
		},
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'resumeCode',
			display : '简历编号',
			sortable : true,
			width:180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id=' + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
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
			sortable : true,
			width : 150
		}, {
			name : 'post',
			display : '应聘职位',
			sortable : true,
			datacode : 'YPZW'
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		//扩展按钮
		buttonsEx : [{
			name : 'addin',
			text : '加入',
			icon : 'add',
			action : function(row, rows, grid) {
				if(rows){
					var checkedRowsIds=$("#applyResumeGrid").yxgrid("getCheckedRowIds").toString();
					$.ajax({
							type : "POST",
							url : "?model=hr_recruitment_applyResume&action=ajaxadds",
							data : {
								id : checkedRowsIds,
								applyid : $("#id").val()
							},
							success:function(msg){
			    		            if(msg==1){
										alert("加入成功！");
										window.close();
										self.opener.location.reload();
			    		            }else if(msg==2){
			    		            	alert("加入失败,该简历已添加到简历库");
			    		            }else{
			    		            	alert("加入失败！");
			    		            }
			    		         }
					});
				}
			}
		}],


		searchitems : [{
			display : "简历编号",
			name : 'resumeCode'
		}, {
			display : "应聘者名称",
			name : 'applicantName'
		}]
	});
});