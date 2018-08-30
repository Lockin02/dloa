$(document).ready(function() {
	 /**
			 * 编码唯一性验证
			 */

			var url = "?model=outsourcing_supplier_basicinfo&action=checkRepeat";
//			if ($("#id").val()) {
//				url += "&id=" + $("#id").val();
//			}
			$("#suppName").ajaxCheck({
						url : url,
						alertText : "* 该外包供应商已存在",
						alertTextOk : "* OK"
					});


		//单选区域
		$("#officeName").yxcombogrid_office({
			hiddenId : 'officeId',
			gridOptions : {
				showcheckbox : false
			}
		});

	$("#linkmanListInfo").yxeditgrid({
		objName : 'basicinfo[linkman]',
		dir : 'ASC',
		colModel : [{
					display : '姓名',
					name : 'name',
					type : 'txt',
					width : 100,
					validation : {
						required : true
					}
				},{
					display : '职务',
					name : 'jobName',
					type : 'txt',
					width : 100,
					validation : {
						required : true
					}
				}, {
					display : '电话',
					name : 'mobile',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					}
				}, {
					display : '邮箱',
					name : 'email',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				}, {
					display : '备注',
					name : 'remarks',
					type : 'txt',
					width : 200
				},{
					display : '是否默认联系人',
					name : 'defaultContact',
					type : 'checkbox',
					width : 70,
					process:function($input, rowData){
						var rowNum = $input.data("rowNum");
						if(rowNum==0){
							$input.attr('checked',true);
						}
					}
				}]
	});

	$("#bankListInfo").yxeditgrid({
		objName : 'basicinfo[bankinfo]',
		dir : 'ASC',
		colModel : [{
					display : '账户名称',
					name : 'suppAccount',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				},{
					display : '开户行',
					name : 'bankName',
					type : 'txt',
					width : 300,
					validation : {
						required : true
					}
				},{
					display : '账号',
					name : 'accountNum',
					type : 'txt',
					width : 300,
					validation : {
						required : true
					},
					process:function($input, rowData){
						var rowNum = $input.data("rowNum");
						$("#bankListInfo_cmp_accountNum" + rowNum).blur(function() {
							if ($(this).val().trim()
							&& !$("#bankListInfo_cmp_suppAccount" + rowNum).val().trim()) {
								$("#bankListInfo_cmp_suppAccount" + rowNum).val($("#suppName").val());
							}
						});
					}
				}, {
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : 200
				},{
					display : '是否默认账号',
					name : 'isDefault',
					type : 'checkbox',
					width : 70,
					process:function($input, rowData){
						var rowNum = $input.data("rowNum");
						if(rowNum==0){
							$input.attr('checked',true);
						}
					}
				}]
	});

	$("#hrListInfo").yxeditgrid({
		objName : 'basicinfo[hrinfo]',
		dir : 'ASC',
	   event: {
            'removeRow': function() {
				check_all();
				checkProportion();
            }
        },
		colModel : [{
					display : '技能领域',
					name : 'skillArea',
					type : 'txt',
					width : 200,
					readonly:true,
					validation : {
						required : true
					},
					process : function($input) {
						var rowNum = $input.data("rowNum");
						$input.yxcombogrid_skillarea({
							hiddenId: 'hrListInfo_cmp_skillAreaId'+rowNum
						});
					}
				},  {
					display : '技能领域ID',
					name : 'skillAreaId',
					type:'hidden'
				},{
					display : '初级',
					name : 'primaryNum',
					type : 'txt',
					width : 70,
					staticVal:'0',
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// 第几行
							var colnum = $(this).data('colNum');// 第几列
							var grid = $(this).data('grid');// 表格组件
							var middleNum=grid.getCmpByRowAndCol(rownum, 'middleNum').val();
							var expertNum=grid.getCmpByRowAndCol(rownum, 'expertNum').val();
							grid.getCmpByRowAndCol(rownum, 'totalNum').val(expertNum*1+middleNum*1+this.value*1);
							check_all();
							checkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '中级',
					name : 'middleNum',
					type : 'txt',
					width : 70,
					staticVal:'0',
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// 第几行
							var colnum = $(this).data('colNum');// 第几列
							var grid = $(this).data('grid');// 表格组件
							var primaryNum=grid.getCmpByRowAndCol(rownum, 'primaryNum').val();
							var expertNum=grid.getCmpByRowAndCol(rownum, 'expertNum').val();
							grid.getCmpByRowAndCol(rownum, 'totalNum').val(expertNum*1+primaryNum*1+this.value*1);
							check_all();
							checkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '高级',
					name : 'expertNum',
					type : 'txt',
					width : 70,
					staticVal:'0',
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							var rownum = $(this).data('rowNum');// 第几行
							var colnum = $(this).data('colNum');// 第几列
							var grid = $(this).data('grid');// 表格组件
							var middleNum=grid.getCmpByRowAndCol(rownum, 'middleNum').val();
							var primaryNum=grid.getCmpByRowAndCol(rownum, 'primaryNum').val();
							grid.getCmpByRowAndCol(rownum, 'totalNum').val(primaryNum*1+middleNum*1+this.value*1);
							check_all();
							checkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '汇总',
					name : 'totalNum',
					type : 'txt',
					staticVal:'0',
					readonly:true,
					tclass:"readOnlyTxt",
					width : 70
				},{
					display : '占比(%)',
					name : 'proportion',
					type : 'txt',
					tclass:"readOnlyTxt",
					staticVal:'0.00',
					width : 70,
					readonly:true
				}, {
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : 250
				}]
	});

	$("#workListInfo").yxeditgrid({
		objName : 'basicinfo[workinfo]',
		dir : 'ASC',
	   event: {
            'removeRow': function() {
            		checkWorkAll();
					checkWorkProportion();
            }
        },
		colModel : [{
					display : '工作经验',
					name : 'experience',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				}, {
					display : '人数',
					name : 'personNum',
					type : 'txt',
					width : 70,
					staticVal:'0',
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)) {
									this.value = 0;
								}else{
								}
							}
							checkWorkAll();
							checkWorkProportion();
						}
					},
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '占比(%)',
					name : 'proportion',
					type : 'txt',
					tclass:"readOnlyTxt",
					staticVal:'0.00',
					width : 70,
					readonly:true
				}, {
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : 250
				}]
	});



	validate({
//				"suppName" : {
//					required : true
//				},
				"officeName" : {
					required : true
				},
				"province" : {
					required : true
				},
				"suppTypeCode" : {
					required : true
				},
				"suppGrade" : {
					required : true
				}
			});
})