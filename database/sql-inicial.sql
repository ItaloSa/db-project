-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema postoDb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `postoDb` ;

-- -----------------------------------------------------
-- Schema postoDb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `postoDb` DEFAULT CHARACTER SET utf8 ;
USE `postoDb` ;

-- -----------------------------------------------------
-- Table `postoDb`.`bandeira`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`bandeira` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`bandeira` (
  `nome` VARCHAR(50) NOT NULL,
  `url` VARCHAR(100) NULL,
  PRIMARY KEY (`nome`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`cidade`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`cidade` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`cidade` (
  `nome` VARCHAR(20) NOT NULL,
  `estado` VARCHAR(20) NOT NULL,
  `latitude` DECIMAL(10,8) NOT NULL,
  `longitude` DECIMAL(11,8) NOT NULL,
  PRIMARY KEY (`nome`, `estado`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`bairro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`bairro` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`bairro` (
  `nome` VARCHAR(20) NOT NULL,
  `cidade_nome` VARCHAR(20) NOT NULL,
  `cidade_estado` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`nome`),
  INDEX `fk_bairro_cidade1_idx` (`cidade_nome` ASC, `cidade_estado` ASC),
  CONSTRAINT `fk_cidade`
    FOREIGN KEY (`cidade_nome` , `cidade_estado`)
    REFERENCES `postoDb`.`cidade` (`nome` , `estado`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`posto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`posto` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`posto` (
  `cnpj` VARCHAR(18) NOT NULL,
  `razao_social` VARCHAR(255) NOT NULL,
  `nome_fantasia` VARCHAR(255) NULL,
  `latitude` DECIMAL(10,8) NOT NULL,
  `longitude` DECIMAL(11,8) NOT NULL,
  `endereco` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(14) NULL,
  `bandeira_nome` VARCHAR(50) NULL,
  `bairro_nome` VARCHAR(20) NULL,
  PRIMARY KEY (`cnpj`),
  INDEX `fk_posto_bandeira_idx` (`bandeira_nome` ASC),
  INDEX `fk_posto_bairro1_idx` (`bairro_nome` ASC),
  CONSTRAINT `fk_bandeira`
    FOREIGN KEY (`bandeira_nome`)
    REFERENCES `postoDb`.`bandeira` (`nome`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_bairro_posto`
    FOREIGN KEY (`bairro_nome`)
    REFERENCES `postoDb`.`bairro` (`nome`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`combustivel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`combustivel` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`combustivel` (
  `nome` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`nome`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`posto_combustivel`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`posto_combustivel` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`posto_combustivel` (
  `combustivel_nome` VARCHAR(10) NOT NULL,
  `posto_cnpj` VARCHAR(18) NOT NULL,
  PRIMARY KEY (`combustivel_nome`, `posto_cnpj`),
  INDEX `fk_combustivel_has_posto_posto1_idx` (`posto_cnpj` ASC),
  INDEX `fk_combustivel_has_posto_combustivel1_idx` (`combustivel_nome` ASC),
  CONSTRAINT `fk_combustivel_posto_combustivel`
    FOREIGN KEY (`combustivel_nome`)
    REFERENCES `postoDb`.`combustivel` (`nome`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_posto_posto_combustivel`
    FOREIGN KEY (`posto_cnpj`)
    REFERENCES `postoDb`.`posto` (`cnpj`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`preco`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`preco` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`preco` (
  `momento` DATETIME NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `combustivel_nome` VARCHAR(10) NOT NULL,
  `posto_cnpj` VARCHAR(18) NOT NULL,
  PRIMARY KEY (`momento`),
  INDEX `fk_preco_posto_combustivel1_idx` (`combustivel_nome` ASC, `posto_cnpj` ASC),
  CONSTRAINT `fk_preco_posto_combustivel1`
    FOREIGN KEY (`combustivel_nome` , `posto_cnpj`)
    REFERENCES `postoDb`.`posto_combustivel` (`combustivel_nome` , `posto_cnpj`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`tipo_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`tipo_usuario` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`tipo_usuario` (
  `nome` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`nome`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`usuario` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`usuario` (
  `login` VARCHAR(10) NOT NULL,
  `senha` VARCHAR(32) NOT NULL,
  `tipo_usuario_nome` VARCHAR(20) NULL,
  PRIMARY KEY (`login`),
  INDEX `fk_usuario_tipo_usuario1_idx` (`tipo_usuario_nome` ASC),
  CONSTRAINT `fk_tipo_usuario`
    FOREIGN KEY (`tipo_usuario_nome`)
    REFERENCES `postoDb`.`tipo_usuario` (`nome`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`pessoa`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`pessoa` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`pessoa` (
  `login` VARCHAR(10) NOT NULL,
  `endereco` VARCHAR(255) NOT NULL,
  `nome` VARCHAR(30) NOT NULL,
  `usuario_login` VARCHAR(10) NULL,
  `bairro_nome` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`login`),
  INDEX `fk_pessoa_usuario1_idx` (`usuario_login` ASC),
  INDEX `fk_pessoa_bairro1_idx` (`bairro_nome` ASC),
  CONSTRAINT `fk_usuario`
    FOREIGN KEY (`usuario_login`)
    REFERENCES `postoDb`.`usuario` (`login`)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `fk_bairro_pessoa`
    FOREIGN KEY (`bairro_nome`)
    REFERENCES `postoDb`.`bairro` (`nome`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`veiculo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`veiculo` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`veiculo` (
  `placa` VARCHAR(7) NOT NULL,
  `marca` VARCHAR(20) NULL,
  `modelo` VARCHAR(20) NULL,
  `pessoa_login` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`placa`),
  INDEX `fk_veiculo_pessoa1_idx` (`pessoa_login` ASC),
  CONSTRAINT `fk_pessoa_veiculo`
    FOREIGN KEY (`pessoa_login`)
    REFERENCES `postoDb`.`pessoa` (`login`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`abastecido`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`abastecido` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`abastecido` (
  `combustivel_nome` VARCHAR(10) NOT NULL,
  `veiculo_placa` VARCHAR(7) NOT NULL,
  PRIMARY KEY (`combustivel_nome`, `veiculo_placa`),
  INDEX `fk_combustivel_has_veiculo_veiculo1_idx` (`veiculo_placa` ASC),
  INDEX `fk_combustivel_has_veiculo_combustivel1_idx` (`combustivel_nome` ASC),
  CONSTRAINT `fk_combustivel_abastecido`
    FOREIGN KEY (`combustivel_nome`)
    REFERENCES `postoDb`.`combustivel` (`nome`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_veiculo`
    FOREIGN KEY (`veiculo_placa`)
    REFERENCES `postoDb`.`veiculo` (`placa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `postoDb`.`comentario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `postoDb`.`comentario` ;

CREATE TABLE IF NOT EXISTS `postoDb`.`comentario` (
  `posto_cnpj` VARCHAR(18) NOT NULL,
  `pessoa_login` VARCHAR(10) NOT NULL,
  `momento` DATETIME NOT NULL,
  PRIMARY KEY (`posto_cnpj`, `pessoa_login`),
  INDEX `fk_posto_has_pessoa_pessoa1_idx` (`pessoa_login` ASC),
  INDEX `fk_posto_has_pessoa_posto1_idx` (`posto_cnpj` ASC),
  CONSTRAINT `fk_posto_comentario`
    FOREIGN KEY (`posto_cnpj`)
    REFERENCES `postoDb`.`posto` (`cnpj`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_pessoa_comentario`
    FOREIGN KEY (`pessoa_login`)
    REFERENCES `postoDb`.`pessoa` (`login`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

CREATE 
VIEW `bairro_cidade` AS
    SELECT 
        `b`.`nome` AS `bairro_nome`,
        `b`.`cidade_nome` AS `cidade_nome`,
        `b`.`cidade_estado` AS `cidade_estado`,
        `c`.`latitude` AS `cidade_latitude`,
        `c`.`longitude` AS `cidade_longitude`
    FROM
        (`bairro` `b`
        JOIN `cidade` `c` ON (((`b`.`cidade_nome` = `c`.`nome`)
            AND (`b`.`cidade_estado` = `c`.`estado`))));
            
CREATE 
VIEW `pessoa_completa` AS
    SELECT 
        `p`.`login` AS `login`,
        `p`.`endereco` AS `endereco`,
        `p`.`nome` AS `nome`,
        `p`.`usuario_login` AS `usuario_login`,
        `p`.`bairro_nome` AS `bairro_nome`,
        `bc`.`cidade_nome` AS `cidade_nome`,
        `bc`.`cidade_estado` AS `cidade_estado`,
        `bc`.`cidade_latitude` AS `cidade_latitude`,
        `bc`.`cidade_longitude` AS `cidade_longitude`,
        `u`.`senha` AS `senha`,
        `u`.`tipo_usuario_nome` AS `tipo_usuario_nome`
    FROM
        (((`pessoa` `p`
        JOIN `bairro_cidade` `bc` ON ((`p`.`bairro_nome` = `bc`.`bairro_nome`)))
        LEFT JOIN `usuario` `u` ON ((`p`.`usuario_login` = `u`.`login`)))
        LEFT JOIN `tipo_usuario` `tu` ON ((`u`.`tipo_usuario_nome` = `tu`.`nome`)));
        
CREATE 
VIEW `posto_completo` AS
    SELECT 
        `p`.`cnpj` AS `cnpj`,
        `p`.`razao_social` AS `razao_social`,
        `p`.`nome_fantasia` AS `nome_fantasia`,
        `p`.`latitude` AS `latitude`,
        `p`.`longitude` AS `longitude`,
        `p`.`endereco` AS `endereco`,
        `p`.`telefone` AS `telefone`,
        `bc`.`bairro_nome` AS `bairro_nome`,
        `bc`.`cidade_nome` AS `cidade_nome`,
        `bc`.`cidade_estado` AS `cidade_estado`,
        `bc`.`cidade_latitude` AS `cidade_latitude`,
        `bc`.`cidade_longitude` AS `cidade_longitude`,
        `b`.`nome` AS `bandeira_nome`,
        `b`.`url` AS `bandeira_url`
    FROM
        ((`posto` `p`
        LEFT JOIN `bairro_cidade` `bc` ON ((`p`.`bairro_nome` = `bc`.`bairro_nome`)))
        LEFT JOIN `bandeira` `b` ON ((`p`.`bandeira_nome` = `b`.`nome`)));