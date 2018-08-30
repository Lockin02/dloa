var show_page = function(page) {
	$("#recomResumeGrid").yxgrid("reload");
};
$(function() {
	buttonArr = [{
			name : 'inone',
			text : "��Ӽ���",
			icon : 'add',
			action : function(row) {
				showModalWin ("?model=hr_recruitment_recomResume&action=toSelect&gridName=resumeGrid"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700&id=" + $("#id").val())
			}
		},{
			name : 'add',
			text : "��������",
			icon : 'add',
			action : function(row) {
				showModalWin ("?model=hr_recruitment_resume&action=toAdd"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700&type=recommend&id=" + $("#id").val())
			}
		}];
	var state=['��ͨ��','δ���'];
	if($.inArray($("#stateC").val(),state)!=-1){
		buttonArr=[];
	}
	$("#recomResumeGrid").yxgrid({
		model : 'hr_recruitment_recomResume',
		title : '�ڲ��Ƽ�������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		isOpButton : false,
		bodyAlign:'center',
		//����Ϣ
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
			display : '������',
			sortable : true
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\",1)'>" + v + "</a>";
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
			sortable : true
		}, {
			name : 'stateC',
			display : '״̬'
		}],
		buttonsEx : buttonArr,
		menusEx : [{
			text : '�鿴����',
			icon : 'view',
			action : function(row) {
					showModalWin('?model=hr_recruitment_resume&action=toView&id='
							+ row.resumeId + "&skey=" + row['skey_']);
			}
		},{
			text : '֪ͨ����',
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
			text : '����¼��֪ͨ',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.stateC == '�˲ų�ѡ'&&$("#stateC").val()!="��ͨ��")
					return true;
				 else
					return false;
			},
			action : function(row) {
				showModalWin('?model=hr_recruitment_invitation&action=sendNotify&interviewType=2&resumeId='+row.resumeId+'&applyid='
						+ row.parentId + "&resumeid=" + row.resumeId+"&applyResumeId="+row.id);
			}
		},{
			text : '���������',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.stateC != '������')
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
										alert("����������ɹ���~");
			    		            }else if(msg==3){
			    		            	alert("����ʧ�ܣ���Ϊ�Ѿ������ˣ�");
			    		            }else{
			    		            	alert("����ʧ�ܣ�~");
			    		            }
			    		         }
					});
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_recruitment_recomResume&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
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
			display : "�������",
			name : 'resumeCode'
		},{
			display : "ӦƸ������",
			name : 'applicantName'
		},{
			display : "������",
			name : 'formCode'
		}]
	});
});