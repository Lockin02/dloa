
var locale = new Object;

// locale.getLanguage(): Called to work out what language to use.
locale.getLanguage = function()
{
	return locale.language ? locale.language : navigator.userLanguage;
}

// locale.getString(): Called to return the language variant of a @code string.
// this routin will fall back to en-us is no language variant is found.  If no
// english version exists, the code is returned.
locale.getString = function(str, lang)
{
	// If not supplied, pick up the language to use
	if (!lang) lang = locale.getLanguage();

	// Get references to required languages 
	if (!locale[lang])
	{
		lang = locale["zh-cn"];
	}
	else
	{
		lang = locale[lang];
	}

	// Find the end of the text code
	var i = str.indexOf('@{');
	while (i != -1)
	{
		// Find the closing } 
		var j = str.indexOf('}', i+1);

		// Extrace the language code
		var code = str.substr(i+2,j-i-2);

		// Return the language version of the text
		if (lang[code]) {
			str = str.substr(0,i) + lang[code] + str.substr(i+j+1);
		}
		// Find the next code if any
		i = str.indexOf('@{', i+1);
	}

	// Untranslated
	return str;
}

// locale.setLocale(): Called once the editor has loaded to replace all language
// codes in alt, title and innerText with thier language counterparts.
locale.setLocale = function()
{
	// Work out which language to apply
	var lang = locale.getLanguage();

	for (var i = 0; i < document.all.length; i++)
	{
		var el = document.all(i);
		if (el.alt && el.alt.indexOf('@{') != -1) {
			el.alt = locale.getString(el.alt, lang);
		}
		if (el.title && el.title.indexOf('@{') != -1) {
			el.title = locale.getString(el.title, lang);
		}
		if (el.src && el.src.indexOf('@{') != -1) {
			el.src = locale.getString(el.src, lang);
		}
		if (!el.children.length && el.innerText && el.innerText.indexOf('@{') != -1) {
			el.innerText = locale.getString(el.innerText, lang);
		}
	}
}

window.attachEvent("onload", locale.setLocale);

////////////////////////////////////////////////////////////////////////////////
//
// 简体中文 (中国)
//
////////////////////////////////////////////////////////////////////////////////

var o = locale["zh-cn"] = new Object;

	// Icon Titles (alt="")
	o["PostTopic"]			= "发布主题";
	o["Cut"]				= "剪切";
	o["Copy"]				= "复制";
	o["Paste"]				= "粘贴";
	o["SpellCheck"]			= "语法拼写检查";
	o["SelectAll"]			= "全部选择";
	o["RemoveFormatting"]	= "删除格式";
	o["InsertLink"]			= "插入超链接";
	o["RemoveLink"]			= "删除超链接";
	o["InsertImage"]		= "插入图片";
	o["InsertTable"]		= "插入表格";
	o["EditTable"]			= "编辑表格";
	o["InsertLine"]			= "插入水平线";
	o["About"]				= "关于";
	o["Bold"]				= "加粗";
	o["Italic"]				= "倾斜";
	o["Underline"]			= "下划线";
	o["Strikethrough"]		= "删除线";
	o["AlignLeft"]			= "左对齐";
	o["Center"]				= "居中";
	o["AlignRight"]			= "右对齐";
	o["AlignBlock"]			= "两端对齐";
	o["NumberedList"]		= "编号";
	o["BulettedList"]		= "项目符号";
	o["DecreaseIndent"]		= "减少缩进量";
	o["IncreaseIndent"]		= "增加缩进量";
	o["HistoryBack"]		= "撤销";
	o["HistoryForward"]		= "恢复";
	o["TextColor"]			= "字体颜色";
	o["BackgroundColor"]	= "背景颜色";

	o["RemoveColspan"]		= "拆分单元格(成多列)";
	o["RemoveRowspan"]		= "拆分单元格(成多行)";
	o["IncreaseColspan"]	= "合并单元格(与右侧)";
	o["IncreaseRowspan"]	= "合并单元格(与下方)";
	o["AddColumn"]			= "插入列(在右侧)";
	o["AddRow"]				= "插入行(在下方)";
	o["RemoveColumn"]		= "删除列";
	o["RemoveRow"]			= "删除行";

	// Label Text
	o["Style"]				= "样式";
	o["Font"]				= "字体";
	o["Size"]				= "字号";
	o["Source"]				= "源文件";

	// Titles
	o["SourceTitle"]		= "点击这里查看源文件";

	// Image Sources
	o["icon_post"]			= "images/icon_post.gif";
	o["hdr_tables"]			= "images/hdr_tables.gif";
	
////////////////////////////////////////////////////////////////////////////////
//
// English (American & British)
//
////////////////////////////////////////////////////////////////////////////////

