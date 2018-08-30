var show_page = function(page) {
	$("#societyGrid").yxgrid("reload");
};
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
			showThickboxWin("?model=hr_personnel_society&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	excelOutArr2 = {
		name : 'exportOut',
		text : "高级查询并导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_society&action=toExcelOut"
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
		url : '?model=hr_personnel_society&action=getLimits',
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
		$("#societyGrid").yxgrid({
				model : 'hr_personnel_society',
               	title : '社会关系信息',
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
                  					width:80,
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
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'age',
                  					display : '年龄',
                  					width:60,
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
					display : "工作单位",
					name : 'workUnit'
				},{
					display : "职位",
					name : 'job'
				}]
 		});
 });