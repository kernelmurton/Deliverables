// Linktable create//
use surija_main
CREATE TABLE `Blogtable` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(64) NOT NULL ,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
CREATE TABLE `Linktable` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `contents_id` INT NOT NULL ,
    `inner_num` INT NOT NULL ,
    `title` VARCHAR(64) NOT NULL ,
    `discribe` VARCHAR(256), 
    `url` VARCHAR(256) NOT NULL , 
    PRIMARY KEY (`id`),
    FOREIGN KEY (contents_id) references Blogtable(id)
) ENGINE = InnoDB;
CREATE TABLE `Commenttable` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `contents_id` INT NOT NULL ,
    `inner_num` INT NOT NULL ,
    `time` TIME NOT NULL ,
    `user_name` VARCHAR(64) NOT NULL ,
    `Comment` VARCHAR(256), 
    PRIMARY KEY (`id`),
    FOREIGN KEY (contents_id) references Linktable(contents_id)
    FOREIGN KEY (inner_num) references Linktable(inner_num)
) ENGINE = InnoDB;