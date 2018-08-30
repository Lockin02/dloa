var show_page = function(page) {
	$("#educationPersonnelGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userAccount").val();
	var userNo = $("#userNo").val();
	$("#healthPersonnelGrid").yxgrid({
				model : 'hr_personnel_health',
               	title : '健康信息',
               	showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
				isOpButton : false,
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
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_health&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : '员工姓名',
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_health&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'hospital',
                  					display : '体检医院',
                  					sortable : true
                              },{
                    					name : 'checkDate',
                  					display : '体检日期',
                  					sortable : true
                              },{
                    					name : 'checkResult',
                  					display : '体检结果',
                  					sortable : true
                              },{
                    					name : 'remark',
                  					display : '内容',
                  					sortable : true
                              },{
                    					name : 'hospitalOpinion',
                  					display : '医院意见',
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
                    name : 'checkDate',
                  	display : '体检日期'
                },{
                    name : 'checkResult',
                  	display : '体检结果'
                }]
 		});
 });