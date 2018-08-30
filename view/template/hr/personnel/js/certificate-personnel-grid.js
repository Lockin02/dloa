var show_page = function(page) {
$("#certificatePersonnelGrid").yxgrid("reload");
};
$(function() {
			var userAccount = $("#userAccount").val();
			var userNo = $("#userNo").val();
			$("#certificatePersonnelGrid").yxgrid({
				model : 'hr_personnel_certificate',
               	title : '资格证书信息',
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
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : '员工姓名',
                  					sortable : true
                              },{
                    					name : 'certificates',
                  					display : '证书名称',
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_certificate&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'level',
                  					display : '等级',
                  					sortable : true
                              },{
                    					name : 'certifying',
                  					display : '发证机构',
                  					sortable : true
                              },{
                    					name : 'certifyingDate',
                  					display : '发证时间',
                  					sortable : true
                              }],
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
						display : "证书名称",
						name : 'certificatesSearch'
					},{
						display : "发证机构",
						name : 'certifyingSearch'
					},{
						display : "发证时间",
						name : 'certifyingDateSearch'
					}]
 		});
 });