/*
Created		22.08.2018
Modified		14.03.2019
Project		
Model			
Company		
Author		
Version		
Database		PostgreSQL 8.1 
*/


/* Create Tables */


Create table "znacka"
(
	"id_znacka" BigSerial NOT NULL,
	"nazev" Text NOT NULL,
 primary key ("id_znacka")
) Without Oids;


Create table "model"
(
	"id_model" BigSerial NOT NULL,
	"id_znacka" Bigint NOT NULL,
	"nazev" Text NOT NULL,
	"vyrobeno_od" Integer NOT NULL,
	"vyrobeno_do" Integer,
	"id_vybava" Integer[],
 primary key ("id_model","id_znacka")
) Without Oids;


Create table "vybava"
(
	"id_vybava" BigSerial NOT NULL,
	"nazev" Text NOT NULL,
	"kategorie" Text NOT NULL,
 primary key ("id_vybava")
) Without Oids;


Create table "notProfessionalUser"
(
	"id_user" Bigint NOT NULL,
	"id_podminka" Bigint NOT NULL,
	"last_login" Date NOT NULL Default now(),
 primary key ("id_user","id_podminka")
) Without Oids;


Create table "pravnickeOsoby"
(
	"ic" Integer NOT NULL UNIQUE,
	"nazev" Text NOT NULL,
	"adresa" Text NOT NULL,
	"mesto" Text NOT NULL,
	"psc" Integer NOT NULL,
	"dic" Text NOT NULL,
	"id_user" Bigint NOT NULL,
 primary key ("id_user")
) Without Oids;


Create table "inzeraty"
(
	"id_adver" BigSerial NOT NULL,
	"id_znacka" Bigint NOT NULL,
	"id_model" Bigint NOT NULL,
	"id_user" Bigint NOT NULL,
	"id_vybava" Integer[],
	"popisek" Varchar(60),
	"text_inzeratu" Text,
	"cena" Money NOT NULL,
	"cena_bez_dph" Money,
	"zverejneny" Boolean NOT NULL,
	"vyrobeno" Integer NOT NULL,
	"provozu_od" Date NOT NULL,
	"najeto" Integer NOT NULL,
	"vin" Text NOT NULL UNIQUE,
	"fotogalerie" Text[],
	"expirace_zverejneni" Date,
	"expirace_smazani" Date,
	"datum_vytvoreni" Date,
	"odpocet_dph" Boolean NOT NULL,
 primary key ("id_adver","id_znacka","id_model","id_user")
) Without Oids;


Create table "professionalUser"
(
	"count_limit" Integer NOT NULL,
	"id_user" Bigint NOT NULL,
 primary key ("id_user")
) Without Oids;


Create table "userContainer"
(
	"id_user" BigSerial NOT NULL,
	"email" Text NOT NULL UNIQUE,
	"pass" Text NOT NULL,
	"user_type" Text NOT NULL,
	"jmeno" Text NOT NULL,
	"prijmeni" Text NOT NULL,
	"adresa" Text NOT NULL,
	"mesto" Text NOT NULL,
	"psc" Integer NOT NULL,
	"telefon" Integer NOT NULL,
	"is_po" Boolean NOT NULL,
 primary key ("id_user")
) Without Oids;


Create table "faktura"
(
	"id_faktura" BigSerial NOT NULL,
	"id_user" Bigint NOT NULL,
	"vystavena" Date NOT NULL,
	"splatna" Date NOT NULL,
	"obdobi_od" Date NOT NULL,
	"obdobi_do" Date NOT NULL,
	"pdf_path" Text,
	"zaplacena" Boolean NOT NULL Default false,
	"castka" Money NOT NULL,
	"variabilni_symbol" Integer NOT NULL,
 primary key ("id_faktura","id_user")
) Without Oids;


Create table "smlouva"
(
	"id_smlouva" BigSerial NOT NULL,
	"platna_od" Date NOT NULL,
	"platna_do" Date,
	"platna" Boolean NOT NULL,
	"pdf_path" Text,
	"vypovedni_obdobi" Boolean NOT NULL,
	"vypovedni_lhuta" Date,
	"id_user" Bigint NOT NULL,
 primary key ("id_smlouva","id_user")
) Without Oids;


Create table "vseobecnePodminky"
(
	"id_podminka" BigSerial NOT NULL,
	"zneni" Text NOT NULL,
	"pdf_path" Text,
	"platnost_od" Date NOT NULL,
	"platnost_do" Date,
	"platna" Boolean NOT NULL,
 primary key ("id_podminka")
) Without Oids;



/* Create Foreign Keys */

Alter table "model" add  foreign key ("id_znacka") references "znacka" ("id_znacka") on update cascade on delete cascade;

Alter table "inzeraty" add  foreign key ("id_model","id_znacka") references "model" ("id_model","id_znacka") on update restrict on delete restrict;

Alter table "faktura" add  foreign key ("id_user") references "professionalUser" ("id_user") on update cascade on delete cascade;

Alter table "smlouva" add  foreign key ("id_user") references "professionalUser" ("id_user") on update cascade on delete cascade;

Alter table "inzeraty" add  foreign key ("id_user") references "userContainer" ("id_user") on update cascade on delete cascade;

Alter table "professionalUser" add  foreign key ("id_user") references "userContainer" ("id_user") on update cascade on delete cascade;

Alter table "notProfessionalUser" add  foreign key ("id_user") references "userContainer" ("id_user") on update cascade on delete cascade;

Alter table "pravnickeOsoby" add  foreign key ("id_user") references "userContainer" ("id_user") on update cascade on delete cascade;

Alter table "notProfessionalUser" add  foreign key ("id_podminka") references "vseobecnePodminky" ("id_podminka") on update restrict on delete restrict;





