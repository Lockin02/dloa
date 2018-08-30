$(document).ready(function() {
	$.formValidator.initConfig({
		formid: "form1",
		//autotip: true,
		onerror: function(msg) {
			//alert(msg);
		},
		onsuccess : function(msg){
			for(var i = 1 ;i <= $("#invnumber").val()*1 ; i++ ){
				if( $("#productId" + i).length == 0 ) continue;
				if($("#productId" + i).val() == ""){
					alert('不能存在物料为空的项');
					return false;
				}
				if($("#amount" + i).val() == "" || $("#amount" + i).val()*1 == 0){
					alert('物料必须填写对应的金额且金额不能为0');
					return false;
				}

				if( accAdd($("#amount" + i).val(),$("#assessment" + i).val(),2)*1 != $("#allCount" + i).val()*1 ){
					alert('记录价税合计不等于合计金额加上税额');
					return false;
				}

			}
			if($("#formDate").val() == ""){
				$("#formDate").val($("#payDate").val()) ;
			}

			//防止重复提交验证
			$("input[type='submit']").attr('disabled',true);
		}
	});

	/** 验证供应商名称 * */
	$("#supplierName").formValidator({
		onshow: "请输入供应商名称",
		onfocus: "供应商名称至少2个字符，最多50个字符",
		oncorrect: "您输入的名称有效"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "名称两边不能有空符号"
		},
		onerror: "您输入的名称不合法，请重新输入"
	});

	/** 部门验证 * */
	$("#departments").formValidator({
		onshow: "请选择部门",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		onerror: "请选择部门"
	});
	
	/** 验证供归属公司 * */
	$("#businessBelongName").formValidator({
		onshow: "请输入归属公司",
		onfocus: "归属公司名称至少2个字符，最多50个字符",
		oncorrect: "您输入的名称有效"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "名称两边不能有空符号"
		},
		onerror: "您输入的名称不合法，请重新输入"
	});

	/** 验证业务员 * */
	$("#salesman").formValidator({
		onshow: "请选择业务员",
		oncorrect: "OK"
	}).inputValidator({
		min: 2,
		max: 50,
		onerror: "请选择业务员"
	});

	/** 验证发票号码 * */
	$("#objNo").formValidator({
		onshow: "请输入发票号码",
		onfocus: "发票号码至少2个字符",
		oncorrect: "您输入的号码有效"
	}).inputValidator({
		min: 2,
		max: 300,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "号码两边不能为空"
		},
		onerror: "您输入的号码不合法，请重新输入"
	}).ajaxValidator({
		url : "index1.php?model=finance_invpurchase_invpurchase&action=ajaxCheck",
		success : function(data){
			if(data == "1"){
				return false;
			}else{
				return true;
			}
		},
		buttons: $("#button"),
		error: function(XMLHttpRequest, textStatus, errorThrown){alert("服务器没有返回数据，可能服务器忙，请重试"+errorThrown);},
		onerror : "该发票号码已存在",
		onwait : "正在对发票好像进行合法性校验，请稍候..."
	}).defaultPassed();

	/** 验证采购方式 * */
	$("#pruType").formValidator({
		onshow: "请输入采购方式",
		onfocus: "采购方式至少2个字符，最多50个字符",
		oncorrect: "您输入的采购方式有效"
	}).inputValidator({
		min: 2,
		max: 50,
		empty: {
			leftempty: false,
			rightempty: false,
			emptyerror: "输入内容两边不能为空"
		},
		onerror: "您输入的内容不合法，请重新输入"
	});

	/** 验证付款日期 * */
	$("#payDate").formValidator({
		onshow: "请选择付款日期",
		onfocus: "请选择日期",
		oncorrect: "你输入的日期合法"
	}).inputValidator({
		min: "1900-01-01",
		max: "2100-01-01",
		type: "date",
		onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});

	/** 验证付款日期 * */
	$("#formDate").formValidator({
		onshow: "请选择单据日期",
		onfocus: "请选择日期",
		oncorrect: "你输入的日期合法"
	}).inputValidator({
		min: "1900-01-01",
		max: "2100-01-01",
		type: "date",
		onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	});
});