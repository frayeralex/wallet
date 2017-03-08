Yii 2 "Wallet"
===============================

[aws server:](http://35.156.244.104/)

ec2-35-156-244-104.eu-central-1.compute.amazonaws.com

DB
```sql
CREATE TABLE `Users` (
	`id` INT NOT NULL,
	`name` varchar(200) NOT NULL,
	`surname` varchar(200) NOT NULL,
	`email` varchar(200) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Incomes` (
	`id` INT NOT NULL,
	`date` DATE NOT NULL,
	`user_id` INT NOT NULL,
	`category_id` INT NOT NULL,
	`walet` varchar NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Outcomes` (
	`id` INT NOT NULL,
	`date` DATE NOT NULL,
	`user_id` INT NOT NULL,
	`category_id` DATE NOT NULL,
	`wallet` varchar NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Categories` (
	`id` INT NOT NULL,
	`name` varchar(50) NOT NULL UNIQUE,
	`type` varchar(50) NOT NULL,
	`owner` varchar(200) NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Wallet` (
	`id` INT NOT NULL,
	`name` varchar(50) NOT NULL UNIQUE,
	`owner` INT NOT NULL,
	`value` FLOAT NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `Incomes` ADD CONSTRAINT `Incomes_fk0` FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`);

ALTER TABLE `Incomes` ADD CONSTRAINT `Incomes_fk1` FOREIGN KEY (`category_id`) REFERENCES `Categories`(`id`);

ALTER TABLE `Incomes` ADD CONSTRAINT `Incomes_fk2` FOREIGN KEY (`walet`) REFERENCES `Wallet`(`name`);

ALTER TABLE `Outcomes` ADD CONSTRAINT `Outcomes_fk0` FOREIGN KEY (`user_id`) REFERENCES `Users`(`id`);

ALTER TABLE `Outcomes` ADD CONSTRAINT `Outcomes_fk1` FOREIGN KEY (`category_id`) REFERENCES `Categories`(`id`);

ALTER TABLE `Outcomes` ADD CONSTRAINT `Outcomes_fk2` FOREIGN KEY (`wallet`) REFERENCES `Wallet`(`name`);

ALTER TABLE `Categories` ADD CONSTRAINT `Categories_fk0` FOREIGN KEY (`owner`) REFERENCES `Users`(`id`);

ALTER TABLE `Wallet` ADD CONSTRAINT `Wallet_fk0` FOREIGN KEY (`owner`) REFERENCES `Users`(`id`);
```
