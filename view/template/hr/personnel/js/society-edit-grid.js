var show_page = function(page) {
	$("#societyEditGrid").yxgrid("reload");
};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#societyEditGrid").yxgrid({
				model : 'hr_personnel_society',
               	title : '社会关系信息',
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
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_society&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'userName',
                  					display : '员工姓名',
                  					width:80,
                  					sortable : true,
									process : function(v,row){
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_personnel_society&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
									}
                              },{
                    					name : 'relationName',
                  					display : '关系人姓名',
                  					sortable : true
                              },{
                    					name : 'age',
                  					display : '年龄',
                  					sortable : true
                              },{
                    					name : 'isRelation',
                  					display : '与本人关系',
                  					sortable : true
                              },{
                    					name : 'information',
                  					display : '联系方式',
                  					sortable : true
                              },{
                    					name : 'workUnit',
                  					display : '工作单位',
                  					sortable : true
                              },{
                    					name : 'job',
                  					display : '职位',
                  					sortable : true
                              }],
		buttonsEx:[{
				name : 'add',
				text : "新增",
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
//					display : "搜索字段",
//					name : 'XXX'
//				}]
 		});
 });