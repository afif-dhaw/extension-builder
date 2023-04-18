CREATE TABLE tx_filter_domain_model_enterprise (
	name varchar(255) NOT NULL DEFAULT '',
	address varchar(255) NOT NULL DEFAULT '',
	phone varchar(255) NOT NULL DEFAULT '',
	website varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	code_postal varchar(255) NOT NULL DEFAULT '',
	ville varchar(255) NOT NULL DEFAULT '',
	category int(11) unsigned DEFAULT '0'
);

CREATE TABLE tx_filter_domain_model_categoryenterprise (
	name varchar(255) NOT NULL DEFAULT ''
);
CREATE TABLE tx_filter_domain_model_enterprise (
	category_perms varchar(255) DEFAULT ''
);

CREATE TABLE tx_news_domain_model_news (
   title_text varchar(2000) NOT NULL DEFAULT '',
   intro_text varchar(2000) NOT NULL DEFAULT '' 
);
CREATE TABLE fe_users (
	role varchar(255) DEFAULT '',
);