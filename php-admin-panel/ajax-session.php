<?php
require "lib/Core.php";
if (isset($_POST["req"])) { switch ($_POST["req"]) {
  // (A) INVALID REQUEST
  default: exit("INVALID REQUEST");

  // (B) LOGIN
  case "in":
    // (B1) ALREADY SIGNED IN
    if (isset($_SESSION["user"])) { exit("OK"); }

    // (B2) VERIFY
    else {
      require PATH_LIB . "LIB-database.php";
      $DB = new DB();
      $user = $DB->fetch(
        "SELECT * FROM `users` WHERE `user_email`=?", [$_POST["email"]]
      );
      $pass = is_array($user);
      if ($pass) { $pass = password_verify($_POST["password"], $user["user_password"]); }
      if ($pass) {
        $_SESSION["user"] = [];
        foreach ($user as $k=>$v) { if ($k!="user_password") {
          $_SESSION["user"][$k] = $v;
        }}
        exit("OK");
      }
      exit("Invalid user/password");
    }

  // (C) LOGOUT
  case "out":
    unset($_SESSION["user"]);
    exit("OK");
}}
