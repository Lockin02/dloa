var show_page = function(page) {
	$("#societyPersonnelGrid").yxgrid("reload");
};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#societyPersonnelGrid").yxgrid({
				model : 'hr_personnel_society',
               	title : '����ϵ��Ϣ',
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_society&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_society&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'relationName',
                  					display : '��ϵ������',
                  					sortable : true
                              },{
                    					name : 'age',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'isRelation',
                  					display : '�뱾�˹�ϵ',
                  					sortable : true
                              },{
                    					name : 'information',
                  					display : '��ϵ��ʽ',
                  					sortable : true
                              },{
                    					name : 'workUnit',
                  					display : '������λ',
                  					sortable : true
                              },{
                    					name : 'job',
                  					display : 'ְλ',
                  					sortable : true
                              }],
		toViewConfig : {
			action : 'toView'
		}
//		searchitems : [{
//					display : "�����ֶ�",
//					name : 'XXX'
//				}]
 		});
 });