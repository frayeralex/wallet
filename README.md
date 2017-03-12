Yii 2 "Wallet"
===============================

[aws server:](http://35.156.244.104/)

ec2-35-156-244-104.eu-central-1.compute.amazonaws.com

```sql
CREATE TABLE `Users` (
	`id` INT NOT NULL,
	`firstName` varchar NOT NULL,
	`lastName` varchar NOT NULL,
	`email` varchar NOT NULL,
	`phone` INT NOT NULL,
	`username` varchar NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `income` (
	`id` INT NOT NULL,
	`createdAt` DATE NOT NULL,
	`userId` INT NOT NULL,
	`categoryId` INT NOT NULL,
	`waletId` varchar NOT NULL,
	`updatedAt` DATE NOT NULL,
	`value` INT NOT NULL,
	`title` varchar NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `outcome` (
	`id` INT NOT NULL,
	`createdAt` DATE NOT NULL,
	`userId` INT NOT NULL,
	`categoryId` DATE NOT NULL,
	`walletId` varchar NOT NULL,
	`updatedAt` DATE NOT NULL,
	`value` INT NOT NULL,
	`title` varchar NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `category` (
	`id` INT NOT NULL,
	`name` varchar(50) NOT NULL UNIQUE,
	`type` varchar(50) NOT NULL,
	`userId` INT NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `Wallet` (
	`id` INT NOT NULL,
	`name` varchar(50) NOT NULL UNIQUE,
	`userId` INT NOT NULL,
	`value` INT NOT NULL,
	`createdAt` DATE NOT NULL,
	`updatedAt` DATE NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `income` ADD CONSTRAINT `income_fk0` FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);

ALTER TABLE `income` ADD CONSTRAINT `income_fk1` FOREIGN KEY (`categoryId`) REFERENCES `category`(`id`);

ALTER TABLE `income` ADD CONSTRAINT `income_fk2` FOREIGN KEY (`waletId`) REFERENCES `Wallet`(`name`);

ALTER TABLE `outcome` ADD CONSTRAINT `outcome_fk0` FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);

ALTER TABLE `outcome` ADD CONSTRAINT `outcome_fk1` FOREIGN KEY (`categoryId`) REFERENCES `category`(`id`);

ALTER TABLE `outcome` ADD CONSTRAINT `outcome_fk2` FOREIGN KEY (`walletId`) REFERENCES `Wallet`(`name`);

ALTER TABLE `category` ADD CONSTRAINT `category_fk0` FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);

ALTER TABLE `Wallet` ADD CONSTRAINT `Wallet_fk0` FOREIGN KEY (`userId`) REFERENCES `Users`(`id`);

```