var o = locale["en-us"] = locale["en-gb"] = new Object;

	// Icon Titles (alt="")
	o["PostTopic"]			= "Post Topic";
	o["Cut"]				= "Cut";
	o["Copy"]				= "Copy";
	o["Paste"]				= "Paste";
	o["SpellCheck"]			= "Spell Check";
	o["SelectAll"]			= "Select All";
	o["RemoveFormatting"]	= "Remove Formatting";
	o["InsertLink"]			= "Insert Link";
	o["RemoveLink"]			= "Remove Link";
	o["InsertImage"]		= "Insert Image";
	o["InsertTable"]		= "Insert Table";
	o["EditTable"]			= "Edit Table";
	o["InsertLine"]			= "Insert Horizontal Line";
	o["InsertSmily"]		= "Insert Smily 8-)";
	o["InsertCharacter"]	= "Insert special character";
	o["About"]				= "About Richtext Editor";
	o["Bold"]				= "Bold";
	o["Italic"]				= "Italic";
	o["Underline"]			= "Underline";
	o["Strikethrough"]		= "Strikethrough";
	o["AlignLeft"]			= "Align Left";
	o["Center"]				= "Center";
	o["AlignRight"]			= "Align Right";
	o["AlignBlock"]			= "Align Block";
	o["NumberedList"]		= "Numbered List";
	o["BulettedList"]		= "Buletted List";
	o["DecreaseIndent"]		= "Decrease Indent";
	o["IncreaseIndent"]		= "Increase Indent";
	o["HistoryBack"]		= "History back";
	o["HistoryForward"]		= "History forward";
	o["TextColor"]			= "Text Color";
	o["BackgroundColor"]	= "Background Color";

	o["RemoveColspan"]		= "Remove Colspan";
	o["RemoveRowspan"]		= "Remove Rowspan";
	o["IncreaseColspan"]	= "Increase Colspan";
	o["IncreaseRowspan"]	= "Increase Rowspan";
	o["AddColumn"]			= "Add Column";
	o["AddRow"]				= "Add Row";
	o["RemoveColumn"]		= "Remove Column";
	o["RemoveRow"]			= "Remove Row";

	// Label Text
	o["Style"]				= "Style";
	o["Font"]				= "Font";
	o["Size"]				= "Size";
	o["Source"]				= "Source";

	// Titles
	o["SourceTitle"]		= "Click here to toggle between WYSIWYG and Source mode.";

	// Image Sources
	o["icon_post"]			= "images/icon_post.gif";
	o["hdr_tables"]			= "images/hdr_tables.gif";

////////////////////////////////////////////////////////////////////////////////
//
// Norwegian Bokm錶
//
////////////////////////////////////////////////////////////////////////////////

o = locale["no"] = new Object;

	// Icon Titles (alt="")
	o["PostTopic"]			= "Send";
	o["Cut"]				= "Klipp";
	o["Copy"]				= "Kopier";
	o["Paste"]				= "Lim";
	o["SpellCheck"]			= "Stavekontroll";
	o["SelectAll"]			= "Marker alt";
	o["RemoveFormatting"]	= "Fjern formatering";
	o["InsertLink"]			= "Sett inn link";
	o["RemoveLink"]			= "Fjern link";
	o["InsertImage"]		= "Sett inn bilde";
	o["InsertTable"]		= "Sett inn tabell";
	o["EditTable"]			= "Endre tabell";
	o["InsertLine"]			= "Sett inn horisontal linje";
	o["InsertSmily"]		= "Sett inn smily 8-)";
	o["InsertCharacter"]	= "Sett inn spesialtegn";
	o["About"]				= "Om Richtext Editor";
	o["Bold"]				= "Fet";
	o["Italic"]				= "Kursiv";
	o["Underline"]			= "Understrekning";
	o["Strikethrough"]		= "Gjennomstrekning";
	o["AlignLeft"]			= "Venstrejustering";
	o["Center"]				= "Sentrering";
	o["AlignRight"]			= "H鴜rejustering";
	o["AlignBlock"]			= "Blokkjustering";
	o["NumberedList"]		= "Nummerert liste";
	o["BulettedList"]		= "Punktliste";
	o["DecreaseIndent"]		= "Mink innrykksverdi";
	o["IncreaseIndent"]		= "豮 innrykksverdi";
	o["HistoryBack"]		= "Historie bakover";
	o["HistoryForward"]		= "Historie forover";
	o["TextColor"]			= "Tekstfarge";
	o["BackgroundColor"]	= "Bakgrunnsfarge";

	// Label Text
	o["Style"]				= "Stil";
	o["Font"]				= "Type";
	o["Size"]				= "St鴕relse";
	o["Source"]				= "Kilde";

	// Titles
	o["SourceTitle"]		= "Klikk her for � bytte mellom WYSIWYG og kilde modus.";

	// Image Sources
	o["icon_post"]			= "images/lang/no.icon_post.gif";

////////////////////////////////////////////////////////////////////////////////
//
// German
//
////////////////////////////////////////////////////////////////////////////////

