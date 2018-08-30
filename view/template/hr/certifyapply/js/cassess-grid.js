var show_page = function(page) {
	$("#cassessGrid").yxgrid("reload");
};
$(function() {
	$("#cassessGrid").yxgrid({
		model : 'hr_certifyapply_cassess',
		title : '任职资格评价表',
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'modelName',
				display : '匹配模板名称',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : '员工编号',
				sortable : true,
				hide : true
			}, {
				name : 'userAccount',
				display : '员工账号',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true,
				width : 80
			}, {
				name : 'deptName',
				display : '部门名称',
				sortable : true,
				width : 80
			}, {
				name : 'jobName',
				display : '职位名称',
				sortable : true,
				width : 80
			}, {
				name : 'nowDirectionName',
				display : '当前通道',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowLevelName',
				display : '当前级别',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'nowGradeName',
				display : '当前级等',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'careerDirectionName',
				display : '申请通道',
				sortable : true,
				width : 80
			}, {
				name : 'baseLevelName',
				display : '申请级别',
				sortable : true,
				width : 70
			}, {
				name : 'baseGradeName',
				display : '申请级等',
				sortable : true,
				width : 70
			}, {
				name : 'managerName',
				display : '主审评委',
				sortable : true,
				width : 80
			}, {
				name : 'memberName',
				display : '参与评委',
				sortable : true
			}, {
				name : 'status',
				display : '状态',
				sortable : true,
				width : 80,
				process : function(v){
					switch(v){
						case '0' : return '保存';break;
						case '1' : return '认证准备中';break;
						case '2' : return '审批中';break;
						case '3' : return '完成待评分';break;
						case '4' : return '完成已评分';break;
						case '5' : return '确认审核中';break;
						case '6' : return '审核已完成';break;
						case '7' : return '认证失败';break;
						default : return v;
					}
				}
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 60
			}, {
				name : 'ExaDT',
				display : '审批日期',
				sortable : true,
				width : 80
			}, {
				name : 'scoreAll',
				display : '评分',
				sortable : true,
				width : 60
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 120,
				hide : true
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				var c = p.toEditConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//判断
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		toViewConfig : {
			action : 'toView',
			showMenuFn : function(row) {
				return true;
			},
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					//判断
					showModalWin("?model=" + p.model + "&action=" + c.action + c.plusUrl + "&id=" + rowData[p.keyField] + keyUrl);
				} else {
					alert('请选择一行记录！');
				}
			}
		},
		buttonsEx :[{
			text: "指定评委",
			icon: 'edit',
			action: function(row,rows,idArr ) {
					var idArr=$("#cassessGrid").yxgrid("getCheckedRowIds");  //获取选中的id
				if (row) {
					//判断数组是否符合条件
					for (var i = 0; i < rows.length; i++) {
						if(rows[i].status != '3'){
							alert('记录 ['+ rows[i].id +']状态不正确 ，只有状态为 [完成待评分] 的记录才能指定评委');
							return false;
						}

						if(rows[i].scoreAll*1 > 0){
							alert('记录 ['+ rows[i].id +']正在进行评分，不能重新指定评委');
							return false;
						}
					}
					//提交认证审核
					idStr = idArr.toString();

					showThickboxWin("?model=hr_certifyapply_cassess&action=toSetManager&id="
						+ idStr
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500"
					);

				}else{
					alert('请选择一行记录！');
				}
			}
		},{
			text: "认证结果",
			icon: 'edit',
			items : [{
				text: "提交认证审核",
				icon: 'edit',
				action: function(row,rows,idArr ) {
					var idArr=$("#cassessGrid").yxgrid("getCheckedRowIds");  //获取选中的id
					if (row) {
						//判断数组是否符合条件
						for (var i = 0; i < rows.length; i++) {
							if(rows[i].status != '4'){
								alert('记录 ['+ rows[i].id +']状态不正确 ，只有状态为 [完成已评分] 的记录才能提交认证审批');
								return false;
							}
						}
						//提交认证审核
						idStr = idArr.toString();
						//判断
						showModalWin("?model=hr_certifyapply_certifyresult&action=toAdd&assessIds=" + idStr );
					}else{
						alert('请选择一行记录！');
					}
				}
			},{
				text: "认证失败",
				icon: 'edit',
				action: function(row,rows,idArr ) {
					var idArr=$("#cassessGrid").yxgrid("getCheckedRowIds");  //获取选中的id
					if (row) {
						//判断数组是否符合条件
						for (var i = 0; i < rows.length; i++) {
							if(rows[i].status != '4'){
								alert('记录 ['+ rows[i].id +']状态不正确 ，只有状态为 [完成已评分] 的记录才能进行此操作');
								return false;
							}
						}

						if(confirm('确定将所选记录更新更认证失败吗')){
							//提交认证审核
							idStr = idArr.toString();
							$.ajax({
							    type: "POST",
							    url: "?model=hr_certifyapply_cassess&action=assessFailure",
							    data: {"ids" : idStr},
							    async: false,
							    success: function(data){
							   	    if(data == 1){
										alert('数据更新成功');
										show_page();
									}else{
										alert('数据更新失败');
									}
								}
							});
						}
					}else{
						alert('请选择一行记录！');
					}
				}
			}]
		}],
		menusEx : [{
			name : 'edit',
			text : "提交认证准备",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('确认提交认证准备吗？')){
					$.ajax({
					    type: "POST",
					    url: "?model=hr_certifyapply_cassess&action=handUp",
					    data: {"id" : row.id},
					    async: false,
					    success: function(data){
					   	    if(data == 1){
								alert('提交成功');
								show_page();
							}else{
								alert('提交失败');
							}
						}
					});
				}
			}
		},{
			name : 'setManagerInfo',
			text : "指定评委",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.scoreAll*1 > 0){
					alert('正在进行评分，不能重新指定评委');
					return false;
				}
				//判断
				showThickboxWin("?model=hr_certifyapply_cassess&action=toSetManager&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=500"
				);
			}
		},{
			name : 'edit',
			text : "录入分数",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.status == '3' || row.status == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.managerId == "" || row.memberId == ""){
					alert('请选择主审评委和参与评委');
					return false;
				}
				//判断
				showModalWin("?model=hr_certifyapply_cassess&action=toInScore&id=" + row.id + "&skey=" + row.skey);
			}
		},{
			name : 'view',
			text : "查看分数",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.scoreAll*1  != 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				//判断
				showModalWin("?model=hr_certifyapply_cassess&action=toViewScore&id=" + row.id + "&skey=" + row.skey);
			}
		}],

        //过滤数据
		comboEx:[{
		     text:'认证通道',
		     key:'careerDirection',
		     datacode : 'HRZYFZ'
		   },{
		     text:'状态',
		     key:'status',
		     data : [{
					text : ' 保存',
					value : '0'
				}, {
					text : '认证准备中',
					value : '1'
				}, {
					text : '审批中',
					value : '2'
				}, {
					text : '完成待评分',
					value : '3'
				}, {
					text : '完成已评分',
					value : '4'
				}, {
					text : '确认审核中',
					value : '5'
				}, {
					text : '审核已完成',
					value : '6'
				}, {
					text : '认证失败',
					value : '7'
				}
			]
		}],

		searchitems : [{
			display : "员工姓名",
			name : 'userNameSearch'
		}]
	});
});