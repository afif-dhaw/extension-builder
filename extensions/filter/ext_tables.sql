CREATE TABLE tx_filter_domain_model_enterprise (
	name varchar(255) NOT NULL DEFAULT '',
	address varchar(255) NOT NULL DEFAULT '',
	phone varchar(255) NOT NULL DEFAULT '',
	website varchar(255) NOT NULL DEFAULT '',
	email varchar(255) NOT NULL DEFAULT '',
	code_postal varchar(255) NOT NULL DEFAULT '',
	ville varchar(255) NOT NULL DEFAULT '',
	category int(11) unsigned NOT NULL DEFAULT '0'
);

CREATE TABLE tx_filter_domain_model_categoryenterprise (
	name varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_filter_enterprise_categoryenterprise_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid_local,uid_foreign),
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
