var show_page = function(page) {
$("#workPersonnelGrid").yxgrid("reload");};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#workPersonnelGrid").yxgrid({
				model : 'hr_personnel_work',
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_work&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_work&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'company',
                  					display : '��˾����',
                  					sortable : true
                              },{
                    					name : 'dept',
                  					display : '��������',
                  					sortable : true
                              },{
                    					name : 'position',
                  					display : 'ְλ',
                  					sortable : true
                              },{
                    					name : 'treatment',
                  					display : '����',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '��ʼʱ��',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'isSeniority',
                  					display : '�ڸù�˾����',
                  					sortable : true
                              },{
                    					name : 'responsibilities',
                  					display : '����ְ��',
                  					width:250,
                  					sortable : true
                              }],
        lockCol:['userNo','userName'],//����������
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "��˾",
					name : 'companySearch'
				},{
					display : "ְλ",
					name : 'positionSearch'
				}]
 		});
 });