var show_page = function(page) {
	$("#societyEditGrid").yxgrid("reload");
};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#societyEditGrid").yxgrid({
				model : 'hr_personnel_society',
               	title : '����ϵ��Ϣ',
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_society&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : 'Ա������',
                  					width:80,
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
		buttonsEx:[{
				name : 'add',
				text : "����",
				icon : 'add',
				action : function(row) {
					showThickboxWin("?model=hr_personnel_society&action=toMyAdd&userNo="+userNo+"&userAccount="+userAccount
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit'
		}
//		searchitems : [{
//					display : "�����ֶ�",
//					name : 'XXX'
//				}]
 		});
 });