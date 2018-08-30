$(document).ready(function() {
	if($('#auditStatusHidden').val() != "WSH"){
		//设置初始值
		setSelect('auditStatus');
	}
	var relDocType = $("#relDocType").val();
	var show = '';
	if(relDocType != 'ZJSQYDSL'){
		show = 'hidden';
	}
	//质检物料
	$("#ereportequitem").yxeditgrid({
		objName : 'qualityereport[ereportequitem]',
		title : '物料信息及检验结果',
		url : "?model=produce_quality_qualityereportequitem&action=listJson",
		param : {"mainId" : $("#id").val() },
		isAddAndDel : false,
		event : {
			'reloadData' : function(){
				//初始化 不合格
				initConfirm();
				//类型为换货归还时初始化
				if(relDocType != 'ZJSQYDSL' && relDocType != 'ZJSQYDSC'){
					initHHGH();
				}else if(relDocType == 'ZJSQYDSC'){
					initSC();
				}
			}
		},
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
			name : 'relItemId',
			display : 'relItemId',
			type : 'hidden'
		}, {
			name : 'productId',
			display : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '物料编码',
			width : 80,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'productName',
			display : '物料名称',
			width : 130,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'pattern',
			display : '规格型号',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'supplierName',
			display : '供应商',
			width : 130,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'supportNum',
			display : '报检数量',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'thisCheckNum',
			display : '本次质检数量',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
            name: 'samplingNum',
            display: '抽检数量',
            width : 70,
            tclass : 'readOnlyTxtMiddle',
            readonly : true
        }, {
			name : 'unitName',
			display : '单位',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'supportTime',
			display : '报检时间',
			width : 130,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'relDocCode',
			display : '源单号',
			width : 90,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'purchaserName',
			display : '申请人',
			width : 90,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'priorityName',
			display : '紧急程度',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'qualitedNum',
			display : '合格数',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'produceNum',
			display : '不合格数',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			event : {
				click : function(e){
					var rowNum = $(this).data("rowNum");
					var g = e.data.gird;
					var rowNum = e.data.rowNum;
					showThickboxWin("?model=produce_quality_serialno&action=toDealView"
						+"&relDocId="+ g.getCmpByRowAndCol(rowNum, 'relItemId').val()
						+"&relDocType=qualityEreport"
						+"&productId="+ g.getCmpByRowAndCol(rowNum, 'productId').val()
						+"&productCode="+ g.getCmpByRowAndCol(rowNum, 'productCode').val()
						+"&productName="+ g.getCmpByRowAndCol(rowNum, 'productName').val()
						+"&pattern="+ g.getCmpByRowAndCol(rowNum, 'pattern').val()
						+"&productNum=" + $(this).val()
						+"&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
				}
		    },
			readonly : true
		}, {
			name : 'passNum',
			display : '正常接收数量',
			width : 70,
			event : {
				blur : function(){
					if(relDocType == 'ZJSQYDSL'){
						//计算质检内容数量
						countEquConfirmNum('passNum',$(this).data("rowNum"));
					}
				}
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			type : show
		}, {
			name : 'receiveNum',
			display : '让步接收数量',
			width : 70,
			event : {
				blur : function(){
					if(relDocType == 'ZJSQYDSL'){
						//计算质检内容数量
						countEquConfirmNum('receiveNum',$(this).data("rowNum"));
					}
				}
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			type : show
		}, {
			name : 'backNum',
			display : '退回数量',
			width : 70,
			event : {
				blur : function(){
					if(relDocType == 'ZJSQYDSL'){
						//计算质检内容数量
						countEquConfirmNum('backNum',$(this).data("rowNum"));
					}
				}
			},
			tclass : 'readOnlyTxtMiddle',
			readonly : true,
			type : show
		}, {
			name : 'remark',
			display : '备注',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}]
	});

	//质检内容
    var itemCols = getItemTable(relDocType);
	$("#itemTable").yxeditgrid({
		objName : 'qualityereport[items]',
		url : '?model=produce_quality_qualityereportitem&action=listJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		title : '质检内容',
		colModel : itemCols,
        event : {
            'reloadData' : function(){
                //如果是换货或者借用归还类的质检报告，则生成不合格信息表
                if(relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH"){
                    //初始化不合格部分
                    showDetail();
                }
            }
        }
	});

	if(relDocType == 'ZJSQDLBF' && $('#auditStatusHidden').val() == 'YSH'){// PMS2386 呆料报废质检如果质检报告合格了的,结果为合格且不可改
		$("#auditStatus").html('<option value="YSH" title="合格">合格</option>');
	}
});

/**
 * 显示不合格详细列表
 */
function showDetail(){
    //获取检验项目
    var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");
    var baseTitle = initFailureItemGrid(dimensionNameArr);
    $("#failureItem").yxeditgrid({
        url : '?model=produce_quality_failureitem&action=listJson',
        param : {
            mainId : $("#id").val()
        },
        type : 'view',
        title : '不合格物料信息',
        colModel : baseTitle
    });
}

//初始化不合格列表内容
function initFailureItemGrid(dimensionNameArr){
    //基本项目表头
    var baseTitle = [{
        name : 'objId',
        display : 'objId',
        type : 'hidden'
    }, {
        name : 'objCode',
        display : 'objCode',
        type : 'hidden'
    }, {
        name : 'objType',
        display : 'objType',
        type : 'hidden'
    }, {
        name : 'objItemId',
        display : 'objItemId',
        type : 'hidden'
    }, {
        name : 'productId',
        display : 'productId',
        type : 'hidden'
    }, {
        name : 'productCode',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '物料编码',
        width : 80
    }, {
        name : 'productName',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '物料名称'
    }, {
        name : 'pattern',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '规格型号'
    }, {
        name : 'unitName',
        tclass : 'readOnlyTxtShort',
        readonly : true,
        display : '单位'
    }, {
        name : 'serialNo',
        display : '序列号',
        validation : {
            required : true
        }
    }];

    //载入检验内容
    dimensionNameArr.each(function(i){
        var resultNum = i + 1;
        baseTitle.push({
            name : 'result' + resultNum,
            display : $(this).val(),
            datacode : 'ZJJDJG',
            width : 80
        });
    });

    //载入评价内容、备注等信息
    baseTitle.push({
        name : 'resultName',
        display : '鉴定结论'
    });
    baseTitle.push({
        name : 'levelName',
        display : '鉴定级别'
    });
    baseTitle.push({
        name : 'remark',
        display : '备注',
        tclass : 'txt',
        width : 120
    });
    return baseTitle;
}

//驳回报告
function toRejectReport(){
    $('#templateInfo').dialog({
        title: '填写驳回原因',
        width: 400,
        height: 200,
        modal: true
    }).dialog('open');
}

//确认驳回
function rejectReport(){
    if(confirm('确认要驳回报告吗？')){
        $.ajax({
            type : "POST",
            url : "?model=produce_quality_qualityereport&action=rejectReport",
            data : {
                "id" : $("#id").val(),
                "reason" : $("#reason").val()
            },
            success : function(msg) {
                if (msg == 1) {
                    alert('驳回成功');
                }else{
                    alert('驳回失败');
                }
                opener.show_page();
                window.close();
            }
        });
    }
}

//获取质检内容表头
function getItemTable(relDocType){
    var colsArr = [{
        name : 'dimensionName',
        tclass : 'txt',
        display : '检验项目',
        validation : {
            required : true
        }
    }, {
        name : 'examTypeName',
        tclass : 'txt',
        display : '检验方式名称'
    }];
    //如果是借用归还
    // if(relDocType != "ZJSQYDGH" && relDocType == "ZJSQYDHH"){
        colsArr.push({
            name : 'crNum',
            width : 80,
            display : 'CR',
            process : function(v){
                if(v*1 != 0){
                    return "<span class='red'>" + v + "</span>";
                }else{
                    return v;
                }
            }
        });
        colsArr.push({
            name : 'maNum',
            width : 80,
            display : 'MA',
            process : function(v){
                if(v*1 != 0){
                    return "<span class='red'>" + v + "</span>";
                }else{
                    return v;
                }
            }
        });
        colsArr.push({
            name : 'miNum',
            width : 80,
            display : 'MI',
            process : function(v){
                if(v*1 != 0){
                    return "<span class='red'>" + v + "</span>";
                }else{
                    return v;
                }
            }
        });
        colsArr.push({
            name : 'qualitedNum',
            width : 80,
            display : '合格数量'
        });
    // }
    colsArr.push({
        name : 'remark',
        width : 250,
        display : '备注'
    });

    return colsArr;
}