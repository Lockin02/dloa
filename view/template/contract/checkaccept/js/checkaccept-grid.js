var show_page = function(page) {
	$("#checkacceptGrid").yxgrid("reload");
};
$(function() {
	$("#checkacceptGrid").yxgrid({
		model : 'contract_checkaccept_checkaccept',
		title : '合同验收单',
		isAddAction : false,
		isEditAction :false,
		isViewAction :false,
		isDelAction :false,
		isOpButton : false,
		param:{checkStatus:$("#checkStatus").val()},
		//列信息
		colModel : [ {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'confirmStatus',
			display : '确认',
			sortable : true,
			process : function(v){
				if(v == "已确认"){
					return '<img title="已确认" src="images/icon/ok3.png">';
				}
			},
			width : 20,
			align : 'center'
		}, {
			name : 'checkStatus',
			display : '验收',
			sortable : true,
			process : function(v){
				if(v == "已验收"){
					return '<img title="已验收" src="images/icon/ok3.png">';
				}
			},
			width : 20,
			align : 'center'

		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 120,
			process : function(v,row){
				if(row.isChange == 1){
					return "<span style='color:red'>"+v+"</span>"
				}else{
					return v;
				}
			}
		}, {
			name : 'realEndDateView',
			display : '合同完成时间',
			sortable : true,
            align : 'center',
            process : function(v){
                if(v == ""){
                    return "-"
                }else{
                    return v;
                }
            }
		}, {
			name : 'clause',
			display : '验收款项',
			sortable : true
		}, {
			name : 'checkDate',
			display : '预计验收日期',
			width : 125,
			sortable : true,
			process : function(v,row){
				if(row.confirmStatus == '已确认' && row.checkStatus == '已验收'){
					return v+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="查看变更历史" src="images/icon/view.gif"></span>';
				}else if(row.confirmStatus == '已确认'){
					return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="查看变更历史" src="images/icon/view.gif"></span>';
				}else{
					return '<input type="text" id="checkDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>'+'<span class="blue" onclick = "changeHistory('+row.id+');"> <img title="查看变更历史" src="images/icon/view.gif"></span>';
				}
			}
		}, {
			name : 'checkDateOld',
			display : '预计验收日期Old',
			process : function(v,row){
				if(row.confirmStatus == '已确认'){
					return '<input type="text" id="checkDateOld'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>';
				}else if(row.checkStatus == '已验收'){
					return v;
				}else{
					return '<input type="text" id="checkDateOld'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>';
				}
			},
			hide : 'true'
		}, {
			name : 'isSend',
			display : '验收日期推送',
			sortable : true,
			width : 80
		}, {
			name : 'remind',
			display : '到期未推送提醒',
			sortable : true,
			process: function(v){
				return "已提醒"+v+"次";
			}
		}, {
			name : 'realCheckDate',
			display : '实际验收日期',
			sortable : true,
			process : function(v,row){
				if(row.checkStatus == '已验收'){
					return v;
				}else{
					return '<input type="text" id="realCheckDate'+row.id+'" onfocus="WdatePicker();" style="width:100px" class="Wdate" value="'+ v +'"/>';
				}
			}
		}, {
			name : 'checkFile',
			display : '验收文本上传',
			sortable : true,
			process : function(v,row){
				if(row.checkStatus == '已验收'){
					return v;
				}else{
					return v+'<input type="button" value="文本上传" onclick = "uploadfile('+row.id+');"/>'
				}
			}
		}, {
			name : 'deal',
			display : '操作',
			sortable : true,
			width : 40,
			process : function(v,row){
				if(row.confirmStatus == '未确认'){
					return '<input type="button" value="确认" onclick = "confirmDate('+row.id+');"/>';
				}else if(row.confirmStatus == '已确认' && row.checkStatus == '未验收'){
					var temp = 1;
					if(row.checkFile == "" || row.checkFile == "暂无任何附件"){
						temp = 0;
					}
					return '<input type="button" value="验收" onclick = "check('+row.id+','+temp+');"/>';
				}
			}
		}, {
			name : 'deal',
			display : '变更操作',
			sortable : true,
			width : 85,
			process : function(v,row){
				if(row.confirmStatus == '已确认' && row.checkStatus == '未验收'){
					var temp = 1;
					if(row.checkFile == "" || row.checkFile == "暂无任何附件"){
						temp = 0;
					}
					return '<input type="button" value="变更预计日期" onclick = "changeDate('+row.id+');"/> ';
//					'<input type="button" value="变更历史" onclick = "changeHistory('+row.id+');"/>';
				}else if(row.confirmStatus == '已确认' && row.checkStatus == '已验收'){
					return '<input type="button" value="查看变更历史" onclick = "changeHistory('+row.id+');"/>';
				}
			}
		}, {
			name : 'reason',
			display : '超期/异常验收原因',
			sortable : true,
			process : function(v,row){
				if(row.checkStatus == "已验收"){
					return v;
				}else{
					return '<input type="text" id="reason'+row.id+'" style="width:120px" value="'+v+'">';
				}
			},
			width : 120
		} ],
//		toViewConfig : {
//			action : 'toView'
//		},
		searchitems : [ {
			display : "合同编号",
			name : 'contractCodeSearch'
		} ,{
			display : "验收款项",
			name : 'clauseSearch'
		} ],
		comboEx : [{
			text: "验收状态",
			key: 'checkStatus',
			data : [ {
				text : '未验收',
				value : '未验收'
			},{
				text : '已验收',
				value : '已验收'
			}]
		},{
			text: "确认状态",
			key: 'confirmStatus',
			data : [ {
				text : '未确认',
				value : '未确认'
			},{
				text : '已确认',
				value : '已确认'
			}]
		}]
	});
});

