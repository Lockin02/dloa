var printer = {
	lodop : null, // 打印组件printer.lodop对象
	version : "6.0", // 打印组件版本：默认5.0.4.3

	/** 安装文件下载路径 */
	contextPath : "",
	lodopFile : "js/Lodop/install_lodop.exe", // 需要配合jsp来取得上下文路径

	/** css、容器设置（需在init执行前进行设置） */
	printCssFile : "css/yxstyle2.css", // 需要配合jsp来取得上下文路径，默认为yxstyle.css
	printCssId : "printCss10000", // 该Id为固定，用于定义打印用css的Id
	printDivId : "printDiv10000", // 该Id为固定，用于定义打印内容容器Div的Id

	/** 打印属性定义 */
	printObjId : "", // 打印对象的Id

	/** 打印内容区域设置（需在analyseTable前进行设置） */
	printTitle : "", // 定义报表标题
	tableTitleRows : 1, // 定义表格标题列的行数（默认1行表头）
	notPrintTopRows : 0, // 不打印表格上部分行数（例如分页栏）
	notPrintBottomRows : 0, // 不打印表格下部分行数（例如分页栏）
	notPrintLeftCols : 0, // 不打印表格左部分列数（例如多选）
	notPrintRightCols : 0, // 不打印表格右部分列数（例如操作列）

	/** 打印风格设置（需在preview前进行设置） */
	pageWidth : "900px",// document.body.clientWidth * 0.85, // 默认纸张宽度
	pageHeight : "500px",// document.body.clientHeight * 0.8, // 默认纸张高度
	pageTop : 30, // 默认纸张上边距
	pageLeft : 30, // 默认纸张左边距
	pageType : "A4", // 纸张类型
	pageOrient : 2, // 打印方向（1:纵(正)向打印，固定纸张;2:横向打印，固定纸张;3:纵(正)向打印，宽度固定，高度按打印内容的高度自适应）
	isDirectPrint : false, // 是否直接打印
	// -----------------------------------------------------------------------------------------------------

	/*
	 * 对打印机Lodop进行初始化
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
	 * 初始化打印css
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
			// 需要由系统提供上下文变量
			var filePath = this.printCssFile;
			if ("" != this.contextPath) {
				filePath = this.contextPath + filePath;
			}
			style.href = filePath;
			document.getElementsByTagName("HEAD")[0].appendChild(style);
		}
	},

	/*
	 * 取得页面所有配置的css文件
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
	 * 检测lodop组件版本及是否已安装
	 */
	checkLodop : function() {
		var oldVersion = this.lodop.Version;
		var filePath = this.lodopFile;
		if ("" != this.contextPath) {
			filePath = this.contextPath + filePath;
		}

		var btnPrint = document.getElementById("btnPrint");
		if (oldVersion == null) {
			var tip = "<br><div style='margin-top:10px;margin-bottom:10px;'><font color='#fb7e04'><b>温馨提示：</b>请您先安装打印控件，否则您将无法打印。</font><a href='"
					+ filePath + "'>[控件下载]</a></div>";
			document.write(tip);
			if (btnPrint) {
				btnPrint.style.display = "none";
			}
		}
		if (oldVersion < this.version) {
			var tip = "<br><div style='margin-top:10px;margin-bottom:10px;'><font color='#fb7e04'><b>温馨提示：</b>请您先升级打印控件，否则您将无法打印。</font><a href='"
					+ filePath + "'>[控件下载]</a></div>";
			document.write(tip);
			if (btnPrint) {
				btnPrint.style.display = "none";
			}
		}
	},

	/*
	 * 组件css、lodop实例化并进行版本检测
	 */
	init : function() {
		// 如果系统页面中存在_CONTEXTPATH变量，则对上下文路径进行配置
		if ('undefined' != typeof(_CONTEXTPATH)) {
			this.contextPath = _CONTEXTPATH;
		}

		this.initLodop(); // 初始化lodop
		this.initPrintCss(); // 初始化css
		this.checkLodop(); // 对版本进行检查
	}
}
// ----------------------------------------------------------------------------------------------------------

printer.init(); // 打印控件对象初始化
function prn_image(Top,Left,Width,Height,strHtmlContent){
	printer.preFn=(function(Top,Left,Width,Height,strHtmlContent){
		return function(){
			printer.lodop.ADD_PRINT_IMAGE(Top,Left,Width,Height,"<img src='images/sjdl.jpg'/>");
		}
	})(Top,Left,Width,Height,strHtmlContent);
}
/**
 * 预览打印
 *
 * @param {}
 *            title 打印表格标题
 * @param {}
 *            objId 打印对象id
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
 * 直接打印(会出现水印 不建议使用)
 * @param {}
 *            title 打印表格标题
 * @param {}
 *            objId 打印对象id
 */
function prn_print(objId, title) {
	prn(objId, title);
	if(printer.preFn){
		printer.preFn();
	}
	printer.lodop.PRINTA();
};
/**
 * 页面调整
 * @param {}
 *            title 打印表格标题
 * @param {}
 *            objId 打印对象id
 */
function prn_design(objId, title) {
	prn(objId, title);
	if(printer.preFn){
		printer.preFn();
	}
	printer.lodop.PRINT_DESIGN();
};

/**
 * 取得页面所要打印的部分
 *
 * @param {}
 *            title 打印表格标题
 * @param {}
 *            objId 打印对象id
 */
function prn(objId,title) {
	if (!objId)
		objId = 'mainTable';
	if (!title)
		title = '表格打印';
	var strBodyStyle = printer.getPrintCssText();
	thisStr = document.getElementById(objId).outerHTML;
	thisStr=thisStr.replace(/<s?img[^>]*>/gi,'');
	var strFormHtml = strBodyStyle + "<body>"
			+ thisStr + "</body>";
	printer.lodop.PRINT_INIT(title);
	//printer.lodop.ADD_PRINT_TEXT(1000, 10, 50, 50, "第#页/共&页");// 在页面最下面显示第1页/共1页
	if(objId=="employmentPrint"){
		printer.lodop.SET_PRINT_STYLE("FontSize", 12);
		printer.lodop.SET_PRINT_STYLE("Bold", 0);
		printer.lodop.ADD_PRINT_TEXT(1048, 500, 300, 30,"应聘者签字：       日期：      ");
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

/** 提供修改页面打印 */

/**
 * 提供图形打印 function doVmlPrint(divId){ printer.printObjId = divId;
 * printer.analyseVml(); printer.previewVml(); }
 */

var $print = printer;
