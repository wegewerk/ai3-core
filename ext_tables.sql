CREATE TABLE tx_ai3_domain_model_generation_task (
    uid int(11) unsigned NOT NULL auto_increment,
    pid int(11) unsigned DEFAULT '0' NOT NULL,

    status varchar(255) DEFAULT '' NOT NULL,
    prompt text,
    image text,
    capability varchar(255) DEFAULT '' NOT NULL,
    record_table varchar(255) DEFAULT '' NOT NULL,
    record_field varchar(255) DEFAULT '' NOT NULL,
    record_uid int(11) unsigned DEFAULT '0' NOT NULL,
    generate_language varchar(255) DEFAULT '' NOT NULL,
    parameters text,
    result text,
    result_meta text,
    error_message text,
    reviewed tinyint(1) unsigned DEFAULT '0' NOT NULL,
    generated_timestamp int(11) unsigned DEFAULT '0' NOT NULL,

    tstamp int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    deleted tinyint(1) unsigned DEFAULT '0' NOT NULL,

    PRIMARY KEY (uid),
    KEY parent (pid)
);