//确认预计验收时间
function confirmDate(id){
	if(window.confirm('确定要确认预计验收时间吗？')){
		if($("#checkDate"+id).val() != ''){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=confirm",
				data : {
					id : id,
					checkDate : $("#checkDate"+id).val()
				},
				success : function(msg) {
					if (msg) {
						alert('确认成功！');
						show_page();
					}else {
					     alert("确认失败！")
					}
				}
			});
		}else{
			alert('预计验收时间不能为空');
		}
	}
}


//变更预计验收时间
function changeDate(id){
	if(window.confirm('确定要变更预计验收时间吗？')){
		if($("#checkDate"+id).val() != ''){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=change",
				data : {
					id : id,
					checkDate : $("#checkDate"+id).val(),
					checkDateOld : $("#checkDateOld"+id).val()
				},
				success : function(msg) {
					if (msg) {
						alert('变更成功！');
						show_page();
					}else {
					     alert("变更失败！")
					}
				}
			});
		}else{
			alert('预计验收时间不能为空');
		}
	}
}

//查看变更历史
function changeHistory(id){
	showThickboxWin('?model=contract_checkaccept_checkaccept&action=showChanceHistory&id='
			+ id
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600');
}

//验收
function check(id,temp){
	if(window.confirm('确定验收吗？')){
		if(temp == 1){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=check",
				data : {
					id : id,
					reason : $("#reason"+id).val(),
					realCheckDate : $("#realCheckDate"+id).val(),
					isError : 0
				},
				success : function(msg) {
					if (msg == 1) {
						alert('验收成功');
						show_page();
					}else if(msg == 2){
						alert('请填写异常验收原因');
					}else if(msg == 3){
						alert('验收已超期，请填写超期验收原因');
					}else{
						alert('验收失败');
					}
				}
			});
		}else if(window.confirm('未上传文本，是否为异常验收？')){
			$.ajax({
				type : "POST",
				url : "?model=contract_checkaccept_checkaccept&action=check",
				data : {
					id : id,
					reason : $("#reason"+id).val(),
					realCheckDate : $("#realCheckDate"+id).val(),
					isError : 1
				},
				success : function(msg) {
					if (msg == 1) {
						alert('验收成功');
						show_page();
					}else if(msg == 2){
						alert('请填写异常验收原因');
					}else if(msg == 3){
						alert('验收已超期，请填写超期验收原因');
					}else{
						alert('验收失败');
					}
				}
			});
		}else{
			alert('请先上传文本');
		}
	}
}

//跳转到上传附件

function uploadfile(id){
	showThickboxWin("?model=contract_checkaccept_checkaccept&action=toUploadFile&id="
			+ id
			+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900");
}
