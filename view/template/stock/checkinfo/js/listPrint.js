var printCssId = "printStyle"; // //该Id为固定，用于定义打印用css的Id
var printCssFile = "js/jquery/style/yxgrid.css";//css文件路径
var contextPath="";
/*
 * 预览
 */
function prn_preview() {
	CreateOneFormPage();
	LODOP.PREVIEW();
};
/*
 * 直接打印(会出现水印 不建议使用)
 */
function prn_print() {
	CreateOneFormPage();
	LODOP.PRINTA();
};
/*
 * 页面调整
 */
function prn_design() {
	CreateOneFormPage();
	LODOP.PRINT_DESIGN();
};
/*
 * 取得页面所要打印的部分
 */
function CreateOneFormPage() {
	var strBodyStyle = this.getPrintCssText();
	var strFormHtml = strBodyStyle + "<body>"
			+ document.getElementById("hDivBox").innerHTML+ document.getElementById("bDiv").innerHTML + "</body>";
	LODOP.PRINT_INIT("盘点入库单列表打印");
	LODOP.SET_PRINT_STYLE("FontSize", 18);
	LODOP.SET_PRINT_STYLE("Bold", 0);
	LODOP.SET_PRINT_STYLEA(1, "ItemType", 2);
	LODOP.SET_PRINT_STYLEA(1, "HOrient", 2);
	LODOP.SET_PRINT_PAGESIZE(2,1880,2800,"");
	LODOP.ADD_PRINT_HTM(20, 10, 768, 1024, strFormHtml);
};
/*
 * 取得页面所有配置的css文件
 */
function getPrintCssText() {
	initPrintCss();
	var text = "<style>";
	for (var i = 0; i < document.styleSheets.length; i++) {
		var css = document.styleSheets(i);
		if (this.printCssId == css.id) {
			text += css.cssText;
		}
	}
	text += "</style>";
	return text;
};
/*
 * 初始化打印css
 */
function initPrintCss() {
	var style = document.getElementById(this.printCssId);
	if (undefined == style || null == style) {
		var cssUrl = this.printCssFile;
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
};
