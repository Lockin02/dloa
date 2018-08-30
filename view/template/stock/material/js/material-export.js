$(function () {
	//Ĭ����ʾ�ı�ͷ
	var default_columns = [
            { field: 'stock_code', title: '���ϱ��', align:'center', sortable:true, width:80 },
            { field: 'stock_name', title: '��������', align:'center', width:80 },
            { field: 'stock_model', title:'�����ͺ�', align:'center', width:80 },
            { field: 'stock_packaging', title:'��װ', align:'center', width:70 },
            { field: 'stock_factory', title:'����', align:'center', width:70 },
            { field: 'stock_total', align:'center', title:'ʵ������', width:70 },
            { field: 'stock_loss_total', title:'������(�����)', align:'center',width:100 },
            { field: 'outStockNum', align:'center', title:'������', width:60 },
            { field: 'realOutNum', align:'center', title:'�˿���', width:60, editor:"numberbox" },
            { field: 'useTotal', align:'center', title:'������', width:60 },
            { field: 'shortage', title:'δ����', align:'center', width:60, styler:function(value){if(value>0){return"background: #fcc"}} },
            { field: 'mustOutNum', align:'center', title:'Ӧ����', width:60 }
        ];
	
	//�ȸ���һ��Ĭ�ϵ�, ���ɱ���ʱ���õ���columns���ɱ�ͷ
	var columns = default_columns.slice(0, default_columns.length);
	
	//���ɱ�ͷѡ��checkbox
	createCheckBox(columns);
	
	//����checkbox����
	function createCheckBox(column) {		
		/********************�����Զ����ֶ� beign*************************/
		var tpl = $("#columnExportTemplate").html(),
			html = template(column, tpl);
		
		$("#columns_form").html(html);
		/********************�����Զ����ֶ� end************************/
		
		
		//jsģ���滻
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