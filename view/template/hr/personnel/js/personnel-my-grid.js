var show_page = function(page) {
	$("#personnelMyGrid").yxgrid("reload");
};
	//�鿴Ա������
	function viewPersonnel(id,userNo,userAccount){
	    var skey = "";
	    $.ajax({
		    type: "POST",
		    url: "?model=hr_personnel_personnel&action=md5RowAjax",
		    data: {"id" : id},
		    async: false,
		    success: function(data){
		   	   skey = data;
			}
		});
		showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+id+"&userNo="+userNo+"&userAccount="+userAccount+"&skey=" + skey
			,'newwindow1','resizable=yes,scrollbars=yes');
	}
$(function() {
			$("#personnelMyGrid").yxgrid({
				model : 'hr_personnel_personnel',
				action:"myPageJson",
               	title : '�ҵĵ�����Ϣ',
				showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
               	isViewAction:false,
				isOpButton:false,
				bodyAlign:'center',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                					name : 'userNo',
                  					display : 'Ա�����',
                  					sortable : true,
                  					width:80
//									process : function(v, row) {
//										return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
//												+ row.id
//												+"\",\""
//												+ row.userNo
//												+"\",\""
//												+row.userAccount
//												+ "\")' >"
//												+ v
//												+ "</a>";
//									}
                              },{
                					name : 'userName',
                  					display : '����',
                  					sortable : true,
                  					width:80
//									process : function(v, row) {
//										return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
//												+ row.id
//												+"\",\""
//												+ row.userNo
//												+"\",\""
//												+row.userAccount
//												+ "\")' >"
//												+ v
//												+ "</a>";
//									}
                              },{
                    					name : 'sex',
                  					display : '�Ա�',
                  					width:60,
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'companyType',
                  					display : '��˾����',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'companyName',
                  					display : '��˾',
                  					sortable : true
                              },{
                    					name : 'belongDeptName',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : 'ְλ',
                  					sortable : true
                              } ,{
                    					name : 'employeesStateName',
                  					display : 'Ա��״̬',
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'personnelTypeName',
                  					display : 'Ա������',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : '��λ����',
                  					sortable : true
                              },{
                    					name : 'personnelClassName',
                  					display : '��Ա����',
                  					sortable : true
                              },{
                    					name : 'wageLevelName',
                  					display : '���ʼ���',
                  					sortable : true
                              }],
                     lockCol:['userNo','userName'],//����������
        buttonsEx:[
//			{  text:'�鿴',
//			   icon:'view',
//			   action:function(row,rows,grid){
//			   		if(row){
//						 showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
//						'newwindow1','resizable=yes,scrollbars=yes');
//			   		}else{
//			   			alert("��ѡ��һ����¼��Ϣ");
//			   		}
//			   }
//			},
			{  text:'�޸�',
			   icon:'edit',
			   action:function(row,rows,grid){
			   		if(row){
						 showModalWin("?model=hr_personnel_personnel&action=toMyTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
						'newwindow1','resizable=yes,scrollbars=yes');
			   		}else{
			   			alert("��ѡ��һ����¼��Ϣ");
			   		}
			   }
			}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		//��չ�Ҽ�
		menusEx:[
//			{  text:'�鿴',
//			   icon:'view',
//			   action:function(row,rows,grid){
//			   		if(row){
//						 showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
//						'newwindow1','resizable=yes,scrollbars=yes');
//			   		}
//			   }
//			},
			{  text:'�޸�',
			   icon:'edit',
			   action:function(row,rows,grid){
			   		if(row){
						 showModalWin("?model=hr_personnel_personnel&action=toMyTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
						'newwindow1','resizable=yes,scrollbars=yes');
			   		}
			   }
			}]
 		});
 });