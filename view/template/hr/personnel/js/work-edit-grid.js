var show_page = function(page) {
$("#workEditGrid").yxgrid("reload");};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#workEditGrid").yxgrid({
				model : 'hr_personnel_work',
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_work&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					width:80,
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
                  					sortable : true
                              }],
		buttonsEx:[{
				name : 'add',
				text : "����",
				icon : 'add',
				action : function(row) {
					showThickboxWin("?model=hr_personnel_work&action=toMyAdd&userNo="+userNo+"&userAccount="+userAccount
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800")
				}
			}],
		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight:500
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