<?php
session_start();

include('controllers/logout.php');

header('Location:views/home.php');