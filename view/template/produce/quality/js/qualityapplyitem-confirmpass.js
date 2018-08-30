$(document).ready(function() {
	//初始化质检明细
	initDetail();

	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'qualityReport'
	});

    if ($("#isDamagePass").val() == "1") {
        $("#damageBtn").show();
    }
});

//初始化邮件接收人
function initMailPerson(){
	//默认邮件接收人
	var TO_NAME = $("#defaultUserName").val();
	var TO_ID = $("#defaultUserCode").val();
	var TO_NAMEArr = TO_NAME == "" ? [] : TO_NAME.split(",");
	var TO_IDArr = TO_ID == "" ? [] : TO_ID.split(",");

	var objGrid = $("#itemTable");
    objGrid.yxeditgrid("getCmpByCol", "applyUserName").each(function(i,n) {
		//过滤掉删除的行
		if($("#qualityapply[items]_" + i +"_isDelTag").length == 0){
			if(jQuery.inArray(this.value,TO_NAMEArr) == -1){
				TO_NAMEArr.push(this.value);
			}
		}
	});

    objGrid.yxeditgrid("getCmpByCol", "applyUserCode").each(function(i,n) {
		//过滤掉删除的行
		if($("#qualityapply[items]_" + i +"_isDelTag").length == 0){
			if(jQuery.inArray(this.value,TO_IDArr) == -1){
				TO_IDArr.push(this.value);
			}
		}
	});

	$("#TO_NAME").val(TO_NAMEArr.toString());
	$("#TO_ID").val(TO_IDArr.toString());
}

//初始化质检明细
function initDetail(){
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=confirmPassJson',
		param : {
			'idArr' : $("#id").val(),
			'status' : '4'
		},
		isAdd : false,
		event : {
			'reloadData' : function(e){
				//初始化邮件接收人
				initMailPerson();
			},
			"removeRow" : function(e,rowNum){
				//初始化邮件接收人
				initMailPerson();
			}
		},
		colModel : [{
			name : 'id',
			display : "id",
			type : 'hidden'
		}, {
			name : 'relDocId',
			display : "relDocId",
			type : 'hidden'
		}, {
			name : 'relDocType',
			display : "relDocType",
			type : 'hidden'
		}, {
			name : 'applyUserCode',
			display : "applyUserCode",
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '物料编号',
			width : 90,
			type : 'statictext'
		}, {
			name : 'productName',
			display : '物料名称',
			width : 180,
			type : 'statictext'
		}, {
			name : 'pattern',
			display : '型号',
			width : 130,
			type : 'statictext'
		}, {
			name : 'unitName',
			display : '单位',
			width : 70,
			type : 'statictext'
		}, {
			name : 'checkTypeName',
			display : '质检方式',
			width : 70,
			type : 'statictext'
		}, {
			name : 'qualityNum',
			display : '报检数量',
			width : 70,
			type : 'statictext'
		}, {
			name : 'relDocCode',
			display : '源单编号',
			type : 'statictext'
		}, {
			name : 'applyUserName',
			display : '申请人',
			type : 'statictext'
		}]
	});
}

//确认免检
function confirmPass(){
	var objGrid = $("#itemTable");
	var objArr = objGrid.yxeditgrid("getCmpByCol", "id");

	if(objArr.length == 0){
		alert('没有选中任何物料');
		return false;
	}

	//确认选中物料
	if(confirm('确认将当前的物料做质检放行处理吗？')){
		//id数组缓存
		var idArr = [];
		objArr.each(function(i,n){
			idArr.push(this.value);
		});
		var ids = idArr.toString();

		//确认免检
		$.ajax({
		    type: "POST",
		    url: "?model=produce_quality_qualityapplyitem&action=confirmPass",
		    data: {
	    		"ids" : ids ,
    			'issend' : $("input[name='issend']:checked").val() ,
    			"TO_ID" : $("#TO_ID").val(),
    			"passReason" : $("#passReason").val()
    		},
		    async: false,
		    success: function(data){
		   		if(data == 1){
					alert('操作成功');
		   	    }else{
					alert('操作失败');
		   	    }
			}
		});

		//关闭本页以及刷新列表
		self.parent.tb_remove();
		parent.show_page();
	}
}

//损坏放行
function damagePass(){
    var objGrid = $("#itemTable");
    var objArr = objGrid.yxeditgrid("getCmpByCol", "id");

    if(objArr.length == 0){
        alert('没有选中任何物料');
        return false;
    }

    //确认选中物料
    if(confirm('确认将当前的物料做损坏放行处理吗？')){
        //id数组缓存
        var idArr = [];
        objArr.each(function(i,n){
            idArr.push(this.value);
        });
        var ids = idArr.toString();

        //确认免检
        $.ajax({
            type: "POST",
            url: "?model=produce_quality_qualityapplyitem&action=damagePass",
            data: {
                "ids" : ids ,
                'issend' : $("input[name='issend']:checked").val() ,
                "TO_ID" : $("#TO_ID").val(),
                "passReason" : $("#passReason").val()
            },
            async: false,
            success: function(data){
                if(data == 1){
                    alert('操作成功');
                }else{
                    alert('操作失败');
                }
            }
        });

        //关闭本页以及刷新列表
        self.parent.tb_remove();
        parent.show_page();
    }
}