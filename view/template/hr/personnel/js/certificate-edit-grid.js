var show_page = function(page) {
$("#certificateEditGrid").yxgrid("reload");
};
$(function() {
			var userAccount = $("#userAccount").val();
			var userNo = $("#userNo").val();
			$("#certificateEditGrid").yxgrid({
				model : 'hr_personnel_certificate',
               	title : '�ʸ�֤����Ϣ',
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
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					width:80,
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
		buttonsEx:[{
				name : 'add',
				text : "����",
				icon : 'add',
				action : function(row) {
					showThickboxWin("?model=hr_personnel_certificate&action=toMyAdd&userNo="+userNo+"&userAccount="+userAccount
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit'
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