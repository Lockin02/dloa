var printer = {
	lodop : null, // ��ӡ���printer.lodop����
	version : "6.0", // ��ӡ����汾��Ĭ��5.0.4.3

	/** ��װ�ļ�����·�� */
	contextPath : "",
	lodopFile : "js/Lodop/install_lodop.exe", // ��Ҫ���jsp��ȡ��������·��

	/** css���������ã�����initִ��ǰ�������ã� */
	printCssFile : "css/yxstyle2.css", // ��Ҫ���jsp��ȡ��������·����Ĭ��Ϊyxstyle.css
	printCssId : "printCss10000", // ��IdΪ�̶������ڶ����ӡ��css��Id
	printDivId : "printDiv10000", // ��IdΪ�̶������ڶ����ӡ��������Div��Id

	/** ��ӡ���Զ��� */
	printObjId : "", // ��ӡ�����Id

	/** ��ӡ�����������ã�����analyseTableǰ�������ã� */
	printTitle : "", // ���屨�����
	tableTitleRows : 1, // ����������е�������Ĭ��1�б�ͷ��
	notPrintTopRows : 0, // ����ӡ����ϲ��������������ҳ����
	notPrintBottomRows : 0, // ����ӡ����²��������������ҳ����
	notPrintLeftCols : 0, // ����ӡ����󲿷������������ѡ��
	notPrintRightCols : 0, // ����ӡ����Ҳ�����������������У�

	/** ��ӡ������ã�����previewǰ�������ã� */
	pageWidth : "900px",// document.body.clientWidth * 0.85, // Ĭ��ֽ�ſ��
	pageHeight : "500px",// document.body.clientHeight * 0.8, // Ĭ��ֽ�Ÿ߶�
	pageTop : 30, // Ĭ��ֽ���ϱ߾�
	pageLeft : 30, // Ĭ��ֽ����߾�
	pageType : "A4", // ֽ������
	pageOrient : 2, // ��ӡ����1:��(��)���ӡ���̶�ֽ��;2:�����ӡ���̶�ֽ��;3:��(��)���ӡ����ȹ̶����߶Ȱ���ӡ���ݵĸ߶�����Ӧ��
	isDirectPrint : false, // �Ƿ�ֱ�Ӵ�ӡ
	// -----------------------------------------------------------------------------------------------------

	/*
	 * �Դ�ӡ��Lodop���г�ʼ��
	 */
	initLodop : function() {
		this.lodop = document.getElementById("lodop");
		if (undefined == this.lodop || null == this.lodop) {
			this.lodop = document.createElement("OBJECT");
			this.lodop.classid = "clsid:2105C259-1E0C-4534-8141-A753534CB4CA";
			this.lodop.id = "lodop";
		}
	},

	/*
	 * ��ʼ����ӡcss
	 */
	initPrintCss : function() {
		var style = document.getElementById(this.printCssId);
		if (undefined == style || null == style) {
			var cssUrl = printer.printCssFile;
			style = document.createElement("link");
			style.id = this.printCssId;
			style.name = this.printCssId;
			style.rel = "stylesheet";
			style.type = "text/css";
			// ��Ҫ��ϵͳ�ṩ�����ı���
			var filePath = this.printCssFile;
			if ("" != this.contextPath) {
				filePath = this.contextPath + filePath;
			}
			style.href = filePath;
			document.getElementsByTagName("HEAD")[0].appendChild(style);
		}
	},

	/*
	 * ȡ��ҳ���������õ�css�ļ�
	 */
	getPrintCssText : function() {
		var text = "<style>";
		for (var i = 0; i < document.styleSheets.length; i++) {
			var css = document.styleSheets[i];
			if (this.printCssId == css.id) {
				text += css.cssText;
			}
		}
		text += "</style>";
		return text;
	},


	/*
	 * ���lodop����汾���Ƿ��Ѱ�װ
	 */
	checkLodop : function() {
		var oldVersion = this.lodop.Version;
		var filePath = this.lodopFile;
		if ("" != this.contextPath) {
			filePath = this.contextPath + filePath;
		}

		var btnPrint = document.getElementById("btnPrint");
		if (oldVersion == null) {
			var tip = "<br><div style='margin-top:10px;margin-bottom:10px;'><font color='#fb7e04'><b>��ܰ��ʾ��</b>�����Ȱ�װ��ӡ�ؼ������������޷���ӡ��</font><a href='"
					+ filePath + "'>[�ؼ�����]</a></div>";
			document.write(tip);
			if (btnPrint) {
				btnPrint.style.display = "none";
			}
		}
		if (oldVersion < this.version) {
			var tip = "<br><div style='margin-top:10px;margin-bottom:10px;'><font color='#fb7e04'><b>��ܰ��ʾ��</b>������������ӡ�ؼ������������޷���ӡ��</font><a href='"
					+ filePath + "'>[�ؼ�����]</a></div>";
			document.write(tip);
			if (btnPrint) {
				btnPrint.style.display = "none";
			}
		}
	},

	/*
	 * ���css��lodopʵ���������а汾���
	 */
	init : function() {
		// ���ϵͳҳ���д���_CONTEXTPATH���������������·����������
		if ('undefined' != typeof(_CONTEXTPATH)) {
			this.contextPath = _CONTEXTPATH;
		}

		this.initLodop(); // ��ʼ��lodop
		this.initPrintCss(); // ��ʼ��css
		this.checkLodop(); // �԰汾���м��
	}
}
// ----------------------------------------------------------------------------------------------------------

