<?php

/* ----------------------------------------------- *
 * Konrad Zdanowicz                                *
 * zdanowicz.konrad@gmail.com                      *                 
 * ----------------------------------------------- *
 *
 * config.php
 *
 */
/*** Configs inclusion */
require_once('database.php'); 

/*** Session construction */
session_start();

/*** Functions & classes */ 
require_once '../includes/function.misc.php';
require_once '../includes/Smarty/libs/Smarty.class.php';
require_once '../includes/class.Kos.php';

/*** Classes construction */
$core=new Kos();

       
/* Misc. settings */
date_default_timezone_set("Europe/Warsaw");

 ?>