var show_page = function(page) {
$("#certificatePersonnelGrid").yxgrid("reload");
};
$(function() {
			var userAccount = $("#userAccount").val();
			var userNo = $("#userNo").val();
			$("#certificatePersonnelGrid").yxgrid({
				model : 'hr_personnel_certificate',
               	title : '�ʸ�֤����Ϣ',
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
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					sortable : true
                              },{
                    					name : 'certificates',
                  					display : '֤������',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certificate&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'level',
                  					display : '�ȼ�',
                  					sortable : true
                              },{
                    					name : 'certifying',
                  					display : '��֤����',
                  					sortable : true
                              },{
                    					name : 'certifyingDate',
                  					display : '��֤ʱ��',
                  					sortable : true
                              }],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
						display : "֤������",
						name : 'certificatesSearch'
					},{
						display : "��֤����",
						name : 'certifyingSearch'
					},{
						display : "��֤ʱ��",
						name : 'certifyingDateSearch'
					}]
 		});
 });