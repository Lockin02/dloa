var show_page = function(page) {
	$("#trackGrid").yxgrid("reload");
};
$(function() {
			$("#trackGrid").yxgrid({
				      model : 'projectmanagent_track_track',
               	title : '跟踪记录',
						//列信息
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'cluesId',
                  					display : '线索ID',
                  					sortable : true
                              },{
                    					name : 'cluesCode',
                  					display : '线索编号',
                  					sortable : true
                              },{
                    					name : 'cluesName',
                  					display : '线索名称',
                  					sortable : true
                              },{
                    					name : 'chanceId',
                  					display : '商机ID',
                  					sortable : true
                              },{
                    					name : 'chanceName',
                  					display : '商机名称',
                  					sortable : true
                              },{
                    					name : 'chanceCode',
                  					display : '商机编号',
                  					sortable : true
                              },{
                    					name : 'trackId',
                  					display : '跟踪人ID',
                  					sortable : true
                              },{
                    					name : 'trackName',
                  					display : '跟踪人姓名',
                  					sortable : true
                              },{
                    					name : 'trackDate',
                  					display : '跟踪日期',
                  					sortable : true
                              },{
                    					name : 'trackType',
                  					display : '跟踪类型',
                  					sortable : true
                              },{
                    					name : 'linkmanName',
                  					display : '联系人姓名',
                  					sortable : true
                              },{
                    					name : 'trackPurpose',
                  					display : '跟踪目的',
                  					sortable : true
                              },{
                    					name : 'customerFocus',
                  					display : '客户关注点',
                  					sortable : true
                              },{
                    					name : 'result',
                  					display : '接触结果',
                  					sortable : true
                              },{
                    					name : 'updateTime',
                  					display : '修改时间',
                  					sortable : true
                              },{
                    					name : 'updateName',
                  					display : '修改人名称',
                  					sortable : true
                              },{
                    					name : 'updateId',
                  					display : '修改人Id',
                  					sortable : true
                              },{
                    					name : 'createTime',
                  					display : '创建时间',
                  					sortable : true
                              },{
                    					name : 'createName',
                  					display : '创建人名称',
                  					sortable : true
                              },{
                    					name : 'createId',
                  					display : '创建人ID',
                  					sortable : true
                              }]
 		});
 });