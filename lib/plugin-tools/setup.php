<?php
namespace MmmPluginToolsNamespace;

function create_tables($_versionnum)
{
  global $wpdb;

  $sql = sprintf("CREATE TABLE IF NOT EXISTS  %s (
        `intID` int(11) NOT NULL AUTO_INCREMENT,
        `vcrName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `vcrDescription` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
        `intQuantity` int(11) NOT NULL,
        `decPrice` decimal(10,2) NOT NULL,
        `intNotifyQuantity` int(11) NOT NULL,
        `dtmStartDate` datetime NOT NULL,
        `dtmEndDate` datetime NOT NULL,
        `tinDeleted` tinyint(1) NOT NULL DEFAULT '0',
        `vcrUrl` varchar(100) NOT NULL DEFAULT '',
        `intExternalID` int(11) NOT NULL DEFAULT '0',
        PRIMARY KEY (`intID`));",
  $wpdb->prefix . "mmpm_product");
  
  \dbDelta($sql);
  
  $sql = sprintf("CREATE TABLE IF NOT EXISTS  %s (
          `intID` int(11) NOT NULL AUTO_INCREMENT,
          `intPurchaserID` int NOT NULL,
        `vcrInvoiceNumber` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `intValid` int NOT NULL DEFAULT '3',
        `dtmDate` datetime NOT NULL,
        `vcrJSON` varchar(9999) COLLATE utf8_unicode_ci DEFAULT '',
        PRIMARY KEY (`intID`));",
  $wpdb->prefix . "mmpm_purchase");
  
  \dbDelta($sql);
  
  $sql = sprintf("CREATE TABLE IF NOT EXISTS  %s (
          `intID` int(11) NOT NULL AUTO_INCREMENT,
        `intPurchaseID` int NOT NULL,
        `intProductID` int NOT NULL,
        `intQuantity` int NOT NULL,
        PRIMARY KEY (`intID`));",
  $wpdb->prefix . "mmpm_lineitem");
  
  \dbDelta($sql);
  
  $sql = sprintf("CREATE TABLE IF NOT EXISTS  %s (
          `intID` int(11) NOT NULL AUTO_INCREMENT,
        `vcrIP` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
        `vcrAgent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `vcrJSON` varchar(9999) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
        PRIMARY KEY (`intID`));",
  $wpdb->prefix . "mmpm_purchaser");
  
  \dbDelta($sql);
  
  add_option("mmpm_versionnum", $_versionnum);
}



?>