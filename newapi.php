<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

define('GUSER', 'no.reply.checkchecker0@gmail.com'); // GMail username
define('GPWD', 'F429D420086F70A3B72B10BFF0446D87DEA2A385AD9EA49A135E547414D91CC2'); // GMail password


function sendConfirmation($token, $email, $username) {
    $from = "no-reply@checkchecker.com";
    $to   = $email;
    $subject = "Confirm registration";
    $body = '
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=US-ASCII">
    <meta name="viewport" content="width=device-width">

</head>

<body style="-moz-box-sizing: border-box; -ms-text-size-adjust: 100%; -webkit-box-sizing: border-box; -webkit-text-size-adjust: 100%; box-sizing: border-box; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 22px; margin: 0; min-width: 100%; padding: 0; text-align: left; width: 100% !important">
<style type="text/css">
    body {
        width: 100% !important;
        min-width: 100%;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        margin: 0;
        padding: 0;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }

    .ExternalClass {
        width: 100%;
    }

    .ExternalClass {
        line-height: 100%;
    }

    #backgroundTable {
        margin: 0;
        padding: 0;
        width: 100% !important;
        line-height: 100% !important;
    }

    img {
        outline: none;
        text-decoration: none;
        -ms-interpolation-mode: bicubic;
        width: auto;
        max-width: 100%;
        clear: both;
        display: block;
    }

    body {
        color: #1C232B;
        font-family: Helvetica, Arial, sans-serif;
        font-weight: normal;
        padding: 0;
        margin: 0;
        text-align: left;
        line-height: 1.3;
    }

    body {
        font-size: 16px;
        line-height: 1.3;
    }

    a:hover {
        color: #1f54ed;
    }

    a:active {
        color: #1f54ed;
    }

    a:visited {
        color: #4E78F1;
    }

    h1 a:visited {
        color: #4E78F1;
    }

    h2 a:visited {
        color: #4E78F1;
    }

    h3 a:visited {
        color: #4E78F1;
    }

    h4 a:visited {
        color: #4E78F1;
    }

    h5 a:visited {
        color: #4E78F1;
    }

    h6 a:visited {
        color: #4E78F1;
    }

    table.button:hover table tr td a {
        color: #FFFFFF;
    }

    table.button:active table tr td a {
        color: #FFFFFF;
    }

    table.button table tr td a:visited {
        color: #FFFFFF;
    }

    table.button.tiny:hover table tr td a {
        color: #FFFFFF;
    }

    table.button.tiny:active table tr td a {
        color: #FFFFFF;
    }

    table.button.tiny table tr td a:visited {
        color: #FFFFFF;
    }

    table.button.small:hover table tr td a {
        color: #FFFFFF;
    }

    table.button.small:active table tr td a {
        color: #FFFFFF;
    }

    table.button.small table tr td a:visited {
        color: #FFFFFF;
    }

    table.button.large:hover table tr td a {
        color: #FFFFFF;
    }

    table.button.large:active table tr td a {
        color: #FFFFFF;
    }

    table.button.large table tr td a:visited {
        color: #FFFFFF;
    }

    table.button:hover table td {
        background: #1f54ed;
        color: #FFFFFF;
    }

    table.button:visited table td {
        background: #1f54ed;
        color: #FFFFFF;
    }

    table.button:active table td {
        background: #1f54ed;
        color: #FFFFFF;
    }

    table.button:hover table a {
        border: 0 solid #1f54ed;
    }

    table.button:visited table a {
        border: 0 solid #1f54ed;
    }

    table.button:active table a {
        border: 0 solid #1f54ed;
    }

    table.button.secondary:hover table td {
        background: #fefefe;
        color: #FFFFFF;
    }

    table.button.secondary:hover table a {
        border: 0 solid #fefefe;
    }

    table.button.secondary:hover table td a {
        color: #FFFFFF;
    }

    table.button.secondary:active table td a {
        color: #FFFFFF;
    }

    table.button.secondary table td a:visited {
        color: #FFFFFF;
    }

    table.button.success:hover table td {
        background: #009482;
    }

    table.button.success:hover table a {
        border: 0 solid #009482;
    }

    table.button.alert:hover table td {
        background: #ff6131;
    }

    table.button.alert:hover table a {
        border: 0 solid #ff6131;
    }

    table.button.warning:hover table td {
        background: #fcae1a;
    }

    table.button.warning:hover table a {
        border: 0px solid #fcae1a;
    }

    .thumbnail:hover {
        box-shadow: 0 0 6px 1px rgba(78, 120, 241, 0.5);
    }

    .thumbnail:focus {
        box-shadow: 0 0 6px 1px rgba(78, 120, 241, 0.5);
    }

    body.outlook p {
        display: inline !important;
    }

    body {
        font-weight: normal;
        font-size: 16px;
        line-height: 22px;
    }

    @media only screen and (max-width: 596px) {
        .small-float-center {
            margin: 0 auto !important;
            float: none !important;
            text-align: center !important;
        }

        .small-text-center {
            text-align: center !important;
        }

        .small-text-left {
            text-align: left !important;
        }

        .small-text-right {
            text-align: right !important;
        }

        .hide-for-large {
            display: block !important;
            width: auto !important;
            overflow: visible !important;
            max-height: none !important;
            font-size: inherit !important;
            line-height: inherit !important;
        }

        table.body table.container .hide-for-large {
            display: table !important;
            width: 100% !important;
        }

        table.body table.container .row.hide-for-large {
            display: table !important;
            width: 100% !important;
        }

        table.body table.container .callout-inner.hide-for-large {
            display: table-cell !important;
            width: 100% !important;
        }

        table.body table.container .show-for-large {
            display: none !important;
            width: 0;
            mso-hide: all;
            overflow: hidden;
        }

        table.body img {
            width: auto;
            height: auto;
        }

        table.body center {
            min-width: 0 !important;
        }

        table.body .container {
            width: 95% !important;
        }

        table.body .columns {
            height: auto !important;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        table.body .column {
            height: auto !important;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        table.body .columns .column {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.body .columns .columns {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.body .column .column {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.body .column .columns {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.body .collapse .columns {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.body .collapse .column {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        td.small-1 {
            display: inline-block !important;
            width: 8.333333% !important;
        }

        th.small-1 {
            display: inline-block !important;
            width: 8.333333% !important;
        }

        td.small-2 {
            display: inline-block !important;
            width: 16.666666% !important;
        }

        th.small-2 {
            display: inline-block !important;
            width: 16.666666% !important;
        }

        td.small-3 {
            display: inline-block !important;
            width: 25% !important;
        }

        th.small-3 {
            display: inline-block !important;
            width: 25% !important;
        }

        td.small-4 {
            display: inline-block !important;
            width: 33.333333% !important;
        }

        th.small-4 {
            display: inline-block !important;
            width: 33.333333% !important;
        }

        td.small-5 {
            display: inline-block !important;
            width: 41.666666% !important;
        }

        th.small-5 {
            display: inline-block !important;
            width: 41.666666% !important;
        }

        td.small-6 {
            display: inline-block !important;
            width: 50% !important;
        }

        th.small-6 {
            display: inline-block !important;
            width: 50% !important;
        }

        td.small-7 {
            display: inline-block !important;
            width: 58.333333% !important;
        }

        th.small-7 {
            display: inline-block !important;
            width: 58.333333% !important;
        }

        td.small-8 {
            display: inline-block !important;
            width: 66.666666% !important;
        }

        th.small-8 {
            display: inline-block !important;
            width: 66.666666% !important;
        }

        td.small-9 {
            display: inline-block !important;
            width: 75% !important;
        }

        th.small-9 {
            display: inline-block !important;
            width: 75% !important;
        }

        td.small-10 {
            display: inline-block !important;
            width: 83.333333% !important;
        }

        th.small-10 {
            display: inline-block !important;
            width: 83.333333% !important;
        }

        td.small-11 {
            display: inline-block !important;
            width: 91.666666% !important;
        }

        th.small-11 {
            display: inline-block !important;
            width: 91.666666% !important;
        }

        td.small-12 {
            display: inline-block !important;
            width: 100% !important;
        }

        th.small-12 {
            display: inline-block !important;
            width: 100% !important;
        }

        .columns td.small-12 {
            display: block !important;
            width: 100% !important;
        }

        .column td.small-12 {
            display: block !important;
            width: 100% !important;
        }

        .columns th.small-12 {
            display: block !important;
            width: 100% !important;
        }

        .column th.small-12 {
            display: block !important;
            width: 100% !important;
        }

        table.body td.small-offset-1 {
            margin-left: 8.333333% !important;
        }

        table.body th.small-offset-1 {
            margin-left: 8.333333% !important;
        }

        table.body td.small-offset-2 {
            margin-left: 16.666666% !important;
        }

        table.body th.small-offset-2 {
            margin-left: 16.666666% !important;
        }

        table.body td.small-offset-3 {
            margin-left: 25% !important;
        }

        table.body th.small-offset-3 {
            margin-left: 25% !important;
        }

        table.body td.small-offset-4 {
            margin-left: 33.333333% !important;
        }

        table.body th.small-offset-4 {
            margin-left: 33.333333% !important;
        }

        table.body td.small-offset-5 {
            margin-left: 41.666666% !important;
        }

        table.body th.small-offset-5 {
            margin-left: 41.666666% !important;
        }

        table.body td.small-offset-6 {
            margin-left: 50% !important;
        }

        table.body th.small-offset-6 {
            margin-left: 50% !important;
        }

        table.body td.small-offset-7 {
            margin-left: 58.333333% !important;
        }

        table.body th.small-offset-7 {
            margin-left: 58.333333% !important;
        }

        table.body td.small-offset-8 {
            margin-left: 66.666666% !important;
        }

        table.body th.small-offset-8 {
            margin-left: 66.666666% !important;
        }

        table.body td.small-offset-9 {
            margin-left: 75% !important;
        }

        table.body th.small-offset-9 {
            margin-left: 75% !important;
        }

        table.body td.small-offset-10 {
            margin-left: 83.333333% !important;
        }

        table.body th.small-offset-10 {
            margin-left: 83.333333% !important;
        }

        table.body td.small-offset-11 {
            margin-left: 91.666666% !important;
        }

        table.body th.small-offset-11 {
            margin-left: 91.666666% !important;
        }

        table.body table.columns td.expander {
            display: none !important;
        }

        table.body table.columns th.expander {
            display: none !important;
        }

        table.body .right-text-pad {
            padding-left: 10px !important;
        }

        table.body .text-pad-right {
            padding-left: 10px !important;
        }

        table.body .left-text-pad {
            padding-right: 10px !important;
        }

        table.body .text-pad-left {
            padding-right: 10px !important;
        }

        table.menu {
            width: 100% !important;
        }

        table.menu td {
            width: auto !important;
            display: inline-block !important;
        }

        table.menu th {
            width: auto !important;
            display: inline-block !important;
        }

        table.menu.vertical td {
            display: block !important;
        }

        table.menu.vertical th {
            display: block !important;
        }

        table.menu.small-vertical td {
            display: block !important;
        }

        table.menu.small-vertical th {
            display: block !important;
        }

        table.menu[align="center"] {
            width: auto !important;
        }

        table.button.small-expand {
            width: 100% !important;
        }

        table.button.small-expanded {
            width: 100% !important;
        }

        table.button.small-expand table {
            width: 100%;
        }

        table.button.small-expanded table {
            width: 100%;
        }

        table.button.small-expand table a {
            text-align: center !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.button.small-expanded table a {
            text-align: center !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        table.button.small-expand center {
            min-width: 0;
        }

        table.button.small-expanded center {
            min-width: 0;
        }

        table.body .container {
            width: 100% !important;
        }
    }

    @media only screen and (min-width: 732px) {
        table.body table.milkyway-email-card {
            width: 525px !important;
        }

        table.body table.emailer-footer {
            width: 525px !important;
        }
    }

    @media only screen and (max-width: 731px) {
        table.body table.milkyway-email-card {
            width: 320px !important;
        }

        table.body table.emailer-footer {
            width: 320px !important;
        }
    }

    @media only screen and (max-width: 320px) {
        table.body table.milkyway-email-card {
            width: 100% !important;
            border-radius: 0;
        }

        table.body table.emailer-footer {
            width: 100% !important;
            border-radius: 0;
        }
    }

    @media only screen and (max-width: 280px) {
        table.body table.milkyway-email-card .milkyway-content {
            width: 100% !important;
        }
    }

    @media (min-width: 596px) {
        .milkyway-header {
            width: 11%;
        }
    }

    @media (max-width: 596px) {
        .milkyway-header {
            width: 50%;
        }

        .emailer-footer .emailer-border-bottom {
            border-bottom: 0.5px solid #E2E5E7;
        }

        .emailer-footer .make-you-smile {
            margin-top: 24px;
        }

        .emailer-footer .make-you-smile .email-tag-line {
            width: 80%;
            position: relative;
            left: 10%;
        }

        .emailer-footer .make-you-smile .universe-address {
            margin-bottom: 10px !important;
        }

        .emailer-footer .make-you-smile .email-tag-line {
            margin-bottom: 10px !important;
        }

        .have-questions-text {
            width: 70%;
        }

        .hide-on-small {
            display: none;
        }

        .product-card-stacked-row .thumbnail-image {
            max-width: 32% !important;
        }

        .product-card-stacked-row .thumbnail-content p {
            width: 64%;
        }

        .welcome-subcontent {
            text-align: left;
            margin: 20px 0 10px;
        }

        .milkyway-title {
            padding: 16px;
        }

        .meta-data {
            text-align: center;
        }

        .label {
            text-align: center;
        }

        .welcome-email .wavey-background-subcontent {
            width: calc(100% - 32px);
        }
    }

    @media (min-width: 597px) {
        .emailer-footer .show-on-mobile {
            display: none;
        }

        .emailer-footer .emailer-border-bottom {
            border-bottom: none;
        }

        .have-questions-text {
            border-bottom: none;
        }

        .hide-on-large {
            display: none;
        }

        .milkyway-title {
            padding: 55px 55px 16px;
        }
    }

    @media only screen and (max-width: 290px) {
        table.container.your-tickets .tickets-container {
            width: 100%;
        }
    }
</style>
<table class="body" data-made-with-foundation=""
       style="background: #EBFFE6; border-collapse: collapse; border-spacing: 0; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; height: 100%; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; width: 100%"
       bgcolor="#FAFAFA">
    <tbody>
    <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
        <td class="center" align="center" valign="top"
            style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word">
            <center style="min-width: 580px; width: 100%">
                <table class=" spacer  float-center" align="center"
                       style="border-collapse: collapse; border-spacing: 0; float: none; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; width: 100%">
                    <tbody>
                    <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                        <td height="20px"
                            style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; hyphens: auto; line-height: 20px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                            align="left" valign="top">&nbsp;
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="header-spacer spacer  float-center" align="center"
                       style="border-collapse: collapse; border-spacing: 0; float: none; line-height: 60px; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; width: 100%">
                    <tbody>
                    <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                        <td height="16px"
                            style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 16px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                            align="left" valign="top">&nbsp;
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div class="milkyway-header float-center" align="center">
                    <img src="cid:logo"
                         style="-ms-interpolation-mode: bicubic; clear: both; display: block; max-width: 100%; outline: none; text-decoration: none; width: auto">
                </div>
                <table class="header-spacer-bottom spacer  float-center" align="center"
                       style="border-collapse: collapse; border-spacing: 0; float: none; line-height: 30px; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; width: 100%">
                    <tbody>
                    <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                        <td height="16px"
                            style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 16px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                            align="left" valign="top">&nbsp;
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="milkyway-email-card container float-center" align="center"
                       style="background: #FFFFFF; border-collapse: collapse; border-radius: 6px; border-spacing: 0; box-shadow: 0 1px 8px 0 rgba(28,35,43,0.15); float: none; margin: 0 auto; overflow: hidden; padding: 0; text-align: center; vertical-align: top; width: 580px"
                       bgcolor="#FFFFFF">
                    <tbody>
                    <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                        <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                            align="left" valign="top">

                            <table class="milkyway-content confirmation-instructions container" align="center"
                                   style="background: #FFFFFF; border-collapse: collapse; border-spacing: 0; hyphens: none; margin: auto; max-width: 100%; padding: 0; text-align: inherit; vertical-align: top; width: 280px !important"
                                   bgcolor="#FFFFFF">
                                <tbody>
                                <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                    <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                        align="left" valign="top">
                                        <table class=" spacer "
                                               style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <td height="30px"
                                                    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 30px; font-weight: normal; hyphens: auto; line-height: 30px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                                    align="left" valign="top">&nbsp;
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class=" row"
                                               style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <th class=" small-12 large-12 columns first last"
                                                    style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; text-align: left; width: 564px"
                                                    align="left">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                                        <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top"
                                                            align="left">
                                                            <th style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left"
                                                                align="left">
                                                                <center style="min-width: 0; width: 100%">
                                                                    <img width="250"
                                                                         src="cid:dog"                                                               align="center"
                                                                         class=" float-center float-center"
                                                                         style="-ms-interpolation-mode: bicubic; clear: both; display: block; float: none; margin: 0 auto; max-width: 100%; outline: none; text-align: center; text-decoration: none; width: auto">
                                                                </center>
                                                            </th>
                                                            <th class="expander"
                                                                style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; visibility: hidden; width: 0"
                                                                align="left"></th>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class=" spacer "
                                               style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <td height="30px"
                                                    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 30px; font-weight: normal; hyphens: auto; line-height: 30px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                                    align="left" valign="top">&nbsp;
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class=" row"
                                               style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <th class="header-padding small-12 large-12 columns first last"
                                                    style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; text-align: left; width: 564px"
                                                    align="left">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                                        <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top"
                                                            align="left">
                                                            <th style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left"
                                                                align="left">
                                                                <h1 class="welcome-header"
                                                                    style="color: inherit; font-family: Helvetica, Arial, sans-serif; font-size: 24px; font-weight: 600; hyphens: none; line-height: 30px; margin: 0 0 24px; padding: 0; text-align: left; width: 100%; word-wrap: normal"
                                                                    align="left">
                                                                    You\'re ready to go!
                                                                </h1>
                                                            </th>
                                                            <th class="expander"
                                                                style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; visibility: hidden; width: 0"
                                                                align="left"></th>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class=" row"
                                               style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <th class="body-content small-12 large-12 columns first last"
                                                    style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; text-align: left; width: 564px"
                                                    align="left">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                                        <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top"
                                                            align="left">
                                                            <th style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left"
                                                                align="left">
                                                                <h2 class="welcome-subcontent"
                                                                    style="color: #6F7881; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 300; line-height: 22px; margin: 0; padding: 0; text-align: left; width: 100%; word-wrap: normal"
                                                                    align="left">
                                                                    Hey '.$username.',
                                                                </h2>
                                                            </th>
                                                            <th class="expander"
                                                                style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; visibility: hidden; width: 0"
                                                                align="left"></th>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class=" row"
                                               style="border-collapse: collapse; border-spacing: 0; display: table; padding: 0; position: relative; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <th class="body-content-end small-12 large-12 columns first last"
                                                    style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; text-align: left; width: 564px"
                                                    align="left">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                                        <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top"
                                                            align="left">
                                                            <th style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left"
                                                                align="left">
                                                                <h2 class="welcome-subcontent"
                                                                    style="color: #6F7881; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 300; line-height: 22px; margin: 0; padding: 0; text-align: left; width: 100%; word-wrap: normal"
                                                                    align="left">
                                                                    We\'ve finished setting up your CheckChecker account.
                                                                    Just confirm your email to get started!
                                                                </h2>
                                                            </th>
                                                            <th class="expander"
                                                                style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; visibility: hidden; width: 0"
                                                                align="left"></th>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class=" spacer "
                                               style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <td height="30px"
                                                    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 30px; font-weight: normal; hyphens: auto; line-height: 30px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                                    align="left" valign="top">&nbsp;
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <table class="milkyway-content row"
                                               style="border-collapse: collapse; border-spacing: 0; display: table; hyphens: none; margin: auto; max-width: 100%; padding: 0; position: relative; text-align: left; vertical-align: top; width: 280px !important">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <th class="milkyway-padding small-12 large-12 columns first last"
                                                    valign="middle"
                                                    style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0 auto; padding: 0; text-align: left; width: 564px"
                                                    align="left">
                                                    <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                                        <tbody>
                                                        <tr style="padding: 0; text-align: left; vertical-align: top"
                                                            align="left">
                                                            <th style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left"
                                                                align="left">
                                                                <table class="cta-text primary radius expanded button"
                                                                       style="border-collapse: collapse; border-spacing: 0; font-size: 14px; font-weight: 400; line-height: 0; margin: 0 0 16px; padding: 0; text-align: left; vertical-align: top; width: 100% !important">
                                                                    <tbody>
                                                                    <tr style="padding: 0; text-align: left; vertical-align: top"
                                                                        align="left">
                                                                        <td style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                                                            align="left" valign="top">
                                                                            <table style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                                                                <tbody>
                                                                                <tr style="padding: 0; text-align: left; vertical-align: top"
                                                                                    align="left">
                                                                                    <td style="-moz-hyphens: auto; -webkit-hyphens: auto; background: #4E78F1; border: 2px none #4e78f1; border-collapse: collapse !important; border-radius: 6px; color: #FFFFFF; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; hyphens: auto; line-height: 1.3; margin: 0; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                                                                        align="left" bgcolor="#4E78F1"
                                                                                        valign="top">
                                                                                        <a href="http://3.89.196.174/checkchecker/newapi.php?operation=verifyUser&token='.$token.'"
                                                                                           style="border: 0 solid #4e78f1; border-radius: 6px; color: #FFFFFF; display: inline-block; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; line-height: 1.3; margin: 0; padding: 13px 0; text-align: center; text-decoration: none; width: 100%"
                                                                                           target="_blank">
                                                                                            <p class="text-center"
                                                                                               style="color: white; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 300; letter-spacing: 1px; line-height: 1.3; margin: 0; padding: 0; text-align: center"
                                                                                               align="center">
                                                                                                Confirm email
                                                                                            </p>
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </th>
                                                            <th class="expander"
                                                                style="color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: normal; line-height: 1.3; margin: 0; padding: 0; text-align: left; visibility: hidden; width: 0"
                                                                align="left"></th>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </th>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <table class=" spacer "
                                               style="border-collapse: collapse; border-spacing: 0; padding: 0; text-align: left; vertical-align: top; width: 100%">
                                            <tbody>
                                            <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                                                <td height="10px"
                                                    style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 10px; font-weight: normal; hyphens: auto; line-height: 10px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                                                    align="left" valign="top">&nbsp;
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class=" spacer  float-center" align="center"
                       style="border-collapse: collapse; border-spacing: 0; float: none; margin: 0 auto; padding: 0; text-align: center; vertical-align: top; width: 100%">
                    <tbody>
                    <tr style="padding: 0; text-align: left; vertical-align: top" align="left">
                        <td height="20px"
                            style="-moz-hyphens: auto; -webkit-hyphens: auto; border-collapse: collapse !important; color: #1C232B; font-family: Helvetica, Arial, sans-serif; font-size: 20px; font-weight: normal; hyphens: auto; line-height: 20px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-align: left; vertical-align: top; word-wrap: break-word"
                            align="left" valign="top">&nbsp;
                        </td>
                    </tr>
                    </tbody>
                </table>

            </center>
        </td>
    </tr>
    </tbody>
</table>

</body>
';
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->isHTML(true);
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = GUSER;
    $mail->Password = GPWD;
    $mail->SetFrom($from, "CheckChecker");
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    $mail->AddEmbeddedImage('download.jpg','dog','download.jpg');
    $mail->AddEmbeddedImage('karkar (1).jpg','logo','karkar (1).jpg');
    if (!$mail->send()) {
        throw new \Exception($mail->ErrorInfo . "(" . $email . ")");
    }
}


$dbc = mysqli_connect("localhost", "checkchecker", "JJWMdF6riGuHDoVr", "checkchecker") or die("failed to connect to db");
mysqli_set_charset($dbc, 'utf8');

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$operations = [];

function executeInsert($dbc, $table, $args, $resolve, $rejectMYSQLError) {
    $query = "INSERT INTO `$table` (";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "`".$k."`");
    }
    $query .= implode(", ", $array);
    $query .= ") VALUES (";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "'".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(", ", $array);
    $query .= ")";
    $result = mysqli_query($dbc, $query);
    if ($result) $resolve($result, null, null, mysqli_insert_id($dbc)); else $rejectMYSQLError(mysqli_error($dbc));
}

function executeDelete($dbc, $table, $args, $resolve, $rejectMYSQLError) {
    $query = "DELETE FROM `$table` WHERE ";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "`".$k."` = '".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(" AND ", $array);
    $result = mysqli_query($dbc, $query);
    if ($result) $resolve($result, null); else $rejectMYSQLError(mysqli_error($dbc));
}

function executeUpdate($dbc, $table, $args, $conditions, $resolve, $rejectMYSQLError, $info = null) {
    $query = "UPDATE `$table` SET ";
    $array = [];
    foreach ($args as $k => $v) {
        array_push($array, "`".$k."` = '".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(", ", $array);
    $query .= " WHERE ";
    $array = [];
    foreach ($conditions as $k => $v) {
        array_push($array, "`".$k."` = '".mysqli_real_escape_string($dbc, $v)."'");
    }
    $query .= implode(" AND ", $array);
    $result = mysqli_query($dbc, $query);
    if ($result) $resolve($result, null, $info); else $rejectMYSQLError(mysqli_error($dbc));
}

$operations = [
    'get' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['table'])) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $query['table'] . "`");
            if ($result) $resolve($result, $query); else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('table');
    },
    'getAll' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        $results = [];
        foreach (
            ['labels', 'groups', 'users', 'accounts', 'categories', 'checks', 'products', 'groups-users_connections', 'operations', 'roles', 'operations-categories_connections', 'currencies']
            as $value) {
            $result = mysqli_query($dbc, "SELECT * FROM `" . $value . "`");
            if ($result) $results[$value] = $result; else $rejectMYSQLError(mysqli_error($dbc));
        }
        $resolve($results, $query);
    },
    'createUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['username']) && isset($query['password']) && isset($query['email'])) {
            $args = [
                "username" => $query['username'],
                "password" => $query['password'],
                "email" => $query['email'],
            ];
            if (isset($query['avatar'])) {
                $args['avatar'] = $query['avatar'];
            }
            if (isset($query['average_income'])) {
                $args['average_income'] = $query['average_income'];
            }
            executeInsert($dbc, "users", $args, function(){}, $rejectMYSQLError);
            $result = mysqli_query($dbc, "SELECT user_id, email FROM users WHERE `username` = '". mysqli_real_escape_string($dbc, $query['username']) . "'");
            if ($result) {
                $arr = mysqli_fetch_array($result);
                $id = $arr['user_id'];
                $email = $arr['email'];
                $token = generateRandomString(20);

                sendConfirmation($token, $email, $query['username']);

                mysqli_query($dbc, "INSERT INTO tokens (user_id, `value`) VALUES ($id, '$token')");
                if ($result) $resolve($result, $query, null, mysqli_insert_id($dbc)); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('username', 'password', 'email');
    },
    'changeUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['username'])) {
                executeUpdate($dbc, 'users', [
                    'username' => $query['username']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['password'])) {
                executeUpdate($dbc, 'users', [
                    'password' => $query['password']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['avatar']) && isset($query['extension'])) {
                $newfilename = time() . "." . $query['extension'];
                $content = base64_decode($query['avatar']);
                $file = fopen("images/" . $newfilename, "wb");
                fwrite($file, $content);
                fclose($file);
                $result = mysqli_query($dbc,
                    "SELECT avatar FROM users WHERE user_id = " . mysqli_real_escape_string($dbc, $query['id']));
                if ($result) {
                    $filename = mysqli_fetch_array($result)['avatar'];
                    if ($filename != "unknown.png") unlink("images/" . $filename);
                    executeUpdate($dbc, 'users', [
                        'avatar' => $newfilename
                    ], [
                        'user_id' => $query['id']
                    ], $resolve, $rejectMYSQLError, $query['avatar']);
                } else $rejectMYSQLError(mysqli_error($dbc));
            } else if (isset($query['avatar'])) {
                executeUpdate($dbc, 'users', [
                    'avatar' => $query['avatar']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['email'])) {
                executeUpdate($dbc, 'users', [
                    'email' => $query['email']
                ], [
                    'user_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError('username', 'password', 'avatar');
        } else $rejectArgumentError('id');
    },
    'verifyUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['token'])) {
            $result = mysqli_query($dbc, "SELECT user_id FROM tokens WHERE `value` = '". $query['token'] ."'");
            if ($result) {
                if ($row = mysqli_fetch_array($result)) {
                    $id = $row['user_id'];
                    if (!(mysqli_query($dbc, "UPDATE users SET approved = 1 WHERE user_id = ".$id) && mysqli_query($dbc, "DELETE FROM tokens WHERE user_id = ".$id))) $rejectMYSQLError(mysqli_error($dbc)); else $resolve(true, $query);
                    echo "<script>window.history.back();window.close()</script>";
                } else $rejectArgumentError('token');
            } else $rejectMYSQLError(mysqli_error($dbc));
        } else $rejectArgumentError('token');
    },
    'createAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['name']) && isset($query['initial_amount'])) {
            $args = [
                "user_id" => $query["user_id"],
                "account_name" => $query["name"],
                "initial_amount" => $query["initial_amount"]
            ];
            if (isset($query['currency_id'])) $args['currency_id'] = $query['currency_id'];
            executeInsert($dbc, 'accounts', $args, $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'accounts', [
                    'account_name' => $query['name']
                ], [
                    'account_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['initial_amount'])) {
                executeUpdate($dbc, 'accounts', [
                    'initial_amount' => $query['initial_amount']
                ], [
                    'account_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name", "initial_amount");
        } else $rejectArgumentError('id');
    },
    'createGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name'])) {
            executeInsert($dbc, 'groups', [
                'group_name' => $query['name']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("name");
    },
    'changeGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'groups', [
                    'group_name' => $query['name']
                ], [
                    'group_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    "createRole" => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['permissions'])) {
            executeInsert($dbc, 'roles', [
                'role_name' => $query['name'],
                'role_permissions' => $query['permissions']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("name", "permissions");
    },
    'changeRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'roles', [
                    'role_name' => $query['name']
                ], [
                    'role_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['permissions'])) {
                executeUpdate($dbc, 'roles', [
                    'role_permissions' => $query['permissions']
                ], [
                    'role_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name", "permissions");
        } else $rejectArgumentError('id');
    },
    'addUserToGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            executeInsert($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id");
    },
    'addRoleToUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            executeInsert($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id'],
                'role_id' => $query['role_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteRoleFromUser' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            executeDelete($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id'],
                'role_id' => $query['role_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'changeUserRole' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id']) && isset($query['role_id'])) {
            executeUpdate($dbc, 'groups-users_connections', [
                'role_id' => $query['role_id']
            ], [
                'group_id' => $query['group_id'],
                'user_id' => $query['user_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', "group_id", 'role_id');
    },
    'deleteUserFromGroup' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['user_id']) && isset($query['group_id'])) {
            executeDelete($dbc, 'groups-users_connections', [
                'user_id' => $query['user_id'],
                'group_id' => $query['group_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('user_id', 'group_id');
    },
    'createOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['account_id'])) {
            $args = [
                "operation_name" => $query["name"],
                "account_id" => $query['account_id']
            ];
            if (isset($query['value'])) $args['value'] = $query['value'];
            executeInsert($dbc, "operations", $args, $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('name', 'account_id');
    },
    'changeOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'operations', [
                    'operation_name' => $query['name']
                ], [
                    'operation_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['value'])) {
                executeUpdate($dbc, 'operations', [
                    'value' => $query['value']
                ], [
                    'operation_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['timestamp'])) {
                $result = mysqli_query($dbc, "UPDATE operations SET added_timestamp = FROM_UNIXTIME(".$query['timestamp'].") WHERE operation_id = ".$query['id']);
                if ($result) $resolve($result, $query, null); else $rejectMYSQLError(mysqli_error($dbc));
            } else $rejectArgumentError("name", "value");
        } else $rejectArgumentError('id');
    },
    'createCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['name']) && isset($query['user_id'])) {
            $args = [
                'category_name' => $query['name'],
                'user_id' => $query['user_id']
            ];
            if (isset($query['label_id'])) $args['label_id'] = $query['label_id'];
            executeInsert($dbc, 'categories', $args, $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("name");
    },
    'changeCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'categories', [
                    'category_name' => $query['name']
                ], [
                    'category_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['label_id'])) {
                executeUpdate($dbc, 'categories', [
                    'label_id' => $query['label_id']
                ], [
                    'category_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'addCategoryToOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['category_id']) && isset($query['operation_id'])) {
            executeInsert($dbc, 'operations-categories_connections', [
                'operation_id' => $query['operation_id'],
                'category_id' => $query['category_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError('category_id', 'operation_id');
    },
    'createCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['operation_id']) && isset($query['name'])) {
            executeInsert($dbc, 'checks', [
                'check_name' => $query['name'],
                'operation_id' => $query['operation_id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeCheck' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'checks', [
                    'check_name' => $query['name']
                ], [
                    'check_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name");
        } else $rejectArgumentError('id');
    },
    'createProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['check_id']) && isset($query['name']) && isset($query['price']) && isset($query['amount'])) {
            executeInsert($dbc, 'products', [
                'product_name' => $query['name'],
                'check_id' => $query['check_id'],
                'price' => $query['price'],
                'amount' => $query['amount']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'changeProduct' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['name'])) {
                executeUpdate($dbc, 'products', [
                    'product_name' => $query['name']
                ], [
                    'product_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['price'])) {
                executeUpdate($dbc, 'products', [
                    'price' => $query['price']
                ], [
                    'product_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['amount'])) {
                executeUpdate($dbc, 'products', [
                    'amount' => $query['amount']
                ], [
                    'product_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("name", "price", "amount");
        } else $rejectArgumentError('id');
    },
    'deleteOperation' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            executeDelete($dbc, 'operations', [
                'operation_id' => $query['id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("id");
    },
    'deleteCategory' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            executeDelete($dbc, 'categories', [
                'category_id' => $query['id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("id");
    },
    'deleteAccount' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            executeDelete($dbc, 'accounts', [
                'account_id' => $query['id']
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("user_id", "name", "initial_amount");
    },
    'createLabel' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['type']) && isset($query['r']) && isset($query['g']) && isset($query['b']) && isset($query['alpha'])) {
            executeInsert($dbc, 'labels', [
                'type' => $query['type'],
                'r' => $query['r'],
                'g' => $query['g'],
                'b' => $query['b'],
                'alpha' => $query['alpha'],
            ], $resolve, $rejectMYSQLError);
        } else $rejectArgumentError("type", 'r', 'g', 'b', 'alpha');
    },
    'changeLabel' => function ($resolve, $rejectArgumentError, $rejectMYSQLError, $dbc, $query) {
        if (isset($query['id'])) {
            if (isset($query['type'])) {
                executeUpdate($dbc, 'labels', [
                    'type' => $query['type']
                ], [
                    'label_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else if (isset($query['r']) && isset($query['g']) && isset($query['b']) && isset($query['alpha'])) {
                executeUpdate($dbc, 'labels', [
                    'r' => $query['r'],
                    'g' => $query['g'],
                    'b' => $query['b'],
                    'alpha' => $query['alpha']
                ], [
                    'label_id' => $query['id']
                ], $resolve, $rejectMYSQLError);
            } else $rejectArgumentError("type", 'r', 'g', 'b', 'alpha');
        } else $rejectArgumentError('id');
    }
];

$methods = [$_GET, $_POST];

foreach ($methods as $query) if (isset($query['operation'])) {
    $name = $query['operation'];
    if (isset($operations[$name])) try {
        $operations[$name](
            function ($result, $query = null, $info = null, $lastID = null) {
                $output = [
                    'success' => "true",
                    'result' => null
                ];
                if ($result === true) {
                    $output['result'] = [];
                    $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                    @socket_connect($socket, '127.0.0.1', 8080);
                    $msg = ["type" => "update"];
                    if (($query != null) && isset($query['client_id'])) $msg['client_id'] = $query['client_id'];
                    @socket_write($socket, json_encode($msg));
                } else if (gettype($result) == "array") {
                    foreach ($result as $key => $value) {
                        $toJSON = [];
                        while ($row = mysqli_fetch_array($value)) {
                            array_push($toJSON, $row);
                        }
                        $output['result'][$key] = $toJSON;
                    }
                } else {
                    $toJSON = [];
                    while ($row = mysqli_fetch_array($result)) {

                        array_push($toJSON, $row);
                    }
                    $output['result'] = $toJSON;
                }
                if ($info != null) {
                    $output['info'] = $info;
                }
                if ($lastID != null) {
                    $output['last_id'] = $lastID;
                }
                echo json_encode($output);
            },
            function (...$errors) {
                echo '{"success":"false", "error":"Bad arguments: ' . implode(", ", $errors) . '"}';
            },
            function ($err) {
                echo '{"success":"false", "error":"MYSQL error: ' . $err . '"}';
            },
            $dbc,
            $query
        );
    } catch (\Exception $e) {
        echo '{"success":"false", "error":"'.$e->getMessage().'"}';
    } else echo '{"success":"false", "error":"No such operation exists"}';
}



// dima pidor