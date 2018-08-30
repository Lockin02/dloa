var show_page = function(page) {
	$("#educationEditGrid").yxgrid("reload");
};
$(function() {
	var userAccount = $("#userAccount").val();
	var userNo = $("#userNo").val();
	$("#educationEditGrid").yxgrid({
				model : 'hr_personnel_education',
               	title : '教育经历信息',
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
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_education&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : '姓名',
                  					width:80,
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
		buttonsEx:[{
				name : 'add',
				text : "新增",
				icon : 'add',
				action : function(row) {
					showThickboxWin("?model=hr_personnel_education&action=toMyAdd&userNo="+userNo+"&userAccount="+userAccount
				          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
				}
			}],
		toViewConfig : {
			action : 'toView'
		},
		toEditConfig : {
			action : 'toEdit'
		},

		//扩展右键
//		menusEx:[
//			{  text:'修改',
//			   icon:'edit',
//			   action:function(row,rows,grid){
//			   		if(row){
//						 showModalWin("?model=hr_personnel_education&action=toMyTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
//						'newwindow1','resizable=yes,scrollbars=yes');
//			   		}
//			   }
//			}],
		searchitems : [{
					display : "学校",
					name : 'organizationSearch'
				},{
					display : "专业",
					name : 'contentSearch'
				}]
 		});
 });