$(function () {
	//默认显示的表头
	var default_columns = [
            { field: 'stock_code', title: '物料编号', align:'center', sortable:true, width:80 },
            { field: 'stock_name', title: '物料名称', align:'center', width:80 },
            { field: 'stock_model', title:'物料型号', align:'center', width:80 },
            { field: 'stock_packaging', title:'封装', align:'center', width:70 },
            { field: 'stock_factory', title:'厂商', align:'center', width:70 },
            { field: 'stock_total', align:'center', title:'实际需求', width:70 },
            { field: 'stock_loss_total', title:'总需求(含损耗)', align:'center',width:100 },
            { field: 'outStockNum', align:'center', title:'发料数', width:60 },
            { field: 'realOutNum', align:'center', title:'退库数', width:60, editor:"numberbox" },
            { field: 'useTotal', align:'center', title:'总用量', width:60 },
            { field: 'shortage', title:'未发数', align:'center', width:60, styler:function(value){if(value>0){return"background: #fcc"}} },
            { field: 'mustOutNum', align:'center', title:'应退数', width:60 }
        ];
	
	//先复制一份默认的, 生成表格的时候用的是columns生成表头
	var columns = default_columns.slice(0, default_columns.length);
	
	//生成表头选择checkbox
	createCheckBox(columns);
	
	//生成checkbox方法
	function createCheckBox(column) {		
		/********************导出自定义字段 beign*************************/
		var tpl = $("#columnExportTemplate").html(),
			html = template(column, tpl);
		
		$("#columns_form").html(html);
		/********************导出自定义字段 end************************/
		
		
		//js模版替换
		function template(datas, tpl) {
			var reg = new RegExp("\{(.*?)\}", "g"),
				replace = [],
				match,
				result = "";
			while(match = reg.exec(tpl)) {
				replace.push([match[0], match[1]]);
			}

			var dataLength = datas.length;
			var replaceLength = replace.length;
			for(var i=0; i<dataLength; i++) {
				datas[i].index = i;
				var tplReplace = tpl;
				for(var j=0; j<replaceLength; j++) {
					tplReplace = tplReplace.replace(replace[j][0], datas[i][replace[j][1]]);
				}
				result += tplReplace;
			}

			return result;
		}
	}

});