var o = locale["de"] = new Object;

	// Icon Titles (alt="")
	o["PostTopic"]                  = "Speichern";
	o["Cut"]                        = "Ausschneiden";
	o["Copy"]                       = "Kopieren";
	o["Paste"]                      = "Einf黦en";
	o["SpellCheck"]                 = "Rechschreibpr黤ung";
	o["SelectAll"]                  = "Alles markieren";
	o["RemoveFormatting"]           = "Formatierung entfernen";
	o["InsertLink"]                 = "Link einf黦en";
	o["RemoveLink"]                 = "Link entfernen";
	o["InsertImage"]                = "Bild einf黦en";
	o["InsertTable"]                = "Tabelle einf黦en";
	o["EditTable"]                  = "Tabelle bearbeiten";
	o["InsertLine"]                 = "Horizontale Linie einf黦en";
	o["InsertSmily"]                = "Smily 8-) einf黦en";
	o["InsertCharacter"]            = "Sonderzeichen einf黦en";
	o["About"]                      = "躡er Richtext Editor";
	o["Bold"]                       = "Fett";
	o["Italic"]                     = "Kursiv";
	o["Underline"]                  = "Unterstrichen";
	o["Strikethrough"]              = "Durchgestrichen";
	o["AlignLeft"]                  = "Linksb黱dig";
	o["Center"]                     = "Zentriert";
	o["AlignRight"]                 = "Rechtsb黱dig";
	o["AlignBlock"]                 = "Blocksatz";
	o["NumberedList"]               = "Nummerierung";
	o["BulettedList"]               = "Aufz鋒lungszeichen";
	o["DecreaseIndent"]             = "Einzug verkleinern";
	o["IncreaseIndent"]             = "Einzug vergr鲞ern";
	o["HistoryBack"]                = "R點kg鋘gig";
	o["HistoryForward"]             = "Wiederherstellen";
	o["TextColor"]                  = "Zeichenfarbe";
	o["BackgroundColor"]            = "Hintergrundfarbe";

	// Label Text
	o["Style"]                      = "Absatzformat";
	o["Font"]                       = "Schriftart";
	o["Size"]                       = "Gr鲞e";
	o["Source"]                     = "Quelltext";

	// Titles
	o["SourceTitle"]                = "Hier klicken, um zwischen WYSIWYG- und Quelltext-Modus umzuschalten.";

	// Image Sources
	o["icon_post"]                  = "images/lang/de.icon_post.gif";

////////////////////////////////////////////////////////////////////////////////
//
// Fran鏰is
//
////////////////////////////////////////////////////////////////////////////////

var o = locale["fr"] = new Object;

	// Icon Titles (alt="")
	o["PostTopic"]			= "Poster le sujet";
	o["Cut"]				= "Couper";
	o["Copy"]				= "Copier";
	o["Paste"]				= "Coller";
	o["Find Text"]			= "Rechercher";
	o["SpellCheck"]			= "V閞ifier l'orthographe";
	o["SelectAll"]			= "S閘ectionner tout";
	o["RemoveFormatting"]	= "Supprimer le formattage";
	o["InsertLink"]			= "Ins閞er un lien";
	o["RemoveLink"]			= "Supprimer un lien";
	o["InsertImage"]		= "Ins閞er une image";
	o["InsertTable"]		= "Ins閞er un tableau";
	o["EditTable"]			= "Editer le tableau";
	o["InsertLine"]			= "Ins閞er une ligne horizontale";
	o["InsertSmily"]		= "Ins閞er un Smiley 8-)";
	o["InsertCharacter"]	= "Ins閞er des caract鑢es sp閏iaux";
	o["About"]				= "A propos de Richtext Editor";
	o["Bold"]				= "Gras";
	o["Italic"]				= "Italique";
	o["Underline"]			= "Soulign";
	o["Strikethrough"]		= "Barr";
	o["AlignLeft"]			= "Align� � gauche";
	o["Center"]				= "Centr";
	o["AlignRight"]			= "Align� � droite";
	o["AlignBlock"]			= "Justifi";
	o["NumberedList"]		= "Liste num閞ot閑";
	o["BulettedList"]		= "Liste � puces";
	o["DecreaseIndent"]		= "Diminuer le retrait";
	o["IncreaseIndent"]		= "Augmenter le retrait";
	o["HistoryBack"]		= "Annuler";
	o["HistoryForward"]		= "R閠ablir";
	o["TextColor"]			= "Couleur du texte";
	o["BackgroundColor"]	= "Couleur de l'arri鑢e plan";

	o["RemoveColspan"]		= "Fractionner la cellule";
	o["RemoveRowspan"]		= "Fusionner la cellule";
	o["IncreaseColspan"]	= "Augmenter l'閠endue de la colonne";
	o["IncreaseRowspan"]	= "Augmenter l'閠endue de la ligne";
	o["AddColumn"]			= "Ajouter une colonne";
	o["AddRow"]				= "Ajouter une ligne";
	o["RemoveColumn"]		= "Supprimer une colonne";
	o["RemoveRow"]			= "Supprimer une ligne";

	// Label Text
	o["Style"]				= "Style";
	o["Font"]				= "Police";
	o["Size"]				= "Taille";
	o["Source"]				= "Code source";

	// Titles
	o["SourceTitle"]		= "Cliquez ici pour basculer entre Aper鐄 et mode Source.";

	// Image Sources
	o["icon_post"]			= "images/icon_post.gif";
	
////////////////////////////////////////////////////////////////////////////////