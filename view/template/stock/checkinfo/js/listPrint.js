var printCssId = "printStyle"; // //��IdΪ�̶������ڶ����ӡ��css��Id
var printCssFile = "js/jquery/style/yxgrid.css";//css�ļ�·��
var contextPath="";
/*
 * Ԥ��
 */
function prn_preview() {
	CreateOneFormPage();
	LODOP.PREVIEW();
};
/*
 * ֱ�Ӵ�ӡ(�����ˮӡ ������ʹ��)
 */
function prn_print() {
	CreateOneFormPage();
	LODOP.PRINTA();
};
/*
 * ҳ�����
 */
function prn_design() {
	CreateOneFormPage();
	LODOP.PRINT_DESIGN();
};
/*
 * ȡ��ҳ����Ҫ��ӡ�Ĳ���
 */
function CreateOneFormPage() {
	var strBodyStyle = this.getPrintCssText();
	var strFormHtml = strBodyStyle + "<body>"
			+ document.getElementById("hDivBox").innerHTML+ document.getElementById("bDiv").innerHTML + "</body>";
	LODOP.PRINT_INIT("�̵���ⵥ�б��ӡ");
	LODOP.SET_PRINT_STYLE("FontSize", 18);
	LODOP.SET_PRINT_STYLE("Bold", 0);
	LODOP.SET_PRINT_STYLEA(1, "ItemType", 2);
	LODOP.SET_PRINT_STYLEA(1, "HOrient", 2);
	LODOP.SET_PRINT_PAGESIZE(2,1880,2800,"");
	LODOP.ADD_PRINT_HTM(20, 10, 768, 1024, strFormHtml);
};
/*
 * ȡ��ҳ���������õ�css�ļ�
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
 * ��ʼ����ӡcss
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
		// ��Ҫ��ϵͳ�ṩ�����ı���
		var filePath = this.printCssFile;
		if ("" != this.contextPath) {
			filePath = this.contextPath + filePath;
		}
		style.href = filePath;
		document.getElementsByTagName("HEAD")[0].appendChild(style);
	}
};
