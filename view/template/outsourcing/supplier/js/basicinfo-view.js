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
					display : '����',
					name : 'name',
					type : 'txt',
					width : 100
				},{
					display : 'ְ��',
					name : 'jobName',
					type : 'txt',
					width : 100
				}, {
					display : '�绰',
					name : 'mobile',
					type : 'txt',
					width : 120
				}, {
					display : '����',
					name : 'email',
					type : 'txt',
					width : 200
				}, {
					display : '��ע',
					name : 'remarks',
					type : 'txt',
					width : 200
				},{
					display : '�Ƿ�Ĭ����ϵ��',
					name : 'defaultContact',
					type : 'checkbox',
					width : 70,
					process : function(v) {
						if (v == "on") {
							return "��";
						} else {
							return "��";
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
					display : '�˻�����',
					name : 'suppAccount',
					type : 'txt',
					width : 200,
					validation : {
						required : true
					}
				},{
					display : '������',
					name : 'bankName',
					type : 'txt',
					width : 300
				},{
					display : '�˺�',
					name : 'accountNum',
					type : 'txt',
					width : 300
				}, {
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 200
				},{
					display : '�Ƿ�Ĭ���˺�',
					name : 'isDefault',
					type : 'checkbox',
					width : 70,
					process : function(v) {
						if (v == "on") {
							return "��";
						} else {
							return "��";
						}
					}
				}]
	});

	function tableHead(){
	var trHTML =  '';
	var detailRows = ['����','�м�','�߼�','����'];
	var detailArr = ['����'];
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
					display : '��������',
					name : 'skillArea',
					type : 'txt',
					width : 200,
					readonly:true
				},  {
					display : '��������ID',
					name : 'skillAreaId',
					type:'hidden'
				},{
					display : '����',
					name : 'primaryNum',
					type : 'txt',
					width : 70
				}, {
					display : '�м�',
					name : 'middleNum',
					type : 'txt',
					width : 70
				}, {
					display : '�߼�',
					name : 'expertNum',
					type : 'txt',
					width : 70
				}, {
					display : '����',
					name : 'totalNum',
					type : 'txt',
					width : 70
				},{
					display : 'ռ��',
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
					display : '��ע',
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
					display : '��������',
					name : 'experience',
					type : 'txt',
					width : 100
				}, {
					display : '����',
					name : 'personNum',
					type : 'txt',
					width : 70,
					validation : {
						required : true,
						custom : ['onlyNumber']
					}
				}, {
					display : 'ռ��(%)',
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
					display : '��ע',
					name : 'remark',
					align : 'left',
					width : 250
				}]
	});

        })