var show_page = function(page) {
	$("#applyResumeGrid").yxgrid("reload");
};
$(function() {
	$("#applyResumeGrid").yxgrid({
		model : 'hr_recruitment_resume',
		title : '������',
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton:false,
		bodyAlign:'center',
		param:{
			resumeType_d:"1','2"
		},
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			width:180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showModalWin(\'?model=hr_recruitment_resume&action=toView&id=' + row.id + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\',1)">' + "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'applicantName',
			display : 'ӦƸ������',
			width:80,
			sortable : true
		}, {
			name : 'sex',
			display : '�Ա�',
			width:60,
			sortable : true
		}, {
			name : 'workSeniority',
			display : '��������',
			width:60,
			sortable : true
		}, {
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		}, {
			name : 'email',
			display : '��������',
			sortable : true,
			width : 150
		}, {
			name : 'post',
			display : 'ӦƸְλ',
			sortable : true,
			datacode : 'YPZW'
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		//��չ��ť
		buttonsEx : [{
			name : 'addin',
			text : '����',
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
										alert("����ɹ���");
										window.close();
										self.opener.location.reload();
			    		            }else if(msg==2){
			    		            	alert("����ʧ��,�ü�������ӵ�������");
			    		            }else{
			    		            	alert("����ʧ�ܣ�");
			    		            }
			    		         }
					});
				}
			}
		}],


		searchitems : [{
			display : "�������",
			name : 'resumeCode'
		}, {
			display : "ӦƸ������",
			name : 'applicantName'
		}]
	});
});