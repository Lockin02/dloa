/**
 * 报废资产出售js
 */

$(function() {
			$("#sellTable").yxeditgrid({
				isAdd : false,
				objName : 'sell[item]',
				url:'?model=asset_assetcard_assetcard&action=getScrapListJson&assetIdArr=' + $("#assetId").val(),
				event : {
					removeRow : function(t, rowNum, rowData) {
						countAmount();
					},
					reloadData : function(){
						countAmount();
					}
				},
				colModel : [{
					display : '卡片编号',
					name : 'assetCode',
					readonly : true
				}, {
					display : '资产名称',
					name : 'assetName',
					readonly : true
				}, {
					display : '资产Id',
					name : 'assetId',
					process : function($input,row){
							var assetId = row.id;
							$input.val(assetId);
						},
					type : 'hidden',
					readonly : true
				}, {
					display:'英文名称',
					name : 'englishName',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'购置日期',
					name : 'buyDate',
					type:'date',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'规格型号',
					name : 'spec',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'附属设备',
					name : 'equip',
					type:'statictext',
					process : function(e, data) {
						if (data) {
							var $href = $("<a>详细</a>");
							$href.attr("href", "#");
							$href.click(function() {
								window.open('?model=asset_assetcard_equip&action=toPage&assetCode='
												+ data.assetCode);
							})
							return $href;
						} else {
							return '<a href="#" >详细</a>';
						}
					}
				},{
					display:'已经使用期间数',//已使用期间数alreadyDay
					name : 'alreadyDay',
					tclass : 'txtshort',
					readonly:true
				},{
					display:'售出部门',//不带
					name : 'deptName',
					tclass : 'txtshort'
				}, {
					display:'售出前用途',//用途useType
					name : 'beforeUse',
					tclass : 'txtshort',
					readonly:true
				}, {
					display:'已折旧金额',//累计折旧depreciation
					name : 'depreciation',
					tclass : 'txtshort',
					type : 'money',
					readonly:true
				},{
					display:'残余价值',//预计净残值salvage
					name : 'salvage',
					tclass : 'txtshort',
					type : 'money',
					readonly:true
				}
//				, {
//					display:'月折旧额',//不带，计算出来的。
//					name : 'monthDepr',
//					tclass : 'txtshort'
//				}
				, {
					display:'备注',//不带，可改
					name : 'remark',
					tclass : 'txt'
				}]
			});
//选择人员组件
	$("#seller").yxselect_user({
		hiddenId : 'sellerId',
		isGetDept:[true,"deptId","deptName"]
	});

 /**
	 * 验证信息
	 */
	validate( {"billNo" : {
			required : true
		},"seller" : {
			required : true
		},"donationDate" : {
			required : true,
			custom : [ 'date' ]
//		},"sellNum" : {
//            required : true,
//            custom : [ 'onlyNumber' ]
        },"sellAmount" : {
            required : true,
            custom : [ 'money' ]
        }
	});
});
// 根据从表的残值动态计算应付总金额
function countAmount() {
	// 获取当前的行数即卡片的资产数
	var curRowNum = $("#sellTable").yxeditgrid("getCurShowRowNum");
	$("#sellNum").val(curRowNum);

	var rowAmountVa = 0;
	var cmps = $("#sellTable").yxeditgrid("getCmpByCol", "salvage");
	cmps.each(function() {
				rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
			});
	$("#sellAmount").val(rowAmountVa);
	$("#sellAmount_v").val(moneyFormat2(rowAmountVa));
	return false;
}
/*
 * 审核确认
 */
function confirmAudit() {
	if (confirm("你确定要提交审核吗?")) {
		$("#form1").attr("action",
				"?model=asset_disposal_sell&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}


