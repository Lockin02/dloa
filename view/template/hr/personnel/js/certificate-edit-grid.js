var show_page = function(page) {
$("#certificateEditGrid").yxgrid("reload");
};
$(function() {
			var userAccount = $("#userAccount").val();
			var userNo = $("#userNo").val();
			$("#certificateEditGrid").yxgrid({
				model : 'hr_personnel_certificate',
               	title : '资格证书信息',
               	showcheckbox:true,
               	isAddAction:false,
               	isEditAction:true,
               	isDelAction:true,
               	param:{"userNo":userNo},
				isOpButton : false,
				bodyAlign:'center',
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
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : '员工姓名',
                  					width:80,
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
		buttonsEx:[{
				name : 'add',
				text : "新增",
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