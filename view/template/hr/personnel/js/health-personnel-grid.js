var show_page = function(page) {
	$("#educationPersonnelGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userAccount").val();
	var userNo = $("#userNo").val();
	$("#healthPersonnelGrid").yxgrid({
				model : 'hr_personnel_health',
               	title : '������Ϣ',
               	showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
				isOpButton : false,
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
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_health&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_health&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'hospital',
                  					display : '���ҽԺ',
                  					sortable : true
                              },{
                    					name : 'checkDate',
                  					display : '�������',
                  					sortable : true
                              },{
                    					name : 'checkResult',
                  					display : '�����',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'hospitalOpinion',
                  					display : 'ҽԺ���',
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
                    name : 'checkDate',
                  	display : '�������'
                },{
                    name : 'checkResult',
                  	display : '�����'
                }]
 		});
 });