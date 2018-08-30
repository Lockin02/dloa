$(document).ready(function() {
	//初始化质检明细
	initDetail();

	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'qualityReport'
	});

	//初始化邮件接收人
	initMailPerson();
})

//初始化邮件接收人
function initMailPerson(){
	//默认邮件接收人
	var TO_NAME = $("#TO_NAME").val();
	var TO_ID = $("#TO_ID").val();
	var TO_NAMEArr = TO_NAME.split(",");
	var TO_IDArr = TO_ID.split(",");

	var applyUserCode = $("#applyUserCode").val();
	if(applyUserCode != "" && jQuery.inArray(applyUserCode,TO_IDArr) == -1){
		TO_IDArr.push(applyUserCode);
	}

	var applyUserName = $("#applyUserName").val();
	if(applyUserName != "" && jQuery.inArray(applyUserName,TO_NAMEArr) == -1){
		TO_NAMEArr.push(applyUserName);
	}

	$("#TO_NAME").val(TO_NAMEArr.toString());
	$("#TO_ID").val(TO_IDArr.toString());
}

//初始化质检明细
function initDetail(){
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapplyitem&action=editItemJson',
		title :'质检申请明细',
		param : {
			mainId : $("#id").val()
		},
		type : 'view',
		isAddAndDel : false,
		colModel : [ {
			name : 'id',
			display : "<input type='checkbox' id='checkAll' onclick='checkAll();' value='all'/>",
			width : 30,
			process : function(v,row){
				if(row.status != "4"){
					return "";
				}else{
					return "<input type='checkbox' name='idCheckbox' value='" + row.id +"'/>";
				}
			},
			type : 'statictext'
		}, {
			name : 'productCode',
			display : '物料编号',
			width : 90
		}, {
			name : 'productName',
			display : '物料名称',
			width : 150
		}, {
			name : 'pattern',
			display : '型号',
			width : 100
		}, {
			name : 'unitName',
			display : '单位',
			width : 50
		}, {
			name : 'checkTypeName',
			display : '质检方式',
			width : 80
		}, {
			name : 'qualityNum',
			display : '报检数量',
			width : 80
		}, {
			name : 'assignNum',
			display : '已下达数量',
			width : 80
		}, {
			name : 'standardNum',
			display : '合格数量',
			width : 80
		},{
			name : 'status',
			display : '处理结果',
			width : 80,
			process : function(v){
				switch(v){
					case "0" : return "质检放行";
					case "1" : return "部分处理";
					case "2" : return "处理中";
					case "3" : return "质检完成";
					case "4" : return "未处理";
                    case "5" : return "损坏放行";
					default : return "";
				}
			}
		},{
			name : 'dealUserName',
			display : '处理人',
			width : 80
		},{
			name : 'dealTime',
			display : '处理时间',
			width : 140
		}, {
			display : '批次号',
			name : 'batchNum',
			width : 80
		}, {
			display : '序列号',
			name : 'serialName',
			process : function(v){
				if(v!="" && v != '物料配置中的内容'){
					return "<a href='javascript:void(0);' onclick='showOpenWin(\"?model=stock_serialno_serialno&action=toViewFormat"+
						"&nos=" + v
						+"\",1,400,600)'>点击查看</a>";
				}else{
					return '无';
				}
			},
			width : 80
		}]
	});
}

//确认免检
function confirmPass(){
	//勾选对象
	var objArr = $("input[name='idCheckbox']:checked");

	if(objArr.length == 0){
		alert('没有选中任何物料');
		return false;
	}

	//确认选中物料
	if(confirm('确认对选中的物料进行质检放行操作吗？')){
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
		    data: {"ids" : ids , 'issend' : $("input[name='issend'][checked]").val() , "TO_ID" : $("#TO_ID").val()},
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

//选择全部
function checkAll(){
	//全选对象
	var checkedVal = $("#checkAll").attr("checked");

	//勾选对象
	var objArr = $("input[name='idCheckbox']");
	objArr.each(function(i,n){
		$(this).attr("checked",checkedVal);
	});
}