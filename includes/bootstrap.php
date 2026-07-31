<?php
/**
 * Shared bootstrap: session, DB, helpers.
 * Include this instead of raw conn.php + session_start().
 */
if (defined('EMS_BOOTSTRAPPED')) {
    return;
}
define('EMS_BOOTSTRAPPED', true);

$config = require dirname(__DIR__) . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect(
    $config['db_host'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_name']
);

if (!$conn) {
    http_response_code(500);
    die('Database connection failed. Check config.php / environment settings.');
}

mysqli_set_charset($conn, $config['db_charset']);

/**
 * Escape for HTML output (XSS prevention).
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash_set(string $type, string $message): void
{
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (empty($_SESSION['_flash'])) {
        return null;
    }
    $f = $_SESSION['_flash'];
    unset($_SESSION['_flash']);
    return $f;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!$token || empty($_SESSION['_csrf']) || !hash_equals($_SESSION['_csrf'], $token)) {
        http_response_code(403);
        die('Invalid security token. Please go back and try again.');
    }
}

/**
 * Hash password for storage.
 */
function ems_hash_password(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password. Supports legacy plain-text DB values and auto-upgrades them.
 * Returns true on match. If $upgradeSql + params provided and legacy match, rehashes.
 *
 * @param mysqli|null $conn
 * @param string|null $upgradeSql  e.g. "UPDATE tbl_customer SET password=? WHERE cust_id=?"
 * @param array|null  $upgradeTypesAndParams  [types, ...params without hash] hash is bound first
 */
function ems_verify_password(
    string $plain,
    string $stored,
    ?mysqli $conn = null,
    ?string $upgradeSql = null,
    ?string $idTypes = null,
    ...$idParams
): bool {
    $ok = false;
    $needsUpgrade = false;

    $isHashed = $stored !== '' && (
        strpos($stored, '$2y$') === 0 ||
        strpos($stored, '$2a$') === 0 ||
        strpos($stored, '$argon2') === 0
    );

    if ($isHashed) {
        $ok = password_verify($plain, $stored);
    } else {
        // Legacy plain-text (existing seed data)
        $ok = hash_equals((string) $stored, $plain);
        $needsUpgrade = $ok;
    }

    if ($ok && $needsUpgrade && $conn && $upgradeSql && $idTypes !== null) {
        $hash = ems_hash_password($plain);
        $types = 's' . $idTypes;
        $stmt = $conn->prepare($upgradeSql);
        if ($stmt) {
            $bind = array_merge([$types, $hash], $idParams);
            $refs = [];
            foreach ($bind as $k => $v) {
                $refs[$k] = &$bind[$k];
            }
            call_user_func_array([$stmt, 'bind_param'], $refs);
            $stmt->execute();
            $stmt->close();
        }
    }

    return $ok;
}

function require_admin(): void
{
    if (empty($_SESSION['admin'])) {
        redirect('login.php');
    }
}

function require_customer(): void
{
    if (empty($_SESSION['customer'])) {
        flash_set('error', 'Please login to continue.');
        redirect('login.php');
    }
}

function require_organizer(): void
{
    if (empty($_SESSION['organizer'])) {
        flash_set('error', 'Please login as organizer to continue.');
        redirect('login.php');
    }
}

function post_string(string $key, int $max = 500): string
{
    $v = trim((string) ($_POST[$key] ?? ''));
    if (mb_strlen($v) > $max) {
        $v = mb_substr($v, 0, $max);
    }
    return $v;
}

function get_int(string $key, int $default = 0): int
{
    return isset($_GET[$key]) ? (int) $_GET[$key] : $default;
}

function post_int(string $key, int $default = 0): int
{
    return isset($_POST[$key]) ? (int) $_POST[$key] : $default;
}

/**
 * Send HTML email via PHPMailer if SMTP is configured.
 * Returns [bool success, string message]
 */
function ems_mail_log_dir(): string
{
    $dir = dirname(__DIR__) . '/storage/mail_log';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir;
}

function ems_admin_inbox_path(): string
{
    $dir = dirname(__DIR__) . '/storage';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir . '/admin_inbox.json';
}

// function ems_admin_notify(string $title, string $bodyHtml): void
// {
//     global $config;
//     $path = ems_admin_inbox_path();
//     $items = [];
//     if (is_file($path)) {
//         $raw = file_get_contents($path);
//         $decoded = json_decode($raw ?: '[]', true);
//         if (is_array($decoded)) {
//             $items = $decoded;
//         }
//     }
//     array_unshift($items, [
//         'id' => bin2hex(random_bytes(8)),
//         'title' => $title,
//         'body' => $bodyHtml,
//         'created_at' => date('Y-m-d H:i:s'),
//         'read' => false,
//     ]);
//     $items = array_slice($items, 0, 100);
//     file_put_contents($path, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

//     $adminEmail = trim((string)($config['admin_notify_email'] ?? ''));
//     if ($adminEmail !== '') {
//         ems_send_mail($adminEmail, $title, $bodyHtml);
//     }
// }

function ems_admin_notify(string $title, string $bodyHtml): void
{
    $path = ems_admin_inbox_path();
    $items = [];

    if (is_file($path)) {
        $raw = file_get_contents($path);
        $decoded = json_decode($raw ?: '[]', true);

        if (is_array($decoded)) {
            $items = $decoded;
        }
    }

    array_unshift($items, [
        'id' => bin2hex(random_bytes(8)),
        'title' => $title,
        'body' => $bodyHtml,
        'created_at' => date('Y-m-d H:i:s'),
        'read' => false,
    ]);

    // Keep only latest 100 admin notifications
    $items = array_slice($items, 0, 100);

    file_put_contents(
        $path,
        json_encode(
            $items,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        )
    );
}

function ems_send_mail(string $to, string $subject, string $htmlBody): array
{
    global $config;

    // Always keep a local copy for demo / debugging
    $logFile = ems_mail_log_dir() . '/' . date('Ymd_His') . '_' . preg_replace('/[^a-zA-Z0-9_-]+/', '_', $to) . '.html';
    $logHtml = '<h3>' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</h3>'
        . '<p><strong>To:</strong> ' . htmlspecialchars($to, ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><strong>Time:</strong> ' . date('Y-m-d H:i:s') . '</p><hr>' . $htmlBody;
    @file_put_contents($logFile, $logHtml);

    if (empty($config['smtp_user']) || empty($config['smtp_pass'])) {
        // Local mode: treat as "sent" via mail log so flows continue
        return [true, 'Email saved locally (SMTP not configured). Check storage/mail_log/'];
    }

    $autoload = dirname(__DIR__) . '/vendor/autoload.php';
    if (!is_file($autoload)) {
        return [false, 'PHPMailer not installed.'];
    }

    require_once $autoload;

    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        // Fail fast instead of freezing user-facing actions on a bad SMTP connection.
        $mail->Timeout = 5;
        $mail->Timelimit = 5;
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_user'];
        $mail->Password = $config['smtp_pass'];
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];
        $mail->setFrom($config['smtp_from_email'], $config['smtp_from_name']);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->send();
        return [true, 'Email sent.'];
    } catch (Throwable $e) {
        return [false, 'Mail error: ' . $e->getMessage()];
    }
}

function app_url(string $path = ''): string
{
    global $config;
    return rtrim($config['app_url'], '/') . '/' . ltrim($path, '/');
}

function random_token(int $bytes = 16): string
{
    return bin2hex(random_bytes($bytes));
}
