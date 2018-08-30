var show_page = function(page) {
	$("#assessrecordsGrid").yxgrid("reload");
};
$(function() {
		var userAccount = $("#userAccount").val();
		var userNo = $("#userNo").val();
		$("#assessrecordsGrid").yxgrid({
				model : 'hr_assess_assessrecords',
               	title : '季度考核信息',
               	showcheckbox:false,
               	isAddAction:false,
               	isEditAction:false,
               	isDelAction:false,
               	isViewAction:false,
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
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : '被考核人',
//									process : function(v,row){
//										return "<a href='#' onclick='showThickboxWin(\"?model=administration_appraisal_performance_list&action=perExIn&keyId=" + row.id+ '&tplId=' + row.tpl_id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=900\")'>" + v + "</a>";
//									},
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'deptName',
                  					display : '直属部门',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'deptNameS',
                  					display : '二级部门',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'deptNameT',
                  					display : '三级部门',
                  					width:80,
                  					sortable : true
                              },{
                                name : 'deptNameF',
                                display : '四级部门',
                                width:80,
                                sortable : true
                            },{
                    					name : 'jobName',
                  					display : '职务',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'years',
                  					display : '考核年份',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'quarter',
                  					display : '考核周期',
									process : function(v,row){
										switch (v)
											{
												case '1':
												    return '一季度';
												  break;
												case '2':
												   return  '二季度';
												  break;
												case '3':
												   return  '三季度';
												  break;
												case '4':
												   return  '四季度';
												  break;
												case '5':
												   return  '上半年';
												  break;
												case '6':
												   return  '下半年';
												  break;
												case '7':
												   return  '全年';
												  break;
									   	   }
									},
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'count_my_fraction',
                  					display : '自评总分',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'assessName',
                  					display : '考核人',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'count_assess_fraction',
                  					display : '考核总分',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'auditName',
                  					display : '审核人',
                  					width:60,
                  					sortable : true
                              },{
                    					name : 'count_audit_fraction',
                  					display : '审核总分',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'countFraction',
                  					display : '总分',
                  					width:50,
                  					sortable : true
                              },{
                    					name : 'deptRank',
                  					display : '二级部门内排名',
                  					width:80,
                  					sortable : true
                              },{
                    					name : 'deptRankPer',
                  					display : '二级部门内排名比例(前)',
                  					width:150,
                  					sortable : true
                              }],
							  lockCol:['userNo','userName'],//锁定的列名
                              menusEx : [{
											text : '查看',
											icon : 'view',
											action : function(row) {
												showThickboxWin('?model=administration_appraisal_performance_list&action=perExIn&keyId='+row.id+'&tplId='
														+ row.tpl_id
														+ "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=800");
											}
										}]
 		});
 });