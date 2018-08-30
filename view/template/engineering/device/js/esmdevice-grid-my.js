var show_page = function() {
	$("#esmdeviceGrid").yxsubgrid("reload");
};

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_device_esmdevice&action=getFormType",
	    data: {'myself' : 1},
	    async: false,
	    success: function(data){
	   		formTypeArr = eval( "(" + data + ")" );
		}
	});
	$("#esmdeviceGrid").yxsubgrid({
		model : 'engineering_device_esmdevice',
		action : 'myJson',
		title : '我的设备',
		showcheckbox : false,
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isOpButton : false,
		// 列信息
		colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'deptName',
            display : '设备所属部门',
            sortable : true
        }, {
            name : 'deviceType',
            display : '设备类型',
            sortable : true,
            width : 100
        }, {
            name : 'device_name',
            display : '设备名称',
            sortable : true,
            width : 200
        }, {
            name : 'unit',
            display : '单位',
            sortable : true,
            width : 80
        }, {
            name : 'num',
            display : '数量',
            sortable : true,
            width : 80
        }, {
            name : 'notse',
            display : '备注',
            sortable : true,
            hide : true
        }, {
            name : 'description',
            display : '描述信息',
            sortable : true,
            width : 480
        }],
		subGridOptions : {
			subgridcheck : true,
			url : '?model=engineering_device_esmdevice&action=equJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'cid',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			afterProcess : function(data, rowDate, $tr) {
				if (data.number <= data.executedNum) {
					$tr.find("td").css("background-color", "#A1A1A1");
				}
			},
			colModel : [{
				name : 'isLockFlag',
				display : '锁定',
				sortable : false,
				width : '25',
				align : 'center',
				process : function(v, row) {
					if (row.borrowDays > 8) {
						return '<img src="images/icon/cicle_yellow.png" title="已锁定" />';
					}else{
						return '';
					}
				}
			}, {
				name : 'device_name',
				width : 150,
				display : '设备名称'
			}, {
				name : 'coding',
				display : '机身码',
				width : 100
			}, {
				name : 'dpcoding',
				display : '部门编码',
				width : 100
			}, {
				name : 'amount',
				display : '数量',
				width : 50
			}, {
				name : 'price',
				display : '单价',
				width : 50
			}, {
				name : 'projectCode',
				display : '项目编号',
				width : 120		
			}, {
				name : 'projectName',
				display : '项目名称',
				width : 120		
			}, {
				name : 'date',
				display : '借用日期',
				width : 70
			}, {
				name : 'targetdate',
				display : '预计归还日期',
				width : 80
			}, {
                name : 'formNo',
                display : '当前所属业务',
                width : 110
            }]
		},
		searchitems : [{
			display : '设备名称',
			name : 'device_nameSearch'
		}, {
			display : '机身码',
			name : 'bCoding'
		}, {
			display : '描述信息',
			name : 'descriptionSearch'
		}],
		// 审批状态数据过滤
		comboEx : [{
			text : "设备类型",
			key : 'typeid',
			data : formTypeArr
		},{
			text : "显示锁定",
			key : 'isLock',
			data : [{
                text : '是',
                value : '1'
            },{
                text : '否',
                value : '0'
            }]
		}],
 		buttonsEx : [{
 			name : 'addReturn',
 			text : "生成归还单",
 			icon : 'add',
 			action : function(row, rows, rowIds, g) {
				alert("您好，新OA已上线，请到新OA操作。谢谢！");
				return false;
				if (row) {
					//所有行
					var allRows = g.getAllSubSelectRowDatas();
                    var rowIdArr = [];
                    var projectIdArr = [];
                    var projectCodeArr = [];
                    var projectNameArr = [];
                    var managerIdArr = [];
                    var managerNameArr = [];
                    var deptId = '';
					for(var i=0; i< allRows.length ; i++){
                        if(allRows[i].formNo != ''){
                            alert('设备【'+ allRows[i].device_name +'】已经保存在单据【'+ allRows[i].formNo +'】，请重新选择');
                            return false;
                        }
						rowIdArr.push(allRows[i].borrowItemId);
                        if(deptId == ''){
                            deptId = allRows[i].deptId;
                        }else{
                            if(deptId != allRows[i].deptId){
                                alert('不同所属部门的设备不能合并生成一张单据，请重新选择');
                                return false;
                            }
                        }
                        if(allRows[i].projectId != '' && $.inArray(allRows[i].projectId,projectIdArr) == -1){
                        	projectIdArr.push(allRows[i].projectId);
                        }
                        if(allRows[i].projectCode != '' && $.inArray(allRows[i].projectCode,projectCodeArr) == -1){
                        	projectCodeArr.push(allRows[i].projectCode);
                        }
                        if(allRows[i].projectName != '' && $.inArray(allRows[i].projectName,projectNameArr) == -1){
                        	projectNameArr.push(allRows[i].projectName);
                        }
                        if(allRows[i].managerId != '' && $.inArray(allRows[i].managerId,managerIdArr) == -1){
                        	managerIdArr.push(allRows[i].managerId);
                        }
                        if(allRows[i].managerName != '' && $.inArray(allRows[i].managerName,managerNameArr) == -1){
                        	managerNameArr.push(allRows[i].managerName);
                        }
					}
					//归还单生成页面
					if(rowIdArr.length > 0){
						showOpenWin("?model=engineering_resources_ereturn&action=toAdd&rowsId="+rowIdArr.toString()
                            +"&projectId="+projectIdArr.toString()+"&projectCode="+projectCodeArr.toString()
                            +"&projectName="+projectNameArr.toString()+"&managerId="+managerIdArr.toString()
                            +"&managerName="+managerNameArr.toString()+"&deviceDeptId="+deptId,1,700,1100);
					}
					else
						alert('请先选择记录');
				} else {
					alert('请先选择记录');
				}
			}
 		},{
 			name : 'addRenew',
 			text : "生成续借单",
 			icon : 'add',
 			action : function(row, rows, rowIds, g) {
				alert("您好，新OA已上线，请到新OA操作。谢谢！");
				return false;
				if (row) {
					//所有行
					var allRows = g.getAllSubSelectRowDatas();
                    var rowIdArr = [];
                    var projectId = '';
                    var deptId = '';
					for(var i=0; i< allRows.length ; i++){
                        if(allRows[i].formNo != ''){
                            alert('设备【'+ allRows[i].device_name +'】已经保存在单据【'+ allRows[i].formNo +'】，请重新选择');
                            return false;
                        }
                        rowIdArr.push(allRows[i].borrowItemId);
                        if(deptId == ''){
                            deptId = allRows[i].deptId;
                        }else{
                            if(deptId != allRows[i].deptId){
                                alert('不同所属部门的设备不能合并生成一张单据，请重新选择');
                                return false;
                            }
                        }
                        if(projectId == ''){
                            projectId = allRows[i].projectId;
                        }else{
                            if(projectId != allRows[i].projectId){
                                alert("不同项目的设备不能合并生成一张单据，请重新选择");
                                return false;
                            }
                        }
					}
					//续借单生成页面
					if(rowIdArr.length > 0){
						showOpenWin("?model=engineering_resources_erenew&action=toAdd&rowsId="+rowIdArr.toString()
                            +"&projectId="+allRows[0].projectId+"&projectCode="+allRows[0].projectCode
                            +"&projectName="+allRows[0].projectName+"&managerId="+allRows[0].managerId
                            +"&managerName="+allRows[0].managerName+"&flag="+allRows[0].flag+"&deviceDeptId="+deptId,1,700,1100);
					}
					else
						alert('请先选择记录');
				} else {
					alert('请先选择记录');
				}
			}
 		},{
 			name : 'addReturn',
 			text : "生成转借单",
 			icon : 'add',
 			action : function(row, rows, rowIds, g) {
				alert("您好，新OA已上线，请到新OA操作。谢谢！");
				return false;
				if (row) {
                    //所有行
                    var allRows = g.getAllSubSelectRowDatas();
                    var rowIdArr = [];
                    var projectIdArr = [];
                    var projectCodeArr = [];
                    var projectNameArr = [];
                    var managerIdArr = [];
                    var managerNameArr = [];
                    var deptId = '';
                    for(var i=0; i< allRows.length ; i++){
                        if(allRows[i].formNo != ''){
                            alert('设备【'+ allRows[i].device_name +'】已经保存在单据【'+ allRows[i].formNo +'】，请重新选择');
                            return false;
                        }
                        rowIdArr.push(allRows[i].borrowItemId);
                        if(deptId == ''){
                            deptId = allRows[i].deptId;
                        }else{
                            if(deptId != allRows[i].deptId){
                                alert('不同所属部门的设备不能合并生成一张单据，请重新选择');
                                return false;
                            }
                        }
                        if(allRows[i].projectId != '' && $.inArray(allRows[i].projectId,projectIdArr) == -1){
                        	projectIdArr.push(allRows[i].projectId);
                        }
                        if(allRows[i].projectCode != '' && $.inArray(allRows[i].projectCode,projectCodeArr) == -1){
                        	projectCodeArr.push(allRows[i].projectCode);
                        }
                        if(allRows[i].projectName != '' && $.inArray(allRows[i].projectName,projectNameArr) == -1){
                        	projectNameArr.push(allRows[i].projectName);
                        }
                        if(allRows[i].managerId != '' && $.inArray(allRows[i].managerId,managerIdArr) == -1){
                        	managerIdArr.push(allRows[i].managerId);
                        }
                        if(allRows[i].managerName != '' && $.inArray(allRows[i].managerName,managerNameArr) == -1){
                        	managerNameArr.push(allRows[i].managerName);
                        }
                    }
					//转借单生成页面
					if(rowIdArr.length > 0){
						showOpenWin("?model=engineering_resources_elent&action=toAdd&rowsId="+rowIdArr.toString()
							+"&projectId="+projectIdArr.toString()+"&projectCode="+projectCodeArr.toString()
	                        +"&projectName="+projectNameArr.toString()+"&managerId="+managerIdArr.toString()
	                        +"&managerName="+managerNameArr.toString()+"&deviceDeptId="+deptId,1,700,1100);
					}
					else
						alert('请先选择记录');
				} else {
					alert('请先选择记录');
				}
			}
 		},{
 	        name: 'addBatch',
 	        text: "批量操作",
 	        icon: 'add',
 	        action: function () {
				alert("您好，新OA已上线，请到新OA操作。谢谢！");
				return false;
 	        	showModalWin("?model=engineering_device_esmdevice&action=toBatch");
 	        }
 	    }],
		sortname : 'g.typename,c.device_name',
		sortorder : 'ASC'
	});
});