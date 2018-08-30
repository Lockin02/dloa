var show_page = function(page) {	   $("#detailGrid").yxgrid("reload");};
$(function() {			$("#detailGrid").yxgrid({				      model : 'hr_permanent_detail',
               	title : '员工转正考核明细表',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        }                    ,{
                    					name : 'parentCode',
                  					display : '评估表Code',
                  					sortable : true
                              }                    ,{
                    					name : 'standardType',
                  					display : '评估项目类型',
                  					sortable : true
                              },{
                    					name : 'selfScore',
                  					display : '自评分值',
                  					sortable : true
                              },{
                    					name : 'otherScore',
                  					display : '复评分值',
                  					sortable : true
                              },{
                    					name : 'standardContent',
                  					display : '考核内容',
                  					sortable : true
                              },{
                    					name : 'standardPoint',
                  					display : '考核要点',
                  					sortable : true
                              },{
                    					name : 'comment',
                  					display : '其他说明',
                  					sortable : true
                              },{
                    					name : 'status',
                  					display : '状态',
                  					sortable : true
                              }],
		// 主从表格设置
		subGridOptions : {
			url : '?model=hr_permanent_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '从表字段'
					}]
		},
      
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "搜索字段",
					name : 'XXX'
				}]
 		});
 });