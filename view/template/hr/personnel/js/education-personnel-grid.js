var show_page = function(page) {
	$("#educationPersonnelGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userAccount").val();
	var userNo = $("#userNo").val();
	$("#educationPersonnelGrid").yxgrid({
				model : 'hr_personnel_education',
               	title : '����������Ϣ',
               	showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
				isOpButton:false,
				bodyAlign:'center',
               	param:{"userNo":userNo},
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
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_education&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
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
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "Ա�����",
					name : 'userNoSearch'
				},{
					display : "Ա������",
					name : 'userNameSearch'
				},{
					display : "ѧУ",
					name : 'organizationSearch'
				},{
					display : "רҵ",
					name : 'contentSearch'
				}]
 		});
 });