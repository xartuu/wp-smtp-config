<?php
/**
 * SMTP Config
 *
 * @package           SMTP Config
 * @author            Artur Kociszewski
 * @copyright         2023 Artur Kociszewski
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       SMTP Config
 * Plugin URI:        https://github.com/xartuu/wp-smtp-config
 * Description:       Uses SMTP server from WordPress config (works with Bedrock and Wordplate too).
 * Version:           0.1.0
 * Author:            Artur Kociszewski
 * Author URI:        https://arturkociszewski.pl/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       smtp-config
 */

use function Env\env;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Roots\WPConfig\Config;

defined("ABSPATH") or die("Sorry ;(");

// Fetches variables from `.env` file in case of custom instance (eg. Bedrock or Wordplate)
if (class_exists(Config::class) && function_exists('Env\env')) {
    env("SMTP_AUTO_TLS") === null || Config::define("SMTP_AUTO_TLS", env("SMTP_AUTO_TLS"));
    env("SMTP_AUTH") === null || Config::define("SMTP_AUTH", env("SMTP_AUTH"));
    env("SMTP_DEBUG") === null || Config::define("SMTP_DEBUG", env("SMTP_DEBUG"));
    env("SMTP_SECURE") === null || Config::define("SMTP_SECURE", env("SMTP_SECURE"));
    env("SMTP_HOST") === null || Config::define("SMTP_HOST", env("SMTP_HOST"));
    env("SMTP_PORT") === null || Config::define("SMTP_PORT", env("SMTP_PORT"));
    env("SMTP_USER") === null || Config::define("SMTP_USER", env("SMTP_USER"));
    env("SMTP_PASS") === null || Config::define("SMTP_PASS", env("SMTP_PASS"));
    env("SMTP_FROM") === null || Config::define("SMTP_FROM", env("SMTP_FROM"));
    env("SMTP_NAME") === null || Config::define("SMTP_NAME", env("SMTP_NAME"));
    Config::apply();
}

// Defines default values
defined("SMTP_AUTO_TLS") || define("SMTP_AUTO_TLS", false);
defined("SMTP_AUTH") || define("SMTP_AUTH", true);
defined("SMTP_DEBUG") || define("SMTP_DEBUG", false);
defined("SMTP_SECURE") || define("SMTP_SECURE", "tls");
defined("SMTP_PORT") || define("SMTP_PORT", 587);
defined("SMTP_FROM") || define("SMTP_FROM", "hello@example.com");
defined("SMTP_NAME") || define("SMTP_NAME", "Example");

add_action("phpmailer_init", function (PHPMailer $phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->SMTPAutoTLS = SMTP_AUTO_TLS;
    $phpmailer->SMTPAuth = SMTP_AUTH;
    $phpmailer->SMTPDebug = SMTP_DEBUG ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
    $phpmailer->SMTPSecure = SMTP_SECURE;
    $phpmailer->Host = SMTP_HOST;
    $phpmailer->Port = SMTP_PORT;
    $phpmailer->Username = SMTP_USER;
    $phpmailer->Password = SMTP_PASS;
    $phpmailer->From = SMTP_FROM;
    $phpmailer->FromName = SMTP_NAME;
    return $phpmailer;
});

add_filter("wp_mail_from", fn() => SMTP_FROM);
add_filter("wp_mail_from_name", fn() => SMTP_NAME);
