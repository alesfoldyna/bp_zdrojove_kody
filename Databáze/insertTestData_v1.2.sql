﻿/*
 tabulka znacka
*/

INSERT INTO public.znacka(nazev) VALUES ('Alfa Romeo');
INSERT INTO public.znacka(nazev) VALUES ('Aston Martin');
INSERT INTO public.znacka(nazev) VALUES ('Audi');
INSERT INTO public.znacka(nazev) VALUES ('BMW');
INSERT INTO public.znacka(nazev) VALUES ('Chevrolet');
INSERT INTO public.znacka(nazev) VALUES ('Ford');
INSERT INTO public.znacka(nazev) VALUES ('Hyundai');
INSERT INTO public.znacka(nazev) VALUES ('Mercedes-Benz');
INSERT INTO public.znacka(nazev) VALUES ('Peugeot');
INSERT INTO public.znacka(nazev) VALUES ('Škoda');
INSERT INTO public.znacka(nazev) VALUES ('Toyota');
INSERT INTO public.znacka(nazev) VALUES ('Volkswagen');
INSERT INTO public.znacka(nazev) VALUES ('Volvo');

/*
tabulka vybava ***********************************************************************
*/

/*1*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('pohon 4x4', 'Technické');
/*2*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('tažné zařízení', 'Technické');
/*3*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('litá kola', 'Technické');
/*4*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('zámek řadící páky', 'Technické');
/*5*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('imobilizér', 'Technické');
/*6*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('alarm', 'Technické');
/*7*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('GPS zabezpečení', 'Technické');
/*8*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('denní svícení', 'Technické');
/*9*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('natáčecí světlomety', 'Technické');
/*10*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('sportovní podvozek', 'Technické');
/*11*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('regulace tuhosti podvozku', 'Technické');
/*12*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('regulace výšky podvozku', 'Technické');
/*13*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('pro tělesně postižené', 'Technické');
/*14*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('automatická převodovka', 'Technické');
/*15*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('klimatizace', 'Technické');
/*16*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('střešní okno', 'Technické');
	
/*17*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('aut. aktivace výstražných světlometů', 'Bezpečnostní');
/*18*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('noční vidění', 'Bezpečnostní');
/*19*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('ochranné rámy', 'Bezpečnostní');
/*20*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('senzor opotřebení brzdových destiček', 'Bezpečnostní');
/*21*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('nouzové brzdění (PEBS)', 'Bezpečnostní');
	
/*22*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('parkovací kamera', 'Asistent');
/*23*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('Asistent stability přívěsu (TSA)', 'Asistent');
/*24*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('360° monitorovací systém (AVM)', 'Asistent');
/*25*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('hlídání mrtvého úhlu', 'Asistent');
/*26*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('hlídání jízdního pruhu', 'Asistent');
/*27*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('sledování únavy řidiče', 'Asistent');
/*28*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('el. dovírání dveří', 'Asistent');
/*29*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('brzdový Asistent', 'Asistent');
/*30*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('aut. zabrzdění v kopci', 'Asistent');
/*31*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('regulace rychlosti při jízdě ze svahu', 'Asistent');
/*32*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('start-stop systém', 'Asistent');
/*33*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('senzor stěračů', 'Asistent');
/*34*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('senzor světel', 'Asistent');
/*35*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('BlueTooth hands free', 'Asistent');
/*36*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('parkovací kamera', 'Asistent');

/*37*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('nezávislé topení', 'Pohodlí');
/*38*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('tempomat', 'Pohodlí');
/*39*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('sedadla s funkcí masáže - přední', 'Pohodlí');
/*40*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('sedadla s funkcí masáže - zadn', 'Pohodlí');
/*41*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('odvětrávaná sedadla', 'Pohodlí');
/*42*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('aut. stavitelný volant při nástupu', 'Pohodlí');
/*43*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('vyhřívaný volant', 'Pohodlí');
/*44*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('multifunkční volant', 'Pohodlí');
/*45*/	INSERT INTO public.vybava(nazev, kategorie) VALUES ('vyhřívaná sedadla', 'Pohodlí');


/*

tabulka model ************************************************************************************

*/


INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (1, 'Brera', 2005, 2010, '{3,4,5,6,10,15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (1, 'Giulia', 2016, null, '{1,3,4,5,6,10,15,22,23,24,25,26,27,28,29,30,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (1, 'Giulietta', 2010, null, '{2,5,6,8,10,11,12,13,14,38,44}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (1, 'Brera', 2003, 2010, '{3,4,5,6,10,15}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (2, 'DB7', 1994, 2004, '{2,8,14,25,16}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (2, 'DB9', 2004, 2016, '{1,2,3,4,5,18,19,20,25,26,35,36,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (2, 'V8 Vantage', 1977, null, '{18,25,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (2, 'Vanquish', 2001, 2007, '{1,2,5,9,10}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A1', 2010, null, '{15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A2', 1999, 2005, '{}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A3', 1996, null, '{3,2}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A4', 1994, null, '{8,9,10,11}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A5', 2007, null, '{1,2,3,6,8}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A6', 1994, null, '{3,5,6,7,8,9,10,11,44}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A7', 2010, null, '{2,4,8,10,12,16,18}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'A8', 1994, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'Q3', 2011, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'Q5', 2008, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (3, 'Q7', 2005, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (4, 'Řada 4', 2013, null, '{2,3,4,5,6}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (4, 'Řada 5', 1972, null, '{18,20,25,11,31}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (4, 'Řada 6', 2003, null, '{1,2,3,4,5,6}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (5, 'Captiva', 2006, 2018, '{15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (5, 'Corvette', 1984, null, '{10,15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (5, 'Spark', 1998, null, '{3,4,5,6,10,15}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (6, 'Escort', 1968, 2004, '{3,4,5}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (6, 'Fiesta', 1976, null, '{3,4,5,6}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (6, 'Mondeo', 1993, null, '{3,4,5,44,45}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (7, 'i20', 2008, null, '{38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (7, 'i30', 2007, null, '{10,15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (7, 'i40', 2011, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,15,38,44,45}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (8, 'C Class', 1993, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (8, 'E Class', 1993, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (8, 'S Class', 1954, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (9, '307', 2001, 2008, '{1,2,3,4,5,6}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (9, '308', 2007, 2014, '{11,21,23}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (9, '5008', 2009, null, '{18,19,20,21,22}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (10, 'Fabia', 1999, null, '{19,20,21,22,23,24,25,26}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (10, 'Octavia', 1996, null, '{14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (10, 'Superb', 2001, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (11, 'Avensis', 1997, 2018, '{10,15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (11, 'Land Cruiser', 1951, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (11, 'Yaris', 1999, null, '{3,4,5,6,10}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (12, 'Golf', 1974, null, '{3,4,5,6,10}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (12, 'Passat', 1974, null, '{3,4,5,6,10,15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (12, 'Touareg', 2002, null, '{3,4,5,6,10,15,38,44,45}');

INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (13, 'V40', 1995, 2004, '{3,4,5,6,10,15}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (13, 'V60', 2010, null, '{3,4,5,6,10,15,38,44,45}');
INSERT INTO public.model(id_znacka, nazev, vyrobeno_od, vyrobeno_do, id_vybava) VALUES (13, 'V90', 2016, null, '{1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45}');


/*

tabulka podminky*******************************************************************************

*/

INSERT INTO public."vseobecnePodminky"(zneni, pdf_path, platnost_od, platnost_do, platna) VALUES ('<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-1250">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 15">
<meta name=Originator content="Microsoft Word 15">
<link rel=File-List href="text%20druhe%20podmínky_soubory/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>Aleš Foldyna</o:Author>
  <o:Template>Normal</o:Template>
  <o:LastAuthor>Aleš Foldyna</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>14</o:TotalTime>
  <o:Created>2019-03-14T18:51:00Z</o:Created>
  <o:LastSaved>2019-03-14T19:05:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>138</o:Words>
  <o:Characters>816</o:Characters>
  <o:Lines>6</o:Lines>
  <o:Paragraphs>1</o:Paragraphs>
  <o:CharactersWithSpaces>953</o:CharactersWithSpaces>
  <o:Version>16.00</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<link rel=themeData href="text%20druhe%20podmínky_soubory/themedata.thmx">
<link rel=colorSchemeMapping
href="text%20druhe%20podmínky_soubory/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:HyphenationZone>21</w:HyphenationZone>
  <w:PunctuationKerning/>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:DoNotPromoteQF/>
  <w:LidThemeOther>CS</w:LidThemeOther>
  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
   <w:SplitPgBreakAndParaMark/>
   <w:EnableOpenTypeKerning/>
   <w:DontFlipMirrorIndents/>
   <w:OverrideTableStyleHps/>
  </w:Compatibility>
  <m:mathPr>
   <m:mathFont m:val="Cambria Math"/>
   <m:brkBin m:val="before"/>
   <m:brkBinSub m:val="&#45;-"/>
   <m:smallFrac m:val="off"/>
   <m:dispDef/>
   <m:lMargin m:val="0"/>
   <m:rMargin m:val="0"/>
   <m:defJc m:val="centerGroup"/>
   <m:wrapIndent m:val="1440"/>
   <m:intLim m:val="subSup"/>
   <m:naryLim m:val="undOvr"/>
  </m:mathPr></w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="false"
  DefSemiHidden="false" DefQFormat="false" DefPriority="99"
  LatentStyleCount="375">
  <w:LsdException Locked="false" Priority="0" QFormat="true" Name="Normal"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 1"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 2"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 3"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 4"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 5"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 6"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 7"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 8"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="heading 9"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 6"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 7"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 8"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index 9"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 1"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 2"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 3"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 4"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 5"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 6"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 7"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 8"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" Name="toc 9"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Normal Indent"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="footnote text"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="annotation text"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="header"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="footer"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="index heading"/>
  <w:LsdException Locked="false" Priority="35" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="caption"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="table of figures"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="envelope address"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="envelope return"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="footnote reference"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="annotation reference"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="line number"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="page number"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="endnote reference"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="endnote text"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="table of authorities"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="macro"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="toa heading"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Bullet"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Number"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Bullet 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Bullet 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Bullet 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Bullet 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Number 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Number 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Number 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Number 5"/>
  <w:LsdException Locked="false" Priority="10" QFormat="true" Name="Title"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Closing"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Signature"/>
  <w:LsdException Locked="false" Priority="1" SemiHidden="true"
   UnhideWhenUsed="true" Name="Default Paragraph Font"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text Indent"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Continue"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Continue 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Continue 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Continue 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="List Continue 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Message Header"/>
  <w:LsdException Locked="false" Priority="11" QFormat="true" Name="Subtitle"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Salutation"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Date"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text First Indent"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text First Indent 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Note Heading"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text Indent 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Body Text Indent 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Block Text"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Hyperlink"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="FollowedHyperlink"/>
  <w:LsdException Locked="false" Priority="22" QFormat="true" Name="Strong"/>
  <w:LsdException Locked="false" Priority="20" QFormat="true" Name="Emphasis"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Document Map"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Plain Text"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="E-mail Signature"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Top of Form"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Bottom of Form"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Normal (Web)"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Acronym"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Address"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Cite"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Code"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Definition"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Keyboard"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Preformatted"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Sample"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Typewriter"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="HTML Variable"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Normal Table"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="annotation subject"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="No List"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Outline List 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Outline List 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Outline List 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Simple 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Simple 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Simple 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Classic 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Classic 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Classic 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Classic 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Colorful 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Colorful 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Colorful 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Columns 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Columns 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Columns 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Columns 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Columns 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 6"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 7"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Grid 8"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 4"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 5"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 6"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 7"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table List 8"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table 3D effects 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table 3D effects 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table 3D effects 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Contemporary"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Elegant"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Professional"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Subtle 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Subtle 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Web 1"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Web 2"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Web 3"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Balloon Text"/>
  <w:LsdException Locked="false" Priority="39" Name="Table Grid"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Table Theme"/>
  <w:LsdException Locked="false" SemiHidden="true" Name="Placeholder Text"/>
  <w:LsdException Locked="false" Priority="1" QFormat="true" Name="No Spacing"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 1"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 1"/>
  <w:LsdException Locked="false" SemiHidden="true" Name="Revision"/>
  <w:LsdException Locked="false" Priority="34" QFormat="true"
   Name="List Paragraph"/>
  <w:LsdException Locked="false" Priority="29" QFormat="true" Name="Quote"/>
  <w:LsdException Locked="false" Priority="30" QFormat="true"
   Name="Intense Quote"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 1"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 1"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 2"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 2"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 2"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 3"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 3"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 3"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 4"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 4"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 4"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 5"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 5"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 5"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="60" Name="Light Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="61" Name="Light List Accent 6"/>
  <w:LsdException Locked="false" Priority="62" Name="Light Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="63" Name="Medium Shading 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="64" Name="Medium Shading 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="65" Name="Medium List 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="66" Name="Medium List 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="67" Name="Medium Grid 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="68" Name="Medium Grid 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="69" Name="Medium Grid 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="70" Name="Dark List Accent 6"/>
  <w:LsdException Locked="false" Priority="71" Name="Colorful Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="72" Name="Colorful List Accent 6"/>
  <w:LsdException Locked="false" Priority="73" Name="Colorful Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="19" QFormat="true"
   Name="Subtle Emphasis"/>
  <w:LsdException Locked="false" Priority="21" QFormat="true"
   Name="Intense Emphasis"/>
  <w:LsdException Locked="false" Priority="31" QFormat="true"
   Name="Subtle Reference"/>
  <w:LsdException Locked="false" Priority="32" QFormat="true"
   Name="Intense Reference"/>
  <w:LsdException Locked="false" Priority="33" QFormat="true" Name="Book Title"/>
  <w:LsdException Locked="false" Priority="37" SemiHidden="true"
   UnhideWhenUsed="true" Name="Bibliography"/>
  <w:LsdException Locked="false" Priority="39" SemiHidden="true"
   UnhideWhenUsed="true" QFormat="true" Name="TOC Heading"/>
  <w:LsdException Locked="false" Priority="41" Name="Plain Table 1"/>
  <w:LsdException Locked="false" Priority="42" Name="Plain Table 2"/>
  <w:LsdException Locked="false" Priority="43" Name="Plain Table 3"/>
  <w:LsdException Locked="false" Priority="44" Name="Plain Table 4"/>
  <w:LsdException Locked="false" Priority="45" Name="Plain Table 5"/>
  <w:LsdException Locked="false" Priority="40" Name="Grid Table Light"/>
  <w:LsdException Locked="false" Priority="46" Name="Grid Table 1 Light"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark"/>
  <w:LsdException Locked="false" Priority="51" Name="Grid Table 6 Colorful"/>
  <w:LsdException Locked="false" Priority="52" Name="Grid Table 7 Colorful"/>
  <w:LsdException Locked="false" Priority="46"
   Name="Grid Table 1 Light Accent 1"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4 Accent 1"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark Accent 1"/>
  <w:LsdException Locked="false" Priority="51"
   Name="Grid Table 6 Colorful Accent 1"/>
  <w:LsdException Locked="false" Priority="52"
   Name="Grid Table 7 Colorful Accent 1"/>
  <w:LsdException Locked="false" Priority="46"
   Name="Grid Table 1 Light Accent 2"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4 Accent 2"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark Accent 2"/>
  <w:LsdException Locked="false" Priority="51"
   Name="Grid Table 6 Colorful Accent 2"/>
  <w:LsdException Locked="false" Priority="52"
   Name="Grid Table 7 Colorful Accent 2"/>
  <w:LsdException Locked="false" Priority="46"
   Name="Grid Table 1 Light Accent 3"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4 Accent 3"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark Accent 3"/>
  <w:LsdException Locked="false" Priority="51"
   Name="Grid Table 6 Colorful Accent 3"/>
  <w:LsdException Locked="false" Priority="52"
   Name="Grid Table 7 Colorful Accent 3"/>
  <w:LsdException Locked="false" Priority="46"
   Name="Grid Table 1 Light Accent 4"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4 Accent 4"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark Accent 4"/>
  <w:LsdException Locked="false" Priority="51"
   Name="Grid Table 6 Colorful Accent 4"/>
  <w:LsdException Locked="false" Priority="52"
   Name="Grid Table 7 Colorful Accent 4"/>
  <w:LsdException Locked="false" Priority="46"
   Name="Grid Table 1 Light Accent 5"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4 Accent 5"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark Accent 5"/>
  <w:LsdException Locked="false" Priority="51"
   Name="Grid Table 6 Colorful Accent 5"/>
  <w:LsdException Locked="false" Priority="52"
   Name="Grid Table 7 Colorful Accent 5"/>
  <w:LsdException Locked="false" Priority="46"
   Name="Grid Table 1 Light Accent 6"/>
  <w:LsdException Locked="false" Priority="47" Name="Grid Table 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="48" Name="Grid Table 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="49" Name="Grid Table 4 Accent 6"/>
  <w:LsdException Locked="false" Priority="50" Name="Grid Table 5 Dark Accent 6"/>
  <w:LsdException Locked="false" Priority="51"
   Name="Grid Table 6 Colorful Accent 6"/>
  <w:LsdException Locked="false" Priority="52"
   Name="Grid Table 7 Colorful Accent 6"/>
  <w:LsdException Locked="false" Priority="46" Name="List Table 1 Light"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark"/>
  <w:LsdException Locked="false" Priority="51" Name="List Table 6 Colorful"/>
  <w:LsdException Locked="false" Priority="52" Name="List Table 7 Colorful"/>
  <w:LsdException Locked="false" Priority="46"
   Name="List Table 1 Light Accent 1"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4 Accent 1"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark Accent 1"/>
  <w:LsdException Locked="false" Priority="51"
   Name="List Table 6 Colorful Accent 1"/>
  <w:LsdException Locked="false" Priority="52"
   Name="List Table 7 Colorful Accent 1"/>
  <w:LsdException Locked="false" Priority="46"
   Name="List Table 1 Light Accent 2"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4 Accent 2"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark Accent 2"/>
  <w:LsdException Locked="false" Priority="51"
   Name="List Table 6 Colorful Accent 2"/>
  <w:LsdException Locked="false" Priority="52"
   Name="List Table 7 Colorful Accent 2"/>
  <w:LsdException Locked="false" Priority="46"
   Name="List Table 1 Light Accent 3"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4 Accent 3"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark Accent 3"/>
  <w:LsdException Locked="false" Priority="51"
   Name="List Table 6 Colorful Accent 3"/>
  <w:LsdException Locked="false" Priority="52"
   Name="List Table 7 Colorful Accent 3"/>
  <w:LsdException Locked="false" Priority="46"
   Name="List Table 1 Light Accent 4"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4 Accent 4"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark Accent 4"/>
  <w:LsdException Locked="false" Priority="51"
   Name="List Table 6 Colorful Accent 4"/>
  <w:LsdException Locked="false" Priority="52"
   Name="List Table 7 Colorful Accent 4"/>
  <w:LsdException Locked="false" Priority="46"
   Name="List Table 1 Light Accent 5"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4 Accent 5"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark Accent 5"/>
  <w:LsdException Locked="false" Priority="51"
   Name="List Table 6 Colorful Accent 5"/>
  <w:LsdException Locked="false" Priority="52"
   Name="List Table 7 Colorful Accent 5"/>
  <w:LsdException Locked="false" Priority="46"
   Name="List Table 1 Light Accent 6"/>
  <w:LsdException Locked="false" Priority="47" Name="List Table 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="48" Name="List Table 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="49" Name="List Table 4 Accent 6"/>
  <w:LsdException Locked="false" Priority="50" Name="List Table 5 Dark Accent 6"/>
  <w:LsdException Locked="false" Priority="51"
   Name="List Table 6 Colorful Accent 6"/>
  <w:LsdException Locked="false" Priority="52"
   Name="List Table 7 Colorful Accent 6"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Mention"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Smart Hyperlink"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Hashtag"/>
  <w:LsdException Locked="false" SemiHidden="true" UnhideWhenUsed="true"
   Name="Unresolved Mention"/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:3 0 0 0 1 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:238;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-536859905 -1073732485 9 0 511 0;}
@font-face
	{font-family:"Calibri Light";
	panose-1:2 15 3 2 2 2 4 3 2 4;
	mso-font-charset:238;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-536859905 -1073732485 9 0 511 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:8.0pt;
	margin-left:0cm;
	line-height:107%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
h1
	{mso-style-priority:9;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-link:"Nadpis 1 Char";
	mso-style-next:Normální;
	margin-top:12.0pt;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:0cm;
	margin-bottom:.0001pt;
	line-height:107%;
	mso-pagination:widow-orphan lines-together;
	page-break-after:avoid;
	mso-outline-level:1;
	font-size:16.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	color:#2F5496;
	mso-themecolor:accent1;
	mso-themeshade:191;
	mso-font-kerning:0pt;
	mso-fareast-language:EN-US;
	font-weight:normal;}
p.MsoTitle, li.MsoTitle, div.MsoTitle
	{mso-style-priority:10;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-link:"Název Char";
	mso-style-next:Normální;
	margin:0cm;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:28.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	letter-spacing:-.5pt;
	mso-font-kerning:14.0pt;
	mso-fareast-language:EN-US;}
p.MsoTitleCxSpFirst, li.MsoTitleCxSpFirst, div.MsoTitleCxSpFirst
	{mso-style-priority:10;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-link:"Název Char";
	mso-style-next:Normální;
	mso-style-type:export-only;
	margin:0cm;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:28.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	letter-spacing:-.5pt;
	mso-font-kerning:14.0pt;
	mso-fareast-language:EN-US;}
p.MsoTitleCxSpMiddle, li.MsoTitleCxSpMiddle, div.MsoTitleCxSpMiddle
	{mso-style-priority:10;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-link:"Název Char";
	mso-style-next:Normální;
	mso-style-type:export-only;
	margin:0cm;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:28.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	letter-spacing:-.5pt;
	mso-font-kerning:14.0pt;
	mso-fareast-language:EN-US;}
p.MsoTitleCxSpLast, li.MsoTitleCxSpLast, div.MsoTitleCxSpLast
	{mso-style-priority:10;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-link:"Název Char";
	mso-style-next:Normální;
	mso-style-type:export-only;
	margin:0cm;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:28.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	letter-spacing:-.5pt;
	mso-font-kerning:14.0pt;
	mso-fareast-language:EN-US;}
span.NzevChar
	{mso-style-name:"Název Char";
	mso-style-priority:10;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:Název;
	mso-ansi-font-size:28.0pt;
	mso-bidi-font-size:28.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	letter-spacing:-.5pt;
	mso-font-kerning:14.0pt;}
span.Nadpis1Char
	{mso-style-name:"Nadpis 1 Char";
	mso-style-priority:9;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:"Nadpis 1";
	mso-ansi-font-size:16.0pt;
	mso-bidi-font-size:16.0pt;
	font-family:"Calibri Light",sans-serif;
	mso-ascii-font-family:"Calibri Light";
	mso-ascii-theme-font:major-latin;
	mso-fareast-font-family:"Times New Roman";
	mso-fareast-theme-font:major-fareast;
	mso-hansi-font-family:"Calibri Light";
	mso-hansi-theme-font:major-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:major-bidi;
	color:#2F5496;
	mso-themecolor:accent1;
	mso-themeshade:191;}
span.SpellE
	{mso-style-name:"";
	mso-spl-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	font-family:"Calibri",sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
.MsoPapDefault
	{mso-style-type:export-only;
	margin-bottom:8.0pt;
	line-height:107%;}
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:70.85pt 70.85pt 70.85pt 70.85pt;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:"Normální tabulka";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin-top:0cm;
	mso-para-margin-right:0cm;
	mso-para-margin-bottom:8.0pt;
	mso-para-margin-left:0cm;
	line-height:107%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri",sans-serif;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext="edit" spidmax="1026"/>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext="edit">
  <o:idmap v:ext="edit" data="1"/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=CS style="tab-interval:35.4pt">

<div class=WordSection1>

<p class=MsoTitle>Znění první všeobecné podmínky</p>

<h1>Jak vytvořit všeobecnou podmínku?</h1>

<p class=MsoNormal>Server FOLDYNA-TUL-BP používá pro vložení znění všeobecné
podmínky html ve formátu stringu. HTML jde vytvořit například ve&nbsp;Wordu od MS
jako běžný dokument. Ten poté uložíte jako html a zdrojový kód nahraje
administrátor v&nbsp;ADMIN zóně do příslušného formuláře.</p>

<h1>Lze všeobecnou podmínku upravit?</h1>

<p class=MsoNormal>U všeobecné podmínky lze upravit pouze datum platnosti (do
kdy podmínka platí) a zdali je platná. Při označení podmínky jako neplatné se
všem soukromým prodejcům, kteří mají přijatou tuto podmínku, zobrazí formulář
pro přijetí nové podmínky. Když ji nepřijmou, nebudou se moci přihlásit. V&nbsp;opačném
případě se aktualizuje tabulka <span lang=EN-US style="mso-ansi-language:EN-US">“</span><span
class=SpellE>notProfessionalUser</span>“ a aplikace je vyzve, aby se přihlásili
znovu. Poté dojde k&nbsp;normálnímu přihlášení.</p>

<h1>Lze všeobecnou podmínku smazat?</h1>

<p class=MsoNormal>Všeobecnou podmínku může vymazat administrátor pouze tehdy,
když veškeří soukromý prodejci mají přijatou novou všeobecnou podmínku.</p>

</div>

</body>

</html>
', 'pdf/vseobecnePodminky/podminka1.pdf', '2018-10-12', null, true);

/*

tabulka user
*/
/*rozšíření pgcrypto*/
create extension pgcrypto;

INSERT INTO public."userContainer"(email, pass, user_type, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po) VALUES ('admin@testovaci.com', ENCODE(DIGEST('Admin1','sha512'),'hex'), 'ADMIN', 'Aleš', 'Foldyna', 'U Lomu 546/11', 'Liberec', 11100, 777888777, false);

INSERT INTO public."userContainer"(email, pass, user_type, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po) VALUES ('profesionalni.prodejce@testovaci.com', ENCODE(DIGEST('Profesional1','sha512'),'hex'), 'PROFI', 'Petr', 'Máchal', 'U mlýna 3', 'Království za sedmoro horami', 11100, 777888888, true);
INSERT INTO public."professionalUser"(count_limit, id_user) VALUES (30, 2);
INSERT INTO public."pravnickeOsoby"(ic, nazev, adresa, mesto, psc, dic, id_user) VALUES (11122211, 'Firma PU s.r.o.', 'U Supa 14', 'Monte Albo', 14000, 'CZ11122211', 2);
INSERT INTO public.smlouva(platna_od, platna_do, platna, pdf_path, vypovedni_obdobi, vypovedni_lhuta, id_user) VALUES ('2018-10-12', null, true, 'pdf/smlouvy/2/smlouva2018-10-19.pdf', false, null, 2);
INSERT INTO public.faktura(id_user, vystavena, splatna, obdobi_od, obdobi_do, pdf_path, zaplacena, castka, variabilni_symbol) VALUES (2, '2018-10-12', '2018-10-26', '2018-10-12', '2018-11-11', 'pdf/faktury/2/fa1122.pdf', false, 24999.99, 1122);

INSERT INTO public."userContainer"(email, pass, user_type, jmeno, prijmeni, adresa, mesto, psc, telefon, is_po) VALUES ('soukromy.prodejce@testovaci.com',  ENCODE(DIGEST('Soukromy1','sha512'),'hex'), 'NOTPROFI', 'Dorota', 'Máchalová', 'Peklo 18', 'Peklo', 11100, 777888999, false);
INSERT INTO public."notProfessionalUser"(id_user, id_podminka, last_login)  VALUES (3, 1, '2018-10-12');


/*

tabulka inzeraty*********************************************************************************

*/


INSERT INTO public.inzeraty(
	id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
	VALUES (1, 1, 1, '{3,4,5,6}', '2.4 JTD SKY WINDOW, 147KW', 'koupeno v I,? nehavarované,? servisní knížka', 120000, 99173.55, true, 2009, '2009-10-01', 88000, 'VINCODE1', '{"photo/1/1.jpg","photo/1/2.jpg","photo/1/3.jpg"}', null, null, '2018-10-12', true);

INSERT INTO public.inzeraty(
	id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
	VALUES (1, 1, 2, '{3,4,5,6,10,15,38,44,45}', '2.4JTD,147KW,KŮŽE,MANUÁL,NAVI', 'koupeno v I,? Atraktivní vůz s výkonným naftovým motorem a bohatou výbavou,? MOŽNOST VÝHODNÉHO FINANCOVÁNÍ S AKONTACÍ OD 0% A VÝKUPU VAŠEHO VOZU PROTIÚČTEM!!', 124999, null, true, 2018, '2018-12-01', 216067, 'VINCODE2', '{"photo/2/1.jpg"}', null, null, '2018-10-12', false);

INSERT INTO public.inzeraty(
	id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
	VALUES (1, 1, 2, '{15,38,44,45}', '2,2 JTS 136 kW', null, 129000, null, true, 2006, '2006-02-01', 124026, 'VINCODE3', '{"photo/3/1.jpg","photo/3/2.jpg"}', null, null, '2018-10-12', false);


INSERT INTO public.inzeraty(
	id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
	VALUES (1, 2, 2, '{1,3,4,5,6,10,15,22,23,24,25,26,27,28,29,30,38,44,45}', '2.2 JTD 150k AT Super, reg. 09', 'Vůz se nachází v Praze – IMOFA spol. s r. o.. Kontaktujte nás na info@imofa.cz nebo 241 731 800.REFERENČNÍ vůz,? 1. reg. 09/2016 IMOFA,? najeto cca 52.000 km,? DEMO BONUS ?-46,?2% (516.000 Kč),? možnost odpočtu DPH (494.876 Kč bez DPH),? vůz nepoškozen,? vůz v tovární záruce 5 let do 09/2021,? DOPORUČUJEME. Tato inzerce není návrh na uzavření kupní smlouvy dle zákona 89/2012 Sb.', 598000, 494215, true, 2016, '2016-02-01', 35000, 'VINCODE4', '{"photo/4/1.jpg","photo/4/2.jpg"}', null, null, '2018-10-12', true);

INSERT INTO public.inzeraty(
	id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
	VALUES (1, 2, 2, '{1,3,4,5,6,10,15,22,23,24,25,26,27,28,29,30,38,44,45}', '2.2 Diesel 180k MT Super', 'Vůz se nachází v Praze – IMOFA spol. s r. o.. Kontaktujte nás na info@imofa.cz nebo 241 731 800.Referentský vůz,? 54.000 km,? ihned k odběru,? SUPER AKCE ŘÍJEN ?-42% (468.100 Kč),? možnost odpočtu DPH (527.934Kč bez DPH),? vůz nepoškozen,? bohatá nadstandardní výbava,? vůz v tovární záruce 5 let do 05/2022,? DOPORUČUJEME. Tato inzerce není návrh na uzavření kupní smlouvy dle zákona 89/2012 Sb. Tato inzerce není návrh na uzavření kupní smlouvy dle zákona 89/2012 Sb.', 638800, 527934, true, 2017, '2017-02-01', 54000, 'VINCODE5', '{"photo/5/1.jpg","photo/5/2.jpg"}', null, null, '2018-10-12', true);


INSERT INTO public.inzeraty(
	id_znacka, id_model, id_user, id_vybava, popisek, text_inzeratu, cena, cena_bez_dph, zverejneny, vyrobeno, provozu_od, najeto, vin, fotogalerie, expirace_zverejneni, expirace_smazani, datum_vytvoreni, odpocet_dph)
	VALUES (1, 3, 3, '{2,5,6,8,10,11,12,13,14,38,44}', '1.6 JTD', 'koupeno v SK,? servisní knížka,? Údaje obsažené v této kartě vozu mají informativní charakter. Tato indikativní nabídka není nabídkou ve smyslu § 1731 nebo § 1732 občanského zákoníku,? ani se nejedná o veřejný příslib dle § 1733 občanského zákoníku. Z této indikativní nabídky nevzniká nárok na uzavření smlouvy.', 155000, 128099, false, 2012, '2012-02-01', 34000, 'VINCODE6', '{"photo/6/1.jpg","photo/6/2.jpg"}', null, null, '2018-10-12', true);

