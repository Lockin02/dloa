$(document).ready(function() {
	var show = '';
    var relDocType = $("#relDocType").val();
    if(relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH"){
		show = 'hidden';
	}

	var qualitedNumLabel = (relDocType == "ZJSQDLBF")? "报废数" : "合格数";
	var produceNumLabel = (relDocType == "ZJSQDLBF")? "不报废数" : "不合格数";
	var sequenceTd = (relDocType == "ZJSQDLBF")? [{
		name: 'serialnoName',
		tclass: 'readOnlyTxtShort',
		readonly: true,
		display:"不报废序列号",
		width: 500

	},{
		name: 'serialnoId',
		display: 'serialnoId',
		type: 'hidden'
	},{
		name: 'serialnoChkedNum',
		display: 'serialnoChkedNum',
		type: 'hidden'
	}] : "";

	var colModelArr = [{
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
		width : 80
	}, {
		name : 'productName',
		display : '物料名称',
		width : 130
	}, {
		name : 'pattern',
		display : '规格型号',
		width : 100
	}, {
		name : 'supplierName',
		display : '供应商',
		width : 130
	}, {
		name : 'supportNum',
		display : '报检数量',
		width : 70
	}, {
		name : 'thisCheckNum',
		display : '本次质检数量',
		width : 80
	}, {
		name: 'samplingNum',
		display: '抽检数量',
		width : 70
	}, {
		name : 'unitName',
		display : '单位',
		width : 70
	}, {
		name : 'supportTime',
		display : '报检时间',
		width : 130
	}, {
		name : 'relDocCode',
		display : '源单号',
		width : 90
	}, {
		name : 'purchaserName',
		display : '申请人',
		width : 90
	}, {
		name : 'priority',
		display : '紧急程度',
		datacode : 'ZJJJCD',
		width : 70
	}, {
		name : 'qualitedNum',
		display : qualitedNumLabel,
		width : 70
	}, {
		name : 'produceNum',
		display : produceNumLabel,
		process : function(v) {
			if(v*1 != 0){
				return "<a href='#' style='color:red;'>" + v + "</a>";
			}else{
				return v;
			}
		},
		width : 70,
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
		}
	}];

	if(sequenceTd != ""){
		$.merge(colModelArr,sequenceTd);
	}

	$.merge(colModelArr,[{
		name : 'passNum',
		display : '正常接收数量',
		width : 90,
		type : show
	}, {
		name : 'receiveNum',
		display : '让步接收数量',
		width : 90,
		type : show,
		process : function(v){
			if(v*1 != 0){
				return "<span class='red'>" + v + "</span>";
			}else{
				return v;
			}
		}
	}, {
		name : 'backNum',
		display : '退回数量',
		width : 70,
		type : show,
		process : function(v){
			if(v*1 != 0){
				return "<span class='red'>" + v + "</span>";
			}else{
				return v;
			}
		}
	}, {
		name : 'completionTime',
		display : '入库时间',
		width : 70
	}, {
		name : 'remark',
		display : '备注',
		width : 130
	}]);

	//质检物料
	$("#ereportequitem").yxeditgrid({
		objName : 'qualityereport[ereportequitem]',
		title : '物料信息及检验结果',
		type : 'view',
		url : "?model=produce_quality_qualityereportequitem&action=listJson",
		param : { "mainId" : $("#id").val() },
		colModel : colModelArr
	});
	
    //源单类型为生产检验的
    if(relDocType == 'ZJSQYDSC'){
    	//显示文档类型
    	$("#ducumentTd").show().next("td").show();
    	$("#fileTd").attr("colspan", 1);
    	//隐藏质检方案，质检标准
    	$("#qualityPlanName").parents("tr:first").hide();
    	//显示计划单及合同编号
    	$("#relCodeTr").show();
    	//显示备注
    	$("#remark").parents("tr:first").show();
    	//显示指引文档
    	$("#guideDocTr").show();
		if($("#guideDocId").val() != ''){
			var idArr = $("#guideDocId").val().split(',');
			var nameArr = $("#guideDocName").val().split(',');
			var html = '';
			for(var i = 0; i < idArr.length; i++){
				html += '<div class="upload"><a title="点击下载" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
				+ '">' + nameArr[i] + '</a></div>';
			}
			$("#guideDocTr .upload").html(html);
		}
    }else{
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
    }
});

/**
 *
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