<?php
require_once 'controllers/ClientesController.php';
$_GET['q'] = $argv[1] ?? 'gmail';
$c = new ClientesController();
$c->search();
