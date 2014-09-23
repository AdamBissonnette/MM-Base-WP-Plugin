<?php
namespace MmmPluginToolsNamespace;

function create_tables($_versionnum)
{
  global $wpdb;
  
  add_option("mmb_versionnum", $_versionnum);
}



?>