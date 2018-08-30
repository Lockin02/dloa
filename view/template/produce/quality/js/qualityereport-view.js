$(document).ready(function() {
	var show = '';
    var relDocType = $("#relDocType").val();
    if(relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH"){
		show = 'hidden';
	}

	var qualitedNumLabel = (relDocType == "ZJSQDLBF")? "������" : "�ϸ���";
	var produceNumLabel = (relDocType == "ZJSQDLBF")? "��������" : "���ϸ���";
	var sequenceTd = (relDocType == "ZJSQDLBF")? [{
		name: 'serialnoName',
		tclass: 'readOnlyTxtShort',
		readonly: true,
		display:"���������к�",
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
		display : '���ϱ���',
		width : 80
	}, {
		name : 'productName',
		display : '��������',
		width : 130
	}, {
		name : 'pattern',
		display : '����ͺ�',
		width : 100
	}, {
		name : 'supplierName',
		display : '��Ӧ��',
		width : 130
	}, {
		name : 'supportNum',
		display : '��������',
		width : 70
	}, {
		name : 'thisCheckNum',
		display : '�����ʼ�����',
		width : 80
	}, {
		name: 'samplingNum',
		display: '�������',
		width : 70
	}, {
		name : 'unitName',
		display : '��λ',
		width : 70
	}, {
		name : 'supportTime',
		display : '����ʱ��',
		width : 130
	}, {
		name : 'relDocCode',
		display : 'Դ����',
		width : 90
	}, {
		name : 'purchaserName',
		display : '������',
		width : 90
	}, {
		name : 'priority',
		display : '�����̶�',
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
		display : '������������',
		width : 90,
		type : show
	}, {
		name : 'receiveNum',
		display : '�ò���������',
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
		display : '�˻�����',
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
		display : '���ʱ��',
		width : 70
	}, {
		name : 'remark',
		display : '��ע',
		width : 130
	}]);

	//�ʼ�����
	$("#ereportequitem").yxeditgrid({
		objName : 'qualityereport[ereportequitem]',
		title : '������Ϣ��������',
		type : 'view',
		url : "?model=produce_quality_qualityereportequitem&action=listJson",
		param : { "mainId" : $("#id").val() },
		colModel : colModelArr
	});
	
    //Դ������Ϊ���������
    if(relDocType == 'ZJSQYDSC'){
    	//��ʾ�ĵ�����
    	$("#ducumentTd").show().next("td").show();
    	$("#fileTd").attr("colspan", 1);
    	//�����ʼ췽�����ʼ��׼
    	$("#qualityPlanName").parents("tr:first").hide();
    	//��ʾ�ƻ�������ͬ���
    	$("#relCodeTr").show();
    	//��ʾ��ע
    	$("#remark").parents("tr:first").show();
    	//��ʾָ���ĵ�
    	$("#guideDocTr").show();
		if($("#guideDocId").val() != ''){
			var idArr = $("#guideDocId").val().split(',');
			var nameArr = $("#guideDocName").val().split(',');
			var html = '';
			for(var i = 0; i < idArr.length; i++){
				html += '<div class="upload"><a title="�������" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
				+ '">' + nameArr[i] + '</a></div>';
			}
			$("#guideDocTr .upload").html(html);
		}
    }else{
    	//�ʼ�����
        var itemCols = getItemTable(relDocType);
    	$("#itemTable").yxeditgrid({
    		objName : 'qualityereport[items]',
    		url : '?model=produce_quality_qualityereportitem&action=listJson',
    		type : 'view',
    		param : {
    			mainId : $("#id").val()
    		},
    		title : '�ʼ�����',
    		colModel : itemCols,
            event : {
                'reloadData' : function(){
                    //����ǻ������߽��ù黹����ʼ챨�棬�����ɲ��ϸ���Ϣ��
                    if(relDocType == "ZJSQYDGH" || relDocType == "ZJSQYDHH"){
                        //��ʼ�����ϸ񲿷�
                        showDetail();
                    }
                }
            }
    	});
    }
});

/**
 *
 * ��ʾ���ϸ���ϸ�б�
 */
function showDetail(){
    //��ȡ������Ŀ
    var dimensionNameArr = $("#itemTable").yxeditgrid("getCmpByCol", "dimensionName");
    var baseTitle = initFailureItemGrid(dimensionNameArr);
    $("#failureItem").yxeditgrid({
        url : '?model=produce_quality_failureitem&action=listJson',
        param : {
            mainId : $("#id").val()
        },
        type : 'view',
        title : '���ϸ�������Ϣ',
        colModel : baseTitle
    });
}

//��ʼ�����ϸ��б�����
function initFailureItemGrid(dimensionNameArr){
    //������Ŀ��ͷ
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
        display : '���ϱ���',
        width : 80
    }, {
        name : 'productName',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '��������'
    }, {
        name : 'pattern',
        tclass : 'readOnlyTxtMiddle',
        readonly : true,
        display : '����ͺ�'
    }, {
        name : 'unitName',
        tclass : 'readOnlyTxtShort',
        readonly : true,
        display : '��λ'
    }, {
        name : 'serialNo',
        display : '���к�',
        validation : {
            required : true
        }
    }];

    //�����������
    dimensionNameArr.each(function(i){
        var resultNum = i + 1;
        baseTitle.push({
            name : 'result' + resultNum,
            display : $(this).val(),
            datacode : 'ZJJDJG',
            width : 80
        });
    });

    //�����������ݡ���ע����Ϣ
    baseTitle.push({
        name : 'resultName',
        display : '��������'
    });
    baseTitle.push({
        name : 'levelName',
        display : '��������'
    });
    baseTitle.push({
        name : 'remark',
        display : '��ע',
        tclass : 'txt',
        width : 120
    });
    return baseTitle;
}

//��ȡ�ʼ����ݱ�ͷ
function getItemTable(relDocType){
    var colsArr = [{
        name : 'dimensionName',
        tclass : 'txt',
        display : '������Ŀ',
        validation : {
            required : true
        }
    }, {
        name : 'examTypeName',
        tclass : 'txt',
        display : '���鷽ʽ����'
    }];
    //����ǽ��ù黹
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
            display : '�ϸ�����'
        });
    // }
    colsArr.push({
        name : 'remark',
        width : 250,
        display : '��ע'
    });

    return colsArr;
}