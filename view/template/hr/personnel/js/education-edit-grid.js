var show_page = function(page) {
	$("#educationEditGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userAccount").val();
	var userNo = $("#userNo").val();
	$("#educationEditGrid").yxgrid({
				model : 'hr_personnel_education',
               	title : '����������Ϣ',
               	showcheckbox:true,
               	isAddAction:false,
               	isEditAction:true,
               	isDelAction:true,
               	param:{"userNo":userNo},
				isOpButton : false,
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
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_education&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : '����',
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_education&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'organization',
                  					display : 'ѧУ',
                  					sortable : true
                              },{
                    					name : 'content',
                  					display : 'רҵ',
                  					sortable : true
                              },{
                    					name : 'educationName',
                  					display : 'ѧ��',
                  					sortable : true
                              },{
                    					name : 'certificate',
                  					display : '֤��',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '��ʼʱ��',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '����ʱ��',
                  					sortable : true
                              }],
		buttonsEx:[{
				name : 'add',
				text : "����",
				icon : 'add',
				action : function(row) {
					showThickboxWin("?model=hr_personnel_education&action=toMyAdd&userNo="+userNo+"&userAccount="+userAccount
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit'
		},

		//��չ�Ҽ�
//		menusEx:[
//			{  text:'�޸�',
//			   icon:'edit',
//			   action:function(row,rows,grid){
//			   		if(row){
//						 showModalWin("?model=hr_personnel_education&action=toMyTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
//						'newwindow1','resizable=yes,scrollbars=yes');
//			   		}
//			   }
//			}],
		searchitems : [{
					display : "ѧУ",
					name : 'organizationSearch'
				},{
					display : "רҵ",
					name : 'contentSearch'
				}]
 		});
 });