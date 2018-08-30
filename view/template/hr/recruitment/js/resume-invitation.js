var show_page = function(page) {
	$("#talentGrid").yxgrid("reload");
};
$(function() {
	//表头按钮数组
	buttonsArr = [];
	$("#talentGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '面试通知简历库',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		isOpButton:false,
		bodyAlign:'center',
		customCode : 'resumeGrid',
		param : {
			resumeType_d : 2
		},
		// 扩展右键菜单

		menusEx : [{
			text : '查看简历',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=hr_recruitment_resume&action=toView&id='
						+ row.id + "&skey=" + row['skey_'],'1');
			}
		},{
				text : '通知面试',
				icon : 'edit',
				/*action : function(row) {
						showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id);
				},*/
				action : function(row) {
				/*
					//检查是否存在面试评估
					 $.ajax({
				         type:"POST",
				         url:"?model=hr_recruitment_interview&action=isAdded",
				         data:{
				         	resumeId:row.id
				         },
				         success:function(msg){
				            if(msg==0){//判断是否有面试评估
									if (window.confirm(("该简历已有面试评估,是否继续?"))) {
										showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id);
										return ;
									}
				            }
				         }
				     });*/
					//检查是否存在面试面试通知
					 $.ajax({
				         type:"POST",
				         url:"?model=hr_recruitment_invitation&action=ajaxCheckExistsResume",
				         data:{
				         	resumeId:row.id
				         },
				         success:function(msg){
				            if(msg!=0){//判断是否有面试评估
									if (window.confirm(("该简历已发送了面试通知,是否继续添加?"))) {
										showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id,'1');
									}
				            }else{
				            	showModalWin('?model=hr_recruitment_invitation&action=toAdd&resumeid=' + row.id,'1');
				            }
				         }
				     });

				}
		}
		],
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
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="点击查看简历" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'applicantName',
			display : '应聘者姓名',
			width:70,
			sortable : true
		}, {
			name : 'isInform',
			display : '面试通知',
			sortable : true,
			process : function(v, row) {
				if(v==1)return "已通知";
				else return "未通知";
			}
		}, {
			name : 'post',
			display : '应聘职位',
			sortable : true,
			datacode : 'YPZW'
		}, {
			name : 'phone',
			display : '联系电话',
			sortable : true
		}, {
			name : 'email',
			display : '电子邮箱',
			sortable : true,
			width : 200
		}],
		comboEx : [{
			text : '简历类型',
			key : 'resumeType',
			data : [{
				text : '公司简历',
				value : '0'
			}, {
				text : '员工简历',
				value : '1'
			}]
		}],
		buttonsEx : buttonsArr,

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