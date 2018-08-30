var show_page = function(page) {
	$("#educationPersonnelGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userAccount").val();
	var userNo = $("#userNo").val();
	$("#educationPersonnelGrid").yxgrid({
				model : 'hr_personnel_education',
               	title : '教育经历信息',
               	showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
				isOpButton:false,
				bodyAlign:'center',
               	param:{"userNo":userNo},
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'userNo',
                  					display : '员工编号',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_education&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : '员工姓名',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_education&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'organization',
                  					display : '学校',
                  					sortable : true
                              },{
                    					name : 'content',
                  					display : '专业',
                  					sortable : true
                              },{
                    					name : 'educationName',
                  					display : '学历',
                  					sortable : true
                              },{
                    					name : 'certificate',
                  					display : '证书',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '开始时间',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '结束时间',
                  					sortable : true
                              }],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "员工编号",
					name : 'userNoSearch'
				},{
					display : "员工姓名",
					name : 'userNameSearch'
				},{
					display : "学校",
					name : 'organizationSearch'
				},{
					display : "专业",
					name : 'contentSearch'
				}]
 		});
 });