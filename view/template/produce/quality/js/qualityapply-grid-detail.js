var show_page = function() {
	$("#qualityapplyGrid").yxgrid("reload");
};
$(function() {
	var detailStatusArr=$("#detailStatusArr").val();
	var relDocType = $("#relDocType").val();
	// ------- 菜单按钮 ------- //
	var confirmBtn = {
		text : '接收确认',
		icon : 'add',
		action: function(row,rows) {
			if(row){
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('物料 【'+ rows[i].productName +'】 处理状态不为 【未处理】，不能进行此操作');
						return false;
					}
					newIdArr.push(rows[i].id);
				}
				if('undefined'==typeof(newIdArr)){
					alert('发生未知错误，请刷新该窗口');
					show_page();
					return false;
				}

				if(confirm("确定要对这"+newIdArr.length+"条接收确认？")){
					idStr = newIdArr.toString();
					$.ajax({
						url:'?model=produce_quality_qualityapplyitem&action=ajaxReceive&ids=' + idStr,
						type:'get',
						dataType:'json',
						success:function(msg){
							if(msg==1){
								show_page();
								alert('操作成功');
							}else
								alert('操作失败');
						},
						error:function(){
							alert('服务器连接失败');
						}
					});
				}
			}else{
				alert('请选择一条物料');
			}
		}
	};// 确认接收
	var submitQualityBtn = {
		text : '质检',
		icon : 'add',
		showMenuFn : function(row) {
			if(row.status != '3'){
				return true;
			}
			return false;
		},
		action: function(row,rows) {
			if(row){
				var tempObjType = '';
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('物料 【'+ rows[i].productName +'】 处理状态不为 【未处理】，不能进行此操作');
						return false;
					}

					if(rows[i].receiveStatus !== '1'){
						alert('请先对物料 【'+ rows[i].productName +'】进行接收确认后再进行质检操作');
						return false;
					}

					if(tempObjType!="" && rows[i].objType != tempObjType){
						alert('不同源单类型的质检申请明细不能生成一个质检任务');
						return false;
					}

					newIdArr.push(rows[i].id);
				}
				if('undefined'==typeof(newIdArr)){
					alert('发生未知错误，请刷新该窗口');
					show_page();
					return false;
				}
				if(''==newIdArr){
					alert('请选择一条物料');
					show_page();
					return false;
				}
				idStr = newIdArr.toString();

				var chkPass = true;// 添加检验标记 PMS2386
				if(row.relDocType == 'ZJSQDLBF'){// 呆料报废需要做特殊检验
					chkPass = false;
					var chkResult = chkIsAllRelativeSelected(idStr);
					chkPass = chkResult;
				}
				if(chkPass){
					if(confirm("确定要对这"+(idStr.split(',')).length+"条质检？")){
						$.ajax({
							url:'?model=produce_quality_qualitytask&action=ajaxTask&itemId='+ idStr+'&docType='+row.relDocType,
							type:'get',
							dataType:'json',
							success:function(msg){
								if(msg==1){
									show_page();
									alert('操作成功');
								}else if(msg==0){
									alert('操作失败');
								}else if(row.relDocType == 'ZJSQDLBF'){
									alert('该申请单【'+msg+'】出错,操作终止。');
								}
							},
							error:function(){
								alert('服务器连接失败');
							}
						});
					}
				}
			}else{
				alert('请选择一条物料');
			}
		}
	};// 提交质检
	var confirmPassBtn = {
		text : '质检放行',
		icon : 'edit',
		showMenuFn : function(row) {
			if(row.status != '3' && row.relDocType != 'ZJSQDLBF'){
				return true;
			}
			return false;
		},
		action: function(row,rows,idArr ) {
			if(row){
				var relDocTypeArr = [];//初始化源单类型数组
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('物料 【'+ rows[i].productName +'】 处理状态不为 【未处理】，不能进行此操作');
						return false;
					}
					if(rows[i].receiveStatus !== '1'){
						alert('请先对物料 【'+ rows[i].productName +'】进行接收确认后再进行质检放行操作');
						return false;
					}
					//构建源单类型数组
					if(relDocTypeArr.indexOf(rows[i].relDocType) == -1){//数组中不存在该元素，则返回-1
						relDocTypeArr.push(rows[i].relDocType);
					}
					newIdArr.push(rows[i].id);
				}
				relDocTypeStr = relDocTypeArr.toString();
				idStr = newIdArr.toString();
				showThickboxWin("?model=produce_quality_qualityapplyitem&action=toConfirmPass&id="
					+ idStr + "&relDocType=" + relDocTypeStr
					+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}else{
				alert('请选择一条物料');
			}
		}
	};// 质检放行
	var backBtn = {
		text : '打回',
		icon : 'delete',
		action: function(row,rows) {
			if(row){
				var newIdArr = [];
				for (var i = 0; i < rows.length; i++) {
					if(rows[i].detailStatus !== '4'){
						alert('物料 【'+ rows[i].productName +'】 处理状态不为 【未处理】，不能进行此操作');
						return false;
					}
					newIdArr.push(rows[i].id);
				}
				if('undefined'==typeof(newIdArr)){
					alert('发生未知错误，请刷新该窗口');
					show_page();
					return false;
				}
				var alertMsg = "确定要对这"+newIdArr.length+"条打回？";
				if(row.relDocType == 'ZJSQDLBF'){// 呆料报废需要做特殊检验
					alertMsg = "确定对这"+newIdArr.length+"条申请,及其相同原单的其他质检申请记录一同打回?";
				}
				if(confirm(alertMsg)){
					idStr = newIdArr.toString();
					$.ajax({
						url:'?model=produce_quality_qualityapplyitem&action=ajaxBack&ids=' + idStr,
						type:'get',
						dataType:'json',
						success:function(msg){
							if(msg==1){
								show_page();
								alert('操作成功');
							}else
								alert('操作失败');
						},
						error:function(){
							alert('服务器连接失败');
						}
					});
				}
			}else{
				alert('请选择一条物料');
			}
		}
	};// 打回
	var buttonMenu = [];
	buttonMenu.push(confirmBtn);
	buttonMenu.push(submitQualityBtn);
	if(relDocType != 'ZJSQDLBF'){
		buttonMenu.push(confirmPassBtn);
	}
	buttonMenu.push(backBtn);
	// ------- ./菜单按钮 ------- //

	if(detailStatusArr!=4){
		buttonMenu=[];
	}
	$("#qualityapplyGrid").yxgrid({
		model : 'produce_quality_qualityapply',
		action : 'jsonDetail',
		title : '质检申请设备明细',
		param : {
			detailStatusArr:detailStatusArr,
			relDocTypeArr:relDocType
		},
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		// 列信息
		colModel : [{
            name : 'id',
            display : 'id',
            sortable : true,
            hide : true
        }, {
            name : 'relDocType',
            display : '源单类型',
            sortable : true,
            width : 90,
            datacode : 'ZJSQDYD'
        }, {
            name : 'relDocId',
            display : '源单id',
            sortable : true,
            hide : true
        }, {
            name : 'relDocCode',
            display : '源单编号',
            sortable : true,
            width : 110
        }, {
            name : 'docCode',
            display : '申请单号',
            sortable : true,
            process : function(v,row){
                if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
                    return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                }else{
                    return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toQualityView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                }
            },
            width : 80
        }, {
            name : 'contractId',
            display : '合同id',
            sortable : true,
            hide : true
        }, {
            name : 'contractCode',
            display : '合同编号',
            sortable : true,
//            process: function (v, row) {
//            	if(row.contractTypeCode == 'HTLX-XSHT' || row.contractTypeCode == 'HTLX-FWHT' ||
//            		row.contractTypeCode == 'HTLX-ZLHT' || row.contractTypeCode == 'HTLX-YFHT'){
//                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
//                    + row.contractId
//                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
//                    + "<font color = '#4169E1'>"
//                    + v
//                    + "</font>"
//                    + '</a>';
//            	}
//            	return v;
//           },
           width : 100
        }, {
            name : 'customerId',
            display : '客户id',
            sortable : true,
            hide : true
        }, {
            name : 'customerName',
            display : '客户名称',
            sortable : true,
            width : 100
        }, {
            name : 'status',
            display : '单据状态',
            sortable : true,
            width : 70,
            process : function(v){
                switch(v){
                    case '0' : return "待执行";
                    case '1' : return "部分执行";
                    case '2' : return "执行中";
                    case '3' : return "已关闭";
                    default : return '<span class="red">非法状态</span>';
                }
            },
            hide : true
        }, {
            name : 'supplierName',
            display : '供应商',
            sortable : true,
            width : 130,
            hide : true
        }, {
            name : 'productCode',
            display : '物料编号',
            width : 80
        }, {
            name : 'productName',
            display : '物料名称',
            width : 120
        }, {
            name : 'pattern',
            display : '型号'
        }, {
            name : 'unitName',
            display : '单位',
            width : 50
        }, {
            display : '批次号',
            name : 'batchNum'
        }, {
            display : '序列号',
            name : 'serialName'
        }, {
            name : 'checkTypeName',
            display : '质检方式',
            width : 60
        }, {
            name : 'qualityNum',
            display : '报检数量',
            width : 60
        }, {
            name : 'assignNum',
            display : '已下达数量',
            width : 60
        }, {
            name : 'complatedNum',
            display : '质检完成数',
            width : 65
        },{
            name : 'standardNum',
            display : '合格数量',
            width : 60
        }, {
            name : 'receiveStatus',
            display : '接收确认',
            sortable : true,
            process : function(v) {
                switch(v){
                	case '0' : return '否';
                    case '1' : return '是';
                }
            },
            width : 70
        }, {
			name : 'receiveId',
			display : '接收确认人id',
			sortable : true,
			hide : true
		}, {
			name : 'receiveName',
			display : '接收确认人',
			sortable : true,
			width : 90
		}, {
			name : 'receiveTime',
			display : '接收确认时间',
			sortable : true,
			width : 130
		},{
            name : 'detailStatus',
            display : '处理结果',
            width : 60,
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
            name : 'applyUserName',
            display : '申请人',
            width : 90,
            sortable : true
        }, {
            name : 'applyUserCode',
            display : '申请人Code',
            hide : true,
            sortable : true
        }, {
            name : 'createTime',
            display : '申请时间',
            width : 130,
            hide : true,
            sortable : true
        }, {
            name : 'closeUserName',
            display : '关闭人',
            hide : true,
            sortable : true
        }, {
            name : 'closeUserId',
            display : '关闭人id',
            hide : true,
            sortable : true
        }, {
            name : 'closeTime',
            display : '关闭时间',
            hide : true,
            width : 130,
            sortable : true
        }, {
            name : 'dealUserName',
            display : '处理人',
            width : 80
        },{
            name : 'dealTime',
            display : '处理时间',
            width : 130
        },{
            name : 'passReason',
            display : '放行原因',
            width : 130
        }],
		buttonsEx : buttonMenu,
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toQualityView',
			formWidth : 900,
			formHeight : 500,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
					showThickboxWin("?model=produce_quality_qualityapply&action=toView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					showThickboxWin("?model=produce_quality_qualityapply&action=toQualityView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
		},
		searchitems : [{
			display : "单据编号",
			name : 'docCodeSearch'
		},{
			display : "源单编号",
			name : 'relDocCodeSearch'
		},{
			display : "申请人",
			name : 'createNameSearch'
		},{
			display : "供应商",
			name : 'supplierNameSearch'
		},{
			display : "物料名称",
			name : 'iProductNameSearch'
		},{
			display : "物料编号",
			name : 'iProductCodeSearch'
		}],
		sortname : 'i.dealTime'
	});
});

// 检查选中的ID里面是否存在相同源单没有被选中的物料
function chkIsAllRelativeSelected(ids){
	var chkResult = $.ajax({
		url:'index1.php?model=produce_quality_qualityapplyitem&action=chkIsAllRelativeSelected&ids='+ids,
		type : "POST",
		async : false
	}).responseText;
	if(chkResult != 'false'){
		var chkResultArr = eval("(" + chkResult + ")");
		var failDocCode = '';
		$.each(chkResultArr,function(){
			var obj = $(this)[0];
			if(parseInt(obj.totalItmesNum) != parseInt(obj['itemIds']['length'])){
				failDocCode += obj.docCode+',';
			}
		});
		if(failDocCode != ''){
			failDocCode = failDocCode.substring(0,failDocCode.length-1);
			alert("以下申请单【"+failDocCode+"】还有物料未选中。");
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}