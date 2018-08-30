var show_page = function(page) {	   $("#healthGrid").yxgrid("reload");};
$(function() {
	buttonsArr = [];
	excelOutArr = {
		name : 'exportIn',
		text : "导入",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_health&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};


	excelOutArr2 = {
		name : 'exportOut',
		text : "导出",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_personnel_health&action=toExcelOut"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '导入权限'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
			}
		}
	});

	$("#healthGrid").yxgrid({
			    model : 'hr_personnel_health',
               	title : '健康信息',
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
                  					width:400,
                  					sortable : true
                              },{
                    					name : 'hospitalOpinion',
                  					display : '医院意见',
                  					width:300,
                  					sortable : true
                              }],
                     lockCol:['userNo','userName'],//锁定的列名
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
                    name : 'checkDate',
                  	display : '体检日期'
                },{
                    name : 'checkResult',
                  	display : '体检结果'
                }]
 		});
 });