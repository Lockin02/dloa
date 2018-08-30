$(document).ready(function() {
	$("#itemTable").yxeditgrid({
		objName : 'qualitytask[items]',
		url : '?model=produce_quality_qualitytaskitem&action=editItemJson',
		type : 'view',
		param : {
			mainId : $("#id").val()
		},
		title : '质检任务明细',
		colModel : [{
			name : 'id',
			display : 'id',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '物料编号'
		}, {
			name : 'productName',
			display : '物料名称'
		}, {
			name : 'pattern',
			tclass : 'txt',
			tclass : 'readOnlyTxtItem',
			display : '型号'
		}, {
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			display : '单位'
		}, {
			name : 'checkTypeName',
			display : '质检方式'
		}, {
			name : 'assignNum',
			display : '下达数量',
			process : function(v,row){
				return Number(v);
			}
		}, {
			name : 'checkedNum',
			display : '已质检数量',
			process : function(v,row){
				return (row.realCheckNum >= 0 && row.realCheckNum != '')? row.realCheckNum : Number(v);
			}
		}, {
			name : 'standardNum',
			display : '合格数量',
			process : function(v,row){
				return (row.checkStatus == "")? "-" : Number(v);
			}
		}, {
            name : 'unStandardNum',
            display : '不合格数量',
            process : function(v,row){
            	var produceNum = 0;
				if(row.realCheckNum >= 0 && row.realCheckNum != ''){
					produceNum = row.realCheckNum-row.standardNum;
				}else{
					produceNum = row.checkedNum-row.standardNum;
				}
                return (row.checkStatus == "")? "-" : produceNum;
            }
        },{
            name : 'qualitedRate',
            display : '合格率',
            process : function(v,row){
                if(v!=""){
                    var str = (row.checkStatus == "")? "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toItempage&type=task&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>-</a>" :
					"<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toItempage&type=task&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
					return str;
                }else{
                    return v;
                }
            }
        },{
			name : 'checkStatus',
			display : '检验状态',
			process : function(v) {
				switch(v){
					case "YJY" : return "已检验"; break;
					case "" : return "未检验"; break;
//					case "YBCBG" : return "已保存报告"; break;
//					case "BH" : return "驳回"; break;
					case "BFJY" : return "部分检验"; break;
					default : return "非法状态";
				}
			}
		}]
	})
})