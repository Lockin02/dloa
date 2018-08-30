$(document).ready(function() {

	if($("#actType").val()!=""){
		$("#closeBtn").hide();
	}

	$("#linkmanListInfo").yxeditgrid({
		objName : 'basicinfo[linkman]',
		url : '?model=outsourcing_supplier_linkman&action=listJson',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
//		dir : 'ASC',
		type : 'view',
		colModel : [{
					display : '姓名',
					name : 'name',
					type : 'txt',
					width : 100
				},{
					display : '职务',
					name : 'jobName',
					type : 'txt',
					width : 100
				}, {
					display : '电话',
					name : 'mobile',
					type : 'txt',
					width : 120
				}, {
					display : '邮箱',
					name : 'email',
					type : 'txt',
					width : 200
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
					process : function(v) {
						if (v == "on") {
							return "是";
						} else {
							return "否";
						}
					}
				}]
	});

	$("#bankListInfo").yxeditgrid({
		objName : 'basicinfo[bankinfo]',
		url : '?model=outsourcing_supplier_bankinfo&action=listJson',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
//		dir : 'ASC',
		type : 'view',
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
					width : 300
				},{
					display : '账号',
					name : 'accountNum',
					type : 'txt',
					width : 300
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
					process : function(v) {
						if (v == "on") {
							return "是";
						} else {
							return "否";
						}
					}
				}]
	});

	function tableHead(){
	var trHTML =  '';
	var detailRows = ['初级','中级','高级','汇总'];
	var detailArr = ['人数'];
	var trObj = $("#hrListInfo tr:eq(0)");
	var tdArr = trObj.children();
	var mark = 1;
	var m = 0;
	tdArr.each(function(i,n){
		if($.inArray($(this).text(),detailRows) != -1){
			if(mark == 1){
				$(this).attr("colSpan",4).text(detailArr[m]);
				mark = 0;
				m++;
			}else{
				$(this).remove();
				mark = 0;
			}
		}else{
			$(this).attr("rowSpan",4);
		}
	});

	trHTML+='<tr class="main_tr_header">';
	for(m=0;m<detailRows.length;m++){
		trHTML+='<th><div class="divChangeLine" style="max-width:70px;">'+detailRows[m]+'</div></th>';
	}
	trHTML+='</tr>';
	trObj.after(trHTML);
}

	$("#hrListInfo").yxeditgrid({
		objName : 'basicinfo[hrinfo]',
		url : '?model=outsourcing_supplier_hrinfo&action=listJsonView',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
//		dir : 'ASC',
		type : 'view',
		colModel : [{
					display : '技能领域',
					name : 'skillArea',
					type : 'txt',
					width : 200,
					readonly:true
				},  {
					display : '技能领域ID',
					name : 'skillAreaId',
					type:'hidden'
				},{
					display : '初级',
					name : 'primaryNum',
					type : 'txt',
					width : 70
				}, {
					display : '中级',
					name : 'middleNum',
					type : 'txt',
					width : 70
				}, {
					display : '高级',
					name : 'expertNum',
					type : 'txt',
					width : 70
				}, {
					display : '汇总',
					name : 'totalNum',
					type : 'txt',
					width : 70
				},{
					display : '占比',
					name : 'proportion',
					type : 'txt',
					width : 70,
					readonly:true,
					process : function(v) {
						if (v != "") {
							return v+"%";
						} else {
							return "";
						}
					}
				}, {
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : 250
				}]
	});
	tableHead();

	$("#workListInfo").yxeditgrid({
		objName : 'basicinfo[workinfo]',
		url : '?model=outsourcing_supplier_workInfo&action=listJsonView',
		param : {
			dir : 'ASC',
			suppId :$("#id").val()
		},
		type : 'view',
		colModel : [{
					display : '工作经验',
					name : 'experience',
					type : 'txt',
					width : 100
				}, {
					display : '人数',
					name : 'personNum',
					type : 'txt',
					width : 70,
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : '占比(%)',
					name : 'proportion',
					type : 'txt',
					tclass:"readOnlyTxt",
					width : 70,
					readonly:true,
					process : function(v) {
						if (v != "") {
							return v+"%";
						} else {
							return "";
						}
					}
				}, {
					display : '备注',
					name : 'remark',
					align : 'left',
					width : 250
				}]
	});

        })