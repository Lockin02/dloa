var show_page = function(page) {
	$("#personnelMyGrid").yxgrid("reload");
};
	//查看员工档案
	function viewPersonnel(id,userNo,userAccount){
	    var skey = "";
	    $.ajax({
		    type: "POST",
		    url: "?model=hr_personnel_personnel&action=md5RowAjax",
		    data: {"id" : id},
		    async: false,
		    success: function(data){
		   	   skey = data;
			}
		});
		showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+id+"&userNo="+userNo+"&userAccount="+userAccount+"&skey=" + skey
			,'newwindow1','resizable=yes,scrollbars=yes');
	}
$(function() {
			$("#personnelMyGrid").yxgrid({
				model : 'hr_personnel_personnel',
				action:"myPageJson",
               	title : '我的档案信息',
				showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
               	isViewAction:false,
				isOpButton:false,
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
                  					sortable : true,
                  					width:80
//									process : function(v, row) {
//										return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
//												+ row.id
//												+"\",\""
//												+ row.userNo
//												+"\",\""
//												+row.userAccount
//												+ "\")' >"
//												+ v
//												+ "</a>";
//									}
                              },{
                					name : 'userName',
                  					display : '姓名',
                  					sortable : true,
                  					width:80
//									process : function(v, row) {
//										return "<a href='#' title='点击查看员工信息' onclick='viewPersonnel(\""
//												+ row.id
//												+"\",\""
//												+ row.userNo
//												+"\",\""
//												+row.userAccount
//												+ "\")' >"
//												+ v
//												+ "</a>";
//									}
                              },{
                    					name : 'sex',
                  					display : '性别',
                  					width:60,
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'companyType',
                  					display : '公司类型',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'companyName',
                  					display : '公司',
                  					sortable : true
                              },{
                    					name : 'belongDeptName',
                  					display : '所属部门',
                  					sortable : true
                              },{
                    					name : 'jobName',
                  					display : '职位',
                  					sortable : true
                              } ,{
                    					name : 'employeesStateName',
                  					display : '员工状态',
                  					sortable : true,
                  					width:60
                              },{
                    					name : 'personnelTypeName',
                  					display : '员工类型',
                  					sortable : true
                              },{
                    					name : 'positionName',
                  					display : '岗位分类',
                  					sortable : true
                              },{
                    					name : 'personnelClassName',
                  					display : '人员分类',
                  					sortable : true
                              },{
                    					name : 'wageLevelName',
                  					display : '工资级别',
                  					sortable : true
                              }],
                     lockCol:['userNo','userName'],//锁定的列名
        buttonsEx:[
//			{  text:'查看',
//			   icon:'view',
//			   action:function(row,rows,grid){
//			   		if(row){
//						 showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
//						'newwindow1','resizable=yes,scrollbars=yes');
//			   		}else{
//			   			alert("请选择一条记录信息");
//			   		}
//			   }
//			},
			{  text:'修改',
			   icon:'edit',
			   action:function(row,rows,grid){
			   		if(row){
						 showModalWin("?model=hr_personnel_personnel&action=toMyTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
						'newwindow1','resizable=yes,scrollbars=yes');
			   		}else{
			   			alert("请选择一条记录信息");
			   		}
			   }
			}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		//扩展右键
		menusEx:[
//			{  text:'查看',
//			   icon:'view',
//			   action:function(row,rows,grid){
//			   		if(row){
//						 showModalWin("?model=hr_personnel_personnel&action=toTabView&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
//						'newwindow1','resizable=yes,scrollbars=yes');
//			   		}
//			   }
//			},
			{  text:'修改',
			   icon:'edit',
			   action:function(row,rows,grid){
			   		if(row){
						 showModalWin("?model=hr_personnel_personnel&action=toMyTabEdit&id="+ row.id+"&skey="+row['skey_']+"&userNo="+row.userNo+"&userAccount="+row.userAccount,
						'newwindow1','resizable=yes,scrollbars=yes');
			   		}
			   }
			}]
 		});
 });