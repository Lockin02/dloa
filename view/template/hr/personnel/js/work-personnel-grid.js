var show_page = function(page) {
$("#workPersonnelGrid").yxgrid("reload");};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#workPersonnelGrid").yxgrid({
				model : 'hr_personnel_work',
               	title : '工作经历信息',
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_work&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : '员工姓名',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_work&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'company',
                  					display : '公司名称',
                  					sortable : true
                              },{
                    					name : 'dept',
                  					display : '部门名称',
                  					sortable : true
                              },{
                    					name : 'position',
                  					display : '职位',
                  					sortable : true
                              },{
                    					name : 'treatment',
                  					display : '待遇',
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '开始时间',
                  					sortable : true
                              },{
                    					name : 'closeDate',
                  					display : '结束时间',
                  					sortable : true
                              },{
                    					name : 'isSeniority',
                  					display : '在该公司年限',
                  					sortable : true
                              },{
                    					name : 'responsibilities',
                  					display : '工作职责',
                  					width:250,
                  					sortable : true
                              }],
        lockCol:['userNo','userName'],//锁定的列名
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "公司",
					name : 'companySearch'
				},{
					display : "职位",
					name : 'positionSearch'
				}]
 		});
 });