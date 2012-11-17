-- articles
DROP TABLE IF EXISTS wiki1_1_article;
CREATE TABLE wiki1_1_article (
	articleID 	INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	versionID	INT DEFAULT 0,
	categoryID 	INT(10),
	userID		INT(10),
	username	VARCHAR(255),
	subject		VARCHAR(255),
	message		TEXT,
	time 		INT(10) NOT NULL,
	languageID   	INT(10),
	translationID	INT(10) NOT NULL,
	parentID	INT DEFAULT NULL,
	isActive	TINYINT(1) NOT NULL DEFAULT 0,
	isDeleted	TINYINT(1) NOT NULL DEFAULT 0,
	deleteTime 	INT(10) NULL,
	lastPostTime	INT(10)
);

-- labels
DROP TABLE IF EXISTS wiki1_1_article_label;
CREATE TABLE wiki1_1_article_label (
	labelID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	userID INT(10) NOT NULL,
	label VARCHAR(80) NOT NULL DEFAULT '',
	cssClassName VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS wiki1_1_article_label_to_object;
CREATE TABLE wiki1_1_article_label_to_object (
	labelID INT(10) NOT NULL,
	articleID INT(10) NOT NULL,

UNIQUE KEY (labelID, conversationID)
);

-- foreign keys
ALTER TABLE wiki1_1_article ADD FOREIGN KEY (categoryID) REFERENCES wcf1_category (categoryID) ON DELETE CASCADE;
ALTER TABLE wiki1_1_article ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;
ALTER TABLE wiki1_1_article ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;

ALTER TABLE wiki1_1_article_label ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE CASCADE;

ALTER TABLE wiki1_1_article_label_to_object ADD FOREIGN KEY (labelID) REFERENCES wiki1_1_article_label (labelID) ON DELETE CASCADE;
ALTER TABLE wiki1_1_article_label_to_object ADD FOREIGN KEY (articleID) REFERENCES wiki1_1_article (articleID) ON DELETE CASCADE;