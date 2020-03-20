/*
 Navicat Premium Data Transfer

 Source Server         : Amazon-Arquivos
 Source Server Type    : MySQL
 Source Server Version : 50729
 Source Host           : 54.146.161.153
 Source Database       : bitcoin

 Target Server Type    : MySQL
 Target Server Version : 50729
 File Encoding         : utf-8

 Date: 03/20/2020 15:45:54 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `log`
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `data` datetime NOT NULL,
  `usuario` decimal(30,0) NOT NULL,
  `log` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`data`,`usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `log`
-- ----------------------------
BEGIN;
INSERT INTO `log` VALUES ('2020-03-19 10:44:55', '0', 'Message  sent. '), ('2020-03-19 10:47:04', '0', 'Message  sent. '), ('2020-03-19 10:47:42', '0', 'Message  sent. '), ('2020-03-19 10:48:03', '0', 'Message  sent. '), ('2020-03-19 10:48:35', '0', 'Criou Usuario - fernando@fmsistemas.net'), ('2020-03-19 10:49:05', '0', 'Criou Usuario - fernando@fmsistemas.net'), ('2020-03-19 10:49:30', '0', 'Criou Usuario - fernando@fmsistemas.net'), ('2020-03-19 10:49:45', '0', 'Message  sent. '), ('2020-03-19 10:50:40', '0', 'Message  sentParabÃ©ns, fernando@fmsistemas.net <br><br>Bem vindo ao BitCoin, seu cadastro foi feito com sucesso!!!<br><br>Equipe BitCoin'), ('2020-03-19 10:55:58', '0', 'Usuario jÃ¡ existe - fernando@fmsistemas.net'), ('2020-03-20 09:49:01', '0', 'Atualizou Usuario -  -> Fernando Camacho'), ('2020-03-20 09:49:57', '0', 'Atualizou Usuario -  -> fernando camacho'), ('2020-03-20 09:50:35', '0', 'Atualizou Usuario -  -> fernnado camacho'), ('2020-03-20 09:50:54', '200319000005', 'Atualizou Usuario - fernando@fmsistemas.net -> fernando camacho'), ('2020-03-20 09:52:14', '200319000005', 'Atualizou Usuario - fernando@fmsistemas.net -> fer camacho'), ('2020-03-20 10:41:29', '200319000005', 'Message  sentParabÃ©ns,  <br><br>DepÃ³sito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin'), ('2020-03-20 10:44:47', '200319000005', 'Message  sentParabÃ©ns,  <br><br>DepÃ³sito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin'), ('2020-03-20 10:46:32', '200319000005', 'Message  sentParabÃ©ns,  <br><br>DepÃ³sito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin'), ('2020-03-20 10:46:56', '200319000005', 'Message  sentParabÃ©ns,  <br><br>DepÃ³sito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin'), ('2020-03-20 10:47:26', '200319000005', 'Message  sentParabÃ©ns,  <br><br>DepÃ³sito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin'), ('2020-03-20 10:47:59', '200319000005', 'Message  sentParabÃ©ns,  <br><br>DepÃ³sito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin');
COMMIT;

-- ----------------------------
--  Table structure for `saldo`
-- ----------------------------
DROP TABLE IF EXISTS `saldo`;
CREATE TABLE `saldo` (
  `id` decimal(30,0) NOT NULL,
  `bitcoin` decimal(15,5) NOT NULL,
  `valor` decimal(15,5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `saldo`
-- ----------------------------
BEGIN;
INSERT INTO `saldo` VALUES ('200319000002', '0.00000', '0.00000'), ('200319000005', '0.00000', '99.97801');
COMMIT;

-- ----------------------------
--  Table structure for `transacoes`
-- ----------------------------
DROP TABLE IF EXISTS `transacoes`;
CREATE TABLE `transacoes` (
  `id` decimal(30,0) NOT NULL,
  `user` decimal(30,0) NOT NULL,
  `ano` int(4) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `deposito` decimal(30,5) NOT NULL,
  `compra` decimal(30,5) NOT NULL,
  `venda` decimal(30,5) NOT NULL,
  `btc` decimal(30,5) NOT NULL,
  PRIMARY KEY (`id`,`data`,`user`),
  KEY `data` (`user`,`data`,`hora`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `transacoes`
-- ----------------------------
BEGIN;
INSERT INTO `transacoes` VALUES ('1', '200319000005', '2020', '2020-03-20', '10:47:59', '100.00000', '0.00000', '0.00000', '0.00000'), ('2', '200319000005', '2020', '2020-03-20', '12:39:06', '0.00000', '100.00000', '0.00000', '33571.85973'), ('3', '200319000005', '2020', '2020-03-20', '12:43:02', '0.00000', '0.00000', '0.00298', '33549.83924'), ('4', '200319000005', '2020', '2020-03-20', '12:48:08', '0.00000', '99.97852', '0.00000', '33499.79001'), ('5', '200319000005', '2020', '2020-03-20', '12:48:58', '0.00000', '0.00000', '0.00298', '33571.79000'), ('6', '200319000005', '2020', '2020-03-20', '13:26:57', '0.00000', '100.04393', '0.00000', '33105.30000'), ('7', '200319000005', '2020', '2020-03-20', '13:27:03', '0.00000', '0.00000', '0.00302', '33105.30000');
COMMIT;

-- ----------------------------
--  Table structure for `usuario`
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `nome` varchar(160) NOT NULL,
  `email` varchar(160) NOT NULL,
  `senha` varchar(30) DEFAULT NULL,
  `id` decimal(30,0) NOT NULL,
  `cadastro` date DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `usuario`
-- ----------------------------
BEGIN;
INSERT INTO `usuario` VALUES ('teste2', 'teste2', 'teste123', '200319000002', '2020-03-19'), ('fernando camacho', 'teste', '123', '200319000005', '2020-03-19');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
