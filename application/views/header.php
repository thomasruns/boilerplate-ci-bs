<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Check for maintenance mode
$maintenance_mode = false;
if ($this->uri->segment(1) === 'maintenance') {
    $maintenance_mode = true;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?=$this->config->item('site_name');?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link href="<?=base_url()?>assets/css/themes/solar/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="<?=base_url()?>assets/css/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/themes/solar/custom.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/style.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/css/fontawesome/css/all.min.css" rel="stylesheet">

    <link href="<?=base_url()?>favicon.gif" type="image/gif" rel="icon" >
    <link href="<?=base_url()?>apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180" >
    <link rel="icon" sizes="180x180" href="<?=base_url()?>apple-touch-icon-180x180.png">

    <script src="<?=base_url()?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=base_url()?>assets/js/jquery-ui/jquery-ui.min.js"></script>
  </head>
  <body>
    <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
      <div class="container">
        <a href="/home" class="navbar-brand">Company X</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav">
            <?php
              if ($this->session->userdata('logged_in') === 1) {
            ?>
            <li class="nav-item">
              <a class="nav-link" href="/logout">Logout</a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="container">
      

      

      