printer.init(); // ��ӡ�ؼ������ʼ��
function prn_image(Top,Left,Width,Height,strHtmlContent){
	printer.preFn=(function(Top,Left,Width,Height,strHtmlContent){
		return function(){
			printer.lodop.ADD_PRINT_IMAGE(Top,Left,Width,Height,"<img src='images/sjdl.jpg'/>");
		}
	})(Top,Left,Width,Height,strHtmlContent);
}
/**
 * Ԥ����ӡ
 *
 * @param {}
 *            title ��ӡ������
 * @param {}
 *            objId ��ӡ����id
 */
function prn_preview(objId, title) {
	prn(objId, title);
	if(printer.preFn){
		printer.preFn();
	}
	return printer.lodop.PREVIEW();
};

/*
 *
 */
/**
 * ֱ�Ӵ�ӡ(�����ˮӡ ������ʹ��)
 * @param {}
 *            title ��ӡ������
 * @param {}
 *            objId ��ӡ����id
 */
function prn_print(objId, title) {
	prn(objId, title);
	if(printer.preFn){
		printer.preFn();
	}
	printer.lodop.PRINTA();
};
/**
 * ҳ�����
 * @param {}
 *            title ��ӡ������
 * @param {}
 *            objId ��ӡ����id
 */
function prn_design(objId, title) {
	prn(objId, title);
	if(printer.preFn){
		printer.preFn();
	}
	printer.lodop.PRINT_DESIGN();
};

/**
 * ȡ��ҳ����Ҫ��ӡ�Ĳ���
 *
 * @param {}
 *            title ��ӡ������
 * @param {}
 *            objId ��ӡ����id
 */
function prn(objId,title) {
	if (!objId)
		objId = 'mainTable';
	if (!title)
		title = '����ӡ';
	var strBodyStyle = printer.getPrintCssText();
	thisStr = document.getElementById(objId).outerHTML;
	thisStr=thisStr.replace(/<s?img[^>]*>/gi,'');
	var strFormHtml = strBodyStyle + "<body>"
			+ thisStr + "</body>";
	printer.lodop.PRINT_INIT(title);
	//printer.lodop.ADD_PRINT_TEXT(1000, 10, 50, 50, "��#ҳ/��&ҳ");// ��ҳ����������ʾ��1ҳ/��1ҳ
	if(objId=="employmentPrint"){
		printer.lodop.SET_PRINT_STYLE("FontSize", 12);
		printer.lodop.SET_PRINT_STYLE("Bold", 0);
		printer.lodop.ADD_PRINT_TEXT(1048, 500, 300, 30,"ӦƸ��ǩ�֣�       ���ڣ�      ");
		printer.lodop.SET_PRINT_STYLEA(1, "ItemType", 1);
		printer.lodop.SET_PRINT_STYLEA(1, "HOrient", 1);
	}
	else{
		printer.lodop.SET_PRINT_STYLE("FontSize", 18);
		printer.lodop.SET_PRINT_STYLE("Bold", 0);
		printer.lodop.SET_PRINT_STYLEA(1, "ItemType", 2);
		printer.lodop.SET_PRINT_STYLEA(1, "HOrient", 2);
	}
//	printer.lodop.SET_PRINT_PAGESIZE(2,1880,2800,"");
	printer.lodop.ADD_PRINT_HTM(20, 10, 768, 1024, strFormHtml);
};

/** �ṩ�޸�ҳ���ӡ */

/**
 * �ṩͼ�δ�ӡ function doVmlPrint(divId){ printer.printObjId = divId;
 * printer.analyseVml(); printer.previewVml(); }
 */

var $print = printer;
