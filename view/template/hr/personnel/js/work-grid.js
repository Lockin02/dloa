var show_page = function(page) {
$("#workGrid").yxgrid("reload");};
$(function() {
	//表头按钮数组
	buttonsArr = [
//        {
//			name : 'view',
//			text : "高级查询",
//			icon : 'view',
//			action : function() {
//				alert('功能暂未开发完成');
////				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
////					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        }
    ];


	//表头按钮数组
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_work&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	excelOutArr2 = {
		name : 'exportOut',
		text : "高级查询并导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_work&action=toExcelOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	};

	 excelOutSelect = {
			name : 'excelOutAllArr',
			text : "自定义导出信息",
			icon : 'excel',
			action : function() {
				if($("#totalSize").val()<1){
					alert("没有可导出的记录");
				}else{
					document.getElementById("form2").submit();
				}
			}
        };

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_work&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
				buttonsArr.push(excelOutSelect);
			}
		}
	});
		$("#workGrid").yxgrid({
				model : 'hr_personnel_work',
               	title : '工作经历信息',
				bodyAlign:'center',
               	isOpButton:false,
			    event:{'afterload':function(data,g){
			      $("#listSql").val(g.listSql);
          	 		$("#totalSize").val(g.totalSize);
			    }},
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
                  					display : '公司',
                  					sortable : true
                              },{
                    					name : 'dept',
                  					display : '部门',
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
                              }],
                              // 主从表格设置
			subGridOptions : {
				url : '?model=hr_personnel_work&action=pageJson',
				param : [{
							paramId : 'userNo',
							colId : 'userNo'
						}],
				colModel : [{
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
                  					display : '公司',
                  					sortable : true
                              },{
                    					name : 'dept',
                  					display : '部门',
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
                              }]
			},

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
				// 默认搜索字段名
				sortname : "userNo",
				// 默认搜索顺序
				sortorder : "asc",
		searchitems : [{
					display : "员工编号",
					name : 'userNoSearch'
				},{
					display : "员工姓名",
					name : 'userNameSearch'
				},{
					display : "公司",
					name : 'companySearch'
				},{
					display : "职位",
					name : 'positionSearch'
				},{
					display : "工作职责",
					name : 'responsibilities'
				}]
 		});
 });