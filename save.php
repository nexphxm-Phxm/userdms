<?php
/**
 * Auto DM Message Saver - Web Interface
 * Works with your existing bot.php
 */

// Configuration
$dataFile = __DIR__ . '/data6.json';

// Load current data
function loadBotData($filePath) {
    if (!file_exists($filePath)) {
        return ['auto_dm_messages' => [], 'forwarding_mode' => false];
    }
    $data = json_decode(file_get_contents($filePath), true);
    if (!$data) {
        return ['auto_dm_messages' => [], 'forwarding_mode' => false];
    }
    return $data;
}

function saveBotData($filePath, $data) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

// Handle actions
$botData = loadBotData($dataFile);
$autoDmMessages = $botData['auto_dm_messages'] ?? [];
$forwardingMode = $botData['forwarding_mode'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'toggle_forwarding') {
        $botData['forwarding_mode'] = !$forwardingMode;
        saveBotData($dataFile, $botData);
        $forwardingMode = $botData['forwarding_mode'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if ($action === 'clear_all') {
        $botData['auto_dm_messages'] = [];
        saveBotData($dataFile, $botData);
        $autoDmMessages = [];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if ($action === 'remove' && isset($_POST['index'])) {
        $index = intval($_POST['index']);
        if (isset($botData['auto_dm_messages'][$index])) {
            unset($botData['auto_dm_messages'][$index]);
            $botData['auto_dm_messages'] = array_values($botData['auto_dm_messages']);
            saveBotData($dataFile, $botData);
            $autoDmMessages = $botData['auto_dm_messages'];
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Bot info
$botToken = "8814977950:AAEr7T-rHx3jE8Dj7zEKmsszORCUBy3_vF4";
function checkBotStatus($token) {
    $url = "https://api.telegram.org/bot" . $token . "/getMe";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return ($data && isset($data['ok']) && $data['ok'] === true) ? $data['result']['username'] : false;
}
$botUsername = checkBotStatus($botToken);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💌 Auto DM Saver</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        }
        h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .subtitle {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-bottom: 30px;
        }
        .status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            flex-wrap: wrap;
            gap: 10px;
        }
        .status-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-dot.on {
            background: #00c853;
            box-shadow: 0 0 12px rgba(0, 200, 83, 0.4);
        }
        .status-dot.off {
            background: #ff1744;
            box-shadow: 0 0 12px rgba(255, 23, 68, 0.4);
        }
        .badge {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }
        .msg-list {
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
        }
        .msg-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.06);
            transition: all 0.3s ease;
        }
        .msg-item:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        .msg-info {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }
        .msg-info .type {
            font-weight: 600;
            color: #64b5f6;
        }
        .msg-info .details {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            margin-top: 4px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-success {
            background: #00c853;
            color: #fff;
        }
        .btn-success:hover {
            background: #00a844;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: #ff1744;
            color: #fff;
        }
        .btn-danger:hover {
            background: #d50000;
            transform: translateY(-2px);
        }
        .btn-warning {
            background: #ff9100;
            color: #fff;
        }
        .btn-warning:hover {
            background: #e67a00;
            transform: translateY(-2px);
        }
        .btn-primary {
            background: #1a73e8;
            color: #fff;
        }
        .btn-primary:hover {
            background: #1557b0;
            transform: translateY(-2px);
        }
        .btn-sm {
            padding: 4px 12px;
            font-size: 12px;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.5);
        }
        .empty-state .emoji {
            font-size: 48px;
            margin-bottom: 16px;
        }
        .empty-state h3 {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 8px;
        }
        .instructions {
            background: rgba(255, 255, 255, 0.04);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .instructions h4 {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
            font-size: 14px;
        }
        .instructions ol {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            line-height: 1.8;
            padding-left: 20px;
        }
        .instructions code {
            background: rgba(255, 255, 255, 0.08);
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: #64b5f6;
        }
        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            margin: 20px 0;
        }
        .bot-link {
            color: #64b5f6;
            text-decoration: none;
            font-weight: 600;
        }
        .bot-link:hover {
            text-decoration: underline;
        }
        .text-muted {
            color: rgba(255, 255, 255, 0.4);
            font-size: 12px;
            text-align: center;
            margin-top: 20px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .status-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .msg-item {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            .actions {
                flex-direction: column;
            }
            .actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💌 Auto DM Saver</h1>
        <p class="subtitle">Manage messages sent automatically to new users</p>

        <!-- Status Bar -->
        <div class="status-bar">
            <div class="status-item">
                <span class="status-dot <?php echo $botUsername ? 'on' : 'off'; ?>"></span>
                Bot: <?php echo $botUsername ? '✅ Online (@' . htmlspecialchars($botUsername) . ')' : '❌ Offline'; ?>
            </div>
            <div class="status-item">
                📨 Messages: <span class="badge"><?php echo count($autoDmMessages); ?></span>
            </div>
            <div class="status-item">
                📤 Forwarding: 
                <span class="badge" style="background: <?php echo $forwardingMode ? 'rgba(0,200,83,0.2)' : 'rgba(255,23,68,0.2)'; ?>; color: <?php echo $forwardingMode ? '#00c853' : '#ff1744'; ?>;">
                    <?php echo $forwardingMode ? '✅ ON' : '❌ OFF'; ?>
                </span>
            </div>
        </div>

        <!-- Messages List -->
        <?php if (empty($autoDmMessages)): ?>
            <div class="empty-state">
                <div class="emoji">📭</div>
                <h3>No messages saved yet</h3>
                <p style="font-size: 14px;">Forward messages to the bot to save them</p>
            </div>
        <?php else: ?>
            <div class="msg-list">
                <?php foreach ($autoDmMessages as $index => $msg): ?>
                    <div class="msg-item">
                        <div class="msg-info">
                            <div class="type">
                                <?php if ($msg['type'] === 'copy'): ?>
                                    📋 Forwarded Message
                                <?php else: ?>
                                    <?php 
                                    $type = isset($msg['text']) ? 'Text' : 
                                           (isset($msg['photo']) ? 'Photo' :
                                           (isset($msg['video']) ? 'Video' :
                                           (isset($msg['document']) ? 'Document/APK' :
                                           (isset($msg['audio']) ? 'Audio' :
                                           (isset($msg['sticker']) ? 'Sticker' : 'Unknown')))));
                                    echo '📎 ' . $type;
                                    ?>
                                <?php endif; ?>
                            </div>
                            <div class="details">
                                #<?php echo ($index + 1); ?> 
                                <?php if ($msg['type'] === 'copy'): ?>
                                    • from chat <?php echo htmlspecialchars($msg['from_chat_id'] ?? 'unknown'); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this message?')">✕ Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <hr class="divider">

        <!-- Actions -->
        <div class="actions">
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="toggle_forwarding">
                <button type="submit" class="btn <?php echo $forwardingMode ? 'btn-danger' : 'btn-success'; ?>">
                    <?php echo $forwardingMode ? '🔄 Turn OFF Forwarding' : '🔄 Turn ON Forwarding'; ?>
                </button>
            </form>
            
            <?php if (!empty($autoDmMessages)): ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="clear_all">
                    <button type="submit" class="btn btn-warning" onclick="return confirm('⚠️ Delete ALL saved messages?')">🗑️ Clear All</button>
                </form>
            <?php endif; ?>
            
            <a href="https://t.me/lose_recover_bot" target="_blank" class="btn btn-primary">
                🚀 Open Bot
            </a>
        </div>

        <!-- Instructions -->
        <div class="instructions">
            <h4>📌 How to Save Auto DM Messages</h4>
            <ol>
                <li>Click <strong>"Turn ON Forwarding"</strong> button above</li>
                <li>Go to your Telegram and <strong>forward</strong> any message to <strong>@lose_recover_bot</strong></li>
                <li>The message will appear in the list above</li>
                <li>Repeat for multiple messages (they will be sent in order)</li>
                <li>Click <strong>"Turn OFF Forwarding"</strong> when done</li>
            </ol>
            <p style="margin-top: 12px; font-size: 13px; color: rgba(255,255,255,0.4);">
                💡 Supports: Text, Photos, Videos, Documents (APK), Audio, Stickers
            </p>
        </div>

        <div class="text-muted">
            Data stored in: <code><?php echo htmlspecialchars($dataFile); ?></code>
        </div>
    </div>
</body>
</html>
