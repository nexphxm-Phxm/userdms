<?php
/**
 * User Verification Bot 
 * With Auto DM System - SAVE ANY MESSAGE SENT TO BOT
 * Bot Token: 8814977950:AAEr7T-rHx3jE8Dj7zEKmsszORCUBy3_vF4
 * Bot Username: @lose_recover_bot
 */

// ==========================================
// 1. HARDCODED CONFIG
// ==========================================
$botToken = "8814977950:AAEr7T-rHx3jE8Dj7zEKmsszORCUBy3_vF4"; 
$defaultImage = "https://t.me/NEXm2m/824"; 
$defaultAdmin = "5157557268";

$defaultChannels = [];
$defaultFolders = [];
$defaultSolvedPost = "https://t.me/NEXm2m/861";
$defaultVerificationMsg = "✅ <b>Verification Successful!</b>\n\nYou have successfully joined all channels.\n\n<b>Access Granted!</b>";

$dataFile = __DIR__ . '/data6.json';

// ==========================================
// 2. DATABASE FUNCTIONS
// ==========================================
function loadBotData($filePath) {
    if (!file_exists($filePath)) {
        return createFreshData();
    }
    $data = json_decode(file_get_contents($filePath), true);
    if (!$data) {
        return createFreshData();
    }
    return $data;
}

function createFreshData() {
    $initialData = [
        'admins' => ['5157557268'],
        'imageUrl' => 'https://t.me/NEXm2m/824',
        'channels' => [],
        'folders' => [],
        'folder_buttons' => [],
        'solved_post_link' => 'https://t.me/NEXm2m/861',
        'verification_success_msg' => '✅ <b>Verification Successful!</b>\n\nYou have successfully joined all channels.\n\n<b>Access Granted!</b>',
        'referrals' => [],
        'registered' => [],
        'verified_users' => [],
        'admin_states' => [],
        'welcome_message' => '',
        'welcome_buttons' => [],
        'processed_join_requests' => [],
        'pending_channel_forward' => [],
        'auto_dm_messages' => [],
        'channel_invite_links' => [],
        'pending_edit_channel' => [],
        'current_folder' => '',
        'pending_folder_button' => [],
        'user_last_interaction' => [],
        'ai_responses' => [
            'hi' => 'Hello! How can I help you today?',
            'help' => 'I can help you with verification and support.',
            'default' => 'Please use /start to begin verification.'
        ],
        'referral_enabled' => true,
        'referral_target' => 3,
        'user_welcome_msg' => '🎉 <b>Welcome to the Network!</b>\n\nYou have successfully verified.',
        'user_join_msg' => '🎯 <b>New User Alert!</b>\n\n👤 {first_name}\n🆔 {user_id}',
        'pending_removal_items' => [],
        'save_mode' => false
    ];
    file_put_contents($GLOBALS['dataFile'], json_encode($initialData, JSON_PRETTY_PRINT));
    return $initialData;
}

function saveBotData($filePath, $data) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

// Load data
$botData = loadBotData($dataFile);

// Extract variables
$imageUrl       = $botData['imageUrl'];
$solvedPostLink = $botData['solved_post_link'];
$admins         = $botData['admins'];
$channels       = $botData['channels'] ?? [];
$folders        = $botData['folders'] ?? [];
$folderButtons  = $botData['folder_buttons'] ?? [];
$welcomeMessage = $botData['welcome_message'] ?? '';
$welcomeButtons = $botData['welcome_buttons'] ?? [];
$autoDmMessages = $botData['auto_dm_messages'] ?? [];
$currentFolder  = $botData['current_folder'] ?? '';
$verificationSuccessMsg = $botData['verification_success_msg'] ?? $defaultVerificationMsg;
$aiResponses = $botData['ai_responses'] ?? [];
$referralEnabled = $botData['referral_enabled'] ?? true;
$referralTarget = $botData['referral_target'] ?? 3;
$userWelcomeMsg = $botData['user_welcome_msg'] ?? '';
$userJoinMsg = $botData['user_join_msg'] ?? '';
$verifiedUsers = $botData['verified_users'] ?? [];
$saveMode = $botData['save_mode'] ?? false;

// Premium Emoji Configuration
$premiumEmojis = [
    '🔖' => '<tg-emoji emoji-id="6154668949549092628">🔖</tg-emoji>',
    '📌' => '<tg-emoji emoji-id="6154635564768300782">📌</tg-emoji>',
    '☯️' => '<tg-emoji emoji-id="6163667490149765932">☯️</tg-emoji>',
    '🏅' => '<tg-emoji emoji-id="6154668507167461992">🏅</tg-emoji>',
    '✔️' => '<tg-emoji emoji-id="6154718341672997377">✔️</tg-emoji>',
    '🎄' => '<tg-emoji emoji-id="6154306875216106278">🎄</tg-emoji>',
    '❤️' => '<tg-emoji emoji-id="6154619677684272865">❤️</tg-emoji>',
    '🔥' => '<tg-emoji emoji-id="6181540309357305513">🔥</tg-emoji>',
    '👤' => '<tg-emoji emoji-id="6165860934242798778">👤</tg-emoji>',
    '🔗' => '<tg-emoji emoji-id="6129589862413638401">🔗</tg-emoji>',
    '💌' => '<tg-emoji emoji-id="6267117597154612941">💌</tg-emoji>',
    '🎉' => '<tg-emoji emoji-id="6154462890630495777">🎉</tg-emoji>',
    '🎯' => '<tg-emoji emoji-id="6154462890630495778">🎯</tg-emoji>',
    '📅' => '<tg-emoji emoji-id="6154326312310014720">📅</tg-emoji>',
    '🔤' => '<tg-emoji emoji-id="5294057271226017876">🔤</tg-emoji>',
    '🍊' => '<tg-emoji emoji-id="6026091316767625976">🍊</tg-emoji>',
    '⭕️' => '<tg-emoji emoji-id="5949775417274536507">⭕️</tg-emoji>',
    '😈' => '<tg-emoji emoji-id="5260553279321944543">😈</tg-emoji>',
    '➡️' => '<tg-emoji emoji-id="6222163909634168112">➡️</tg-emoji>',
    '✅' => '<tg-emoji emoji-id="6275846989334710982">✅</tg-emoji>',
    '✨' => '<tg-emoji emoji-id="5990073381720953601">✨</tg-emoji>',
    '💎' => '<tg-emoji emoji-id="5796262720495946957">💎</tg-emoji>',
    '✈️' => '<tg-emoji emoji-id="5325705326157113713">✈️</tg-emoji>',
    '🚦' => '<tg-emoji emoji-id="5262179768980443936">🚦</tg-emoji>',
    '🚀' => '<tg-emoji emoji-id="5222075933468954340">🚀</tg-emoji>',
];

// ==========================================
// 3. MAIN WEBHOOK CONTROLLER
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>🤖 Bot Status: Active</h1>";
    echo "<p>👤 Admins: " . count($admins) . "</p>";
    echo "<p>📢 Channels: " . count($channels) . "</p>";
    echo "<p>📁 Folders: " . count($folders) . "</p>";
    echo "<p>💌 Auto DM Messages: " . count($autoDmMessages) . "</p>";
    echo "<p>📤 Save Mode: " . ($saveMode ? '✅ ON' : '❌ OFF') . "</p>";
    
    if (!empty($autoDmMessages)) {
        echo "<hr><h3>📨 Saved Auto DM Messages:</h3>";
        foreach ($autoDmMessages as $index => $msg) {
            echo "<p>" . ($index + 1) . ". ";
            if ($msg['type'] === 'copy') {
                echo "📋 Forwarded Message";
            } else {
                $type = isset($msg['text']) ? 'Text' : 
                       (isset($msg['photo']) ? 'Photo' :
                       (isset($msg['video']) ? 'Video' :
                       (isset($msg['document']) ? 'Document/APK' :
                       (isset($msg['audio']) ? 'Audio' :
                       (isset($msg['sticker']) ? 'Sticker' : 'Unknown')))));
                echo "📎 " . $type;
            }
            echo "</p>";
        }
    } else {
        echo "<hr><p style='color:orange;'>⚠️ No Auto DM messages saved yet!</p>";
        echo "<p>📌 <b>How to save:</b></p>";
        echo "<ol>";
        echo "<li>Send <code>/admin</code> to @lose_recover_bot</li>";
        echo "<li>Click '💌 Manage Auto DM'</li>";
        echo "<li>Click '🔄 Turn ON Save Mode'</li>";
        echo "<li><b>SEND ANY MESSAGE</b> to the bot (text, photo, video, APK, etc.)</li>";
        echo "<li>The message will be saved automatically!</li>";
        echo "</ol>";
    }
    
    echo "<hr>";
    echo "<p><strong>Bot:</strong> <a href='https://t.me/lose_recover_bot'>@lose_recover_bot</a></p>";
    echo "<p><strong>Web Interface:</strong> <a href='save.php'>save.php</a></p>";
    echo "<p><strong>To set webhook:</strong></p>";
    echo "<code>https://api.telegram.org/bot" . $botToken . "/setWebhook?url=" . (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "</code>";
    exit;
}

$input = file_get_contents('php://input');
$update = json_decode($input, true);

if (!$update) {
    http_response_code(200);
    exit;
}

if (isset($update['message'])) {
    handleMessage($update['message'], $botToken, $imageUrl, $channels, $premiumEmojis, $admins, $botData, $dataFile, $welcomeMessage, $welcomeButtons, $autoDmMessages, $folders, $folderButtons, $verificationSuccessMsg, $aiResponses, $referralEnabled, $referralTarget, $userWelcomeMsg, $userJoinMsg, $verifiedUsers, $saveMode);
} elseif (isset($update['callback_query'])) {
    handleCallbackQuery($update['callback_query'], $botToken, $channels, $solvedPostLink, $premiumEmojis, $admins, $botData, $dataFile, $folders, $folderButtons, $verificationSuccessMsg, $referralEnabled, $referralTarget, $userWelcomeMsg, $verifiedUsers);
} elseif (isset($update['chat_join_request'])) {
    $me = sendTelegramRequest('getMe', [], $botToken);
    $botUser = $me['result']['username'] ?? 'lose_recover_bot';
    handleChatJoinRequest($update['chat_join_request'], $botToken, $botUser, $premiumEmojis, $admins, $botData, $dataFile, $autoDmMessages);
}

http_response_code(200);
exit;

// ==========================================
// 4. SAVE FUNCTIONS
// ==========================================

function saveMessageContent($message) {
    $data = ['type' => 'content'];
    
    // TEXT
    if (isset($message['text'])) {
        $data['text'] = $message['text'];
        $data['parse_mode'] = 'HTML';
    }
    
    // PHOTO
    if (isset($message['photo'])) {
        $photo = end($message['photo']);
        $data['photo'] = $photo['file_id'];
        if (isset($message['caption'])) {
            $data['caption'] = $message['caption'];
        }
    }
    
    // VIDEO
    if (isset($message['video'])) {
        $data['video'] = $message['video']['file_id'];
        $data['caption'] = $message['caption'] ?? '';
    }
    
    // DOCUMENT (APK files)
    if (isset($message['document'])) {
        $data['document'] = $message['document']['file_id'];
        $data['caption'] = $message['caption'] ?? '';
        $data['file_name'] = $message['document']['file_name'] ?? 'file';
    }
    
    // AUDIO
    if (isset($message['audio'])) {
        $data['audio'] = $message['audio']['file_id'];
        $data['caption'] = $message['caption'] ?? '';
    }
    
    // VOICE
    if (isset($message['voice'])) {
        $data['voice'] = $message['voice']['file_id'];
    }
    
    // STICKER
    if (isset($message['sticker'])) {
        $data['sticker'] = $message['sticker']['file_id'];
    }
    
    // ANIMATION (GIF)
    if (isset($message['animation'])) {
        $data['animation'] = $message['animation']['file_id'];
        $data['caption'] = $message['caption'] ?? '';
    }
    
    return $data;
}

function saveMessageToDatabase($message, &$botData, $dataFile) {
    $savedData = [];
    
    // Check if it's a forwarded message (can copy directly)
    if (isset($message['forward_from_chat']) && isset($message['forward_from_message_id'])) {
        $savedData = [
            'type' => 'copy',
            'from_chat_id' => $message['forward_from_chat']['id'],
            'message_id' => $message['forward_from_message_id']
        ];
    } elseif (isset($message['forward_from']) && isset($message['forward_from_message_id'])) {
        $savedData = [
            'type' => 'copy',
            'from_chat_id' => $message['forward_from']['id'],
            'message_id' => $message['forward_from_message_id']
        ];
    } elseif (isset($message['forward_origin'])) {
        $origin = $message['forward_origin'];
        if (isset($origin['chat']) && isset($origin['message_id'])) {
            $savedData = [
                'type' => 'copy',
                'from_chat_id' => $origin['chat']['id'],
                'message_id' => $origin['message_id']
            ];
        } else {
            $savedData = saveMessageContent($message);
        }
    } else {
        // Direct message - save content
        $savedData = saveMessageContent($message);
    }
    
    $botData['auto_dm_messages'][] = $savedData;
    saveBotData($dataFile, $botData);
    
    return $savedData;
}

// ==========================================
// 5. SEND FUNCTIONS
// ==========================================

function sendSavedMessage($chatId, $data, $botToken) {
    if (isset($data['text'])) {
        return sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => $data['text'],
            'parse_mode' => $data['parse_mode'] ?? 'HTML'
        ], $botToken);
    }
    
    if (isset($data['photo'])) {
        return sendTelegramRequest('sendPhoto', [
            'chat_id' => $chatId,
            'photo' => $data['photo'],
            'caption' => $data['caption'] ?? ''
        ], $botToken);
    }
    
    if (isset($data['video'])) {
        return sendTelegramRequest('sendVideo', [
            'chat_id' => $chatId,
            'video' => $data['video'],
            'caption' => $data['caption'] ?? ''
        ], $botToken);
    }
    
    if (isset($data['document'])) {
        return sendTelegramRequest('sendDocument', [
            'chat_id' => $chatId,
            'document' => $data['document'],
            'caption' => $data['caption'] ?? ''
        ], $botToken);
    }
    
    if (isset($data['audio'])) {
        return sendTelegramRequest('sendAudio', [
            'chat_id' => $chatId,
            'audio' => $data['audio'],
            'caption' => $data['caption'] ?? ''
        ], $botToken);
    }
    
    if (isset($data['voice'])) {
        return sendTelegramRequest('sendVoice', [
            'chat_id' => $chatId,
            'voice' => $data['voice']
        ], $botToken);
    }
    
    if (isset($data['sticker'])) {
        return sendTelegramRequest('sendSticker', [
            'chat_id' => $chatId,
            'sticker' => $data['sticker']
        ], $botToken);
    }
    
    if (isset($data['animation'])) {
        return sendTelegramRequest('sendAnimation', [
            'chat_id' => $chatId,
            'animation' => $data['animation'],
            'caption' => $data['caption'] ?? ''
        ], $botToken);
    }
    
    return false;
}

function sendAutoDmMessages($chatId, $autoDmMessages, $botToken) {
    if (empty($autoDmMessages)) {
        return;
    }
    
    foreach ($autoDmMessages as $index => $msgData) {
        if ($msgData['type'] === 'copy') {
            $response = sendTelegramRequest('copyMessage', [
                'chat_id' => $chatId,
                'from_chat_id' => $msgData['from_chat_id'],
                'message_id' => $msgData['message_id']
            ], $botToken);
            
            if (!$response || !isset($response['ok']) || $response['ok'] !== true) {
                error_log("Failed to copy message " . ($index + 1));
            }
        } else {
            sendSavedMessage($chatId, $msgData, $botToken);
        }
        usleep(500000); // 0.5 second delay
    }
}

// ==========================================
// 6. MESSAGE HANDLER - FIXED NULL ISSUE
// ==========================================

function handleMessage($message, $botToken, $imageUrl, $channels, $premiumEmojis, $admins, &$botData, $dataFile, $welcomeMessage, $welcomeButtons, $autoDmMessages, $folders, $folderButtons, $verificationSuccessMsg, $aiResponses, $referralEnabled, $referralTarget, $userWelcomeMsg, $userJoinMsg, &$verifiedUsers, &$saveMode) {
    $chatId = $message['chat']['id'] ?? null;
    $text = trim($message['text'] ?? '');
    $userId = $message['from']['id'] ?? null;
    
    if (!$chatId || !$userId) return;

    $isAdmin = in_array((string)$userId, $admins);
    $isInState = isset($botData['admin_states'][$userId]) || 
                 isset($botData['pending_channel_forward'][$userId]) || 
                 isset($botData['pending_folder_button'][$userId]) ||
                 isset($botData['pending_edit_channel'][$userId]);

    // ==========================================
    // SAVE MODE - Save ANY message sent to bot
    // ==========================================
    // Always read save_mode fresh from $botData to avoid stale-variable bug
    $currentSaveMode = $botData['save_mode'] ?? false;
    if ($isAdmin && $currentSaveMode && !$isInState && strpos($text, '/') !== 0) {
        // Detect any content type
        $hasContent = isset($message['text']) || 
                      isset($message['photo']) || 
                      isset($message['video']) || 
                      isset($message['document']) || 
                      isset($message['audio']) || 
                      isset($message['voice']) || 
                      isset($message['sticker']) ||
                      isset($message['animation']);
        
        if ($hasContent) {
            $savedData = saveMessageToDatabase($message, $botData, $dataFile);
            
            $reply = "✅ <b>Auto DM Message Saved!</b>\n\n";
            $reply .= "📌 Total messages: " . count($botData['auto_dm_messages']);
            
            if ($savedData['type'] === 'copy') {
                $reply .= "\n📌 Type: Forwarded Message";
            } else {
                $type = isset($savedData['text']) ? 'Text' : 
                       (isset($savedData['photo']) ? 'Photo' :
                       (isset($savedData['video']) ? 'Video' :
                       (isset($savedData['document']) ? 'Document/APK' :
                       (isset($savedData['audio']) ? 'Audio' :
                       (isset($savedData['sticker']) ? 'Sticker' : 'Media')))));
                $reply .= "\n📌 Type: " . $type;
            }
            
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($reply, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
            
            showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
            return;
        }
    }

    // ==========================================
    // AUTO DM MANAGEMENT
    // ==========================================
    if ($isAdmin && isset($botData['admin_states'][$userId]) && $botData['admin_states'][$userId] === 'manage_auto_dm') {
        unset($botData['admin_states'][$userId]);
        if (strpos($text, 'remove|') === 0) {
            removeAutoDmMessage($chatId, $userId, $text, $botData, $dataFile, $botToken, $premiumEmojis);
        } else {
            showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
        }
        return;
    }

    // AI Response (only if not in save mode)
    if (!$isAdmin && !$isInState && strpos($text, '/') !== 0 && !empty($text)) {
        $response = getAIResponse($text, $aiResponses, $userId);
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($response, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }

    // ==========================================
    // ADMIN FUNCTIONS
    // ==========================================
    
    if ($isAdmin && isset($botData['pending_folder_button'][$userId])) {
        unset($botData['pending_folder_button'][$userId]);
        $link = trim($text);
        if (strpos($link, 't.me/addlist/') !== false || strpos($link, 'telegram.me/addlist/') !== false) {
            $botData['folder_buttons'][] = ['link' => $link, 'created_at' => time()];
            saveBotData($dataFile, $botData);
            $reply = "✔️ <b>Folder Button Created Successfully!</b>";
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($reply, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
        } else {
            $reply = "📌 <b>Invalid Link!</b>";
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($reply, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
        }
        showCurrentFolder($chatId, $botToken, $botData);
        return;
    }

    if ($isAdmin && isset($botData['pending_channel_forward'][$userId])) {
        unset($botData['pending_channel_forward'][$userId]);
        if (isset($message['forward_from_chat'])) {
            $forwardedChat = $message['forward_from_chat'];
            $chatIdForwarded = $forwardedChat['id'] ?? null;
            $chatTitle = $forwardedChat['title'] ?? 'Private Channel';
            if ($chatIdForwarded) {
                $inviteLink = null;
                try {
                    $response = sendTelegramRequest('createChatInviteLink', [
                        'chat_id' => $chatIdForwarded,
                        'name' => 'Bot Verification Link',
                        'creates_join_request' => true
                    ], $botToken);
                    if ($response && isset($response['ok']) && $response['ok'] === true) {
                        $inviteLink = $response['result']['invite_link'] ?? null;
                        if (!isset($botData['channel_invite_links'])) {
                            $botData['channel_invite_links'] = [];
                        }
                        $botData['channel_invite_links'][$chatIdForwarded] = [
                            'link' => $inviteLink,
                            'created_at' => time()
                        ];
                    }
                } catch (Exception $e) {}
                if (!$inviteLink && isset($botData['channel_invite_links'][$chatIdForwarded])) {
                    $inviteLink = $botData['channel_invite_links'][$chatIdForwarded]['link'] ?? null;
                }
                if ($inviteLink) {
                    $exists = false;
                    foreach ($botData['channels'] as $chan) {
                        if ($chan['id'] == $chatIdForwarded) { $exists = true; break; }
                    }
                    if (!$exists) {
                        $folder = $botData['current_folder'] ?? '';
                        $botData['channels'][] = [
                            'id' => $chatIdForwarded,
                            'link' => $inviteLink,
                            'type' => 'private',
                            'title' => $chatTitle,
                            'folder' => $folder
                        ];
                        saveBotData($dataFile, $botData);
                        $reply = "✔️ <b>Private Channel Added Successfully!</b>";
                    } else {
                        $reply = "✔️ <b>Private Channel Already Exists!</b>";
                    }
                } else {
                    $reply = "📌 <b>Failed to Create Invite Link!</b>";
                }
            } else {
                $reply = "📌 <b>Invalid Forward!</b>";
            }
        } else {
            $reply = "📌 <b>Please forward a message from the private channel!</b>";
        }
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        showCurrentFolder($chatId, $botToken, $botData);
        return;
    }

    if ($isAdmin && isset($botData['pending_edit_channel'][$userId])) {
        $editData = $botData['pending_edit_channel'][$userId];
        unset($botData['pending_edit_channel'][$userId]);
        $channelIndex = $editData['index'];
        $newLink = trim($text);
        if (isset($botData['channels'][$channelIndex])) {
            $botData['channels'][$channelIndex]['link'] = $newLink;
            saveBotData($dataFile, $botData);
            $reply = "✔️ <b>Channel Link Updated Successfully!</b>";
        } else {
            $reply = "📌 <b>Channel not found!</b>";
        }
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        showCurrentFolder($chatId, $botToken, $botData);
        return;
    }

    if ($isAdmin && isset($botData['admin_states'][$userId]) && $botData['admin_states'][$userId] === 'reorder_channels') {
        unset($botData['admin_states'][$userId]);
        $numbers = array_map('trim', explode(',', $text));
        $totalChannels = count($botData['channels']);
        $valid = true;
        $used = [];
        foreach ($numbers as $num) {
            $num = intval($num);
            if ($num < 1 || $num > $totalChannels || in_array($num, $used)) { $valid = false; break; }
            $used[] = $num;
        }
        if ($valid && count($numbers) == $totalChannels) {
            $oldOrder = [];
            foreach ($botData['channels'] as $index => $chan) { $oldOrder[$index + 1] = $chan; }
            $newChannels = [];
            foreach ($numbers as $num) { $newChannels[] = $oldOrder[intval($num)]; }
            $botData['channels'] = $newChannels;
            saveBotData($dataFile, $botData);
            $reply = "✔️ <b>Channels Reordered Successfully!</b>";
        } else {
            $reply = "📌 <b>Invalid Order!</b>";
        }
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        showCurrentFolder($chatId, $botToken, $botData);
        return;
    }

    if ($isAdmin && isset($botData['admin_states'][$userId]) && $botData['admin_states'][$userId] === 'new_folder') {
        unset($botData['admin_states'][$userId]);
        $folderName = trim($text);
        if (!empty($folderName) && !in_array($folderName, $botData['folders'])) {
            $botData['folders'][] = $folderName;
            $botData['current_folder'] = $folderName;
            saveBotData($dataFile, $botData);
            $reply = "✔️ <b>Folder Created Successfully!</b>";
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($reply, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
        } else {
            $reply = "📌 <b>Folder already exists!</b>";
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($reply, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
        }
        showCurrentFolder($chatId, $botToken, $botData);
        return;
    }

    if ($isAdmin && isset($botData['admin_states'][$userId]) && $botData['admin_states'][$userId] === 'remove_item') {
        processUnifiedRemove($chatId, $userId, $text, $botData, $dataFile, $botToken, $premiumEmojis);
        unset($botData['admin_states'][$userId]);
        return;
    }

    if ($isAdmin && isset($botData['admin_states'][$userId]) && $botData['admin_states'][$userId] === 'confirm_reset') {
        unset($botData['admin_states'][$userId]);
        if (strtolower(trim($text)) === 'yes') {
            $GLOBALS['botData'] = createFreshData();
            $GLOBALS['channels'] = [];
            $GLOBALS['folders'] = [];
            $GLOBALS['folderButtons'] = [];
            $GLOBALS['verifiedUsers'] = [];
            $reply = "✅ <b>Bot Reset Successfully!</b>";
        } else {
            $reply = "❌ <b>Reset Cancelled</b>";
        }
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        sendAdminPanel($chatId, $botToken);
        return;
    }

    // ==========================================
    // ADMIN STATE MACHINE
    // ==========================================
    if ($isAdmin && isset($botData['admin_states'][$userId]) && strpos($text, '/') !== 0) {
        $state = $botData['admin_states'][$userId];
        unset($botData['admin_states'][$userId]);

        switch ($state) {
            case 'add_chan_public':
                $lines = explode("\n", $text);
                if (count($lines) >= 2) {
                    $chanId = trim($lines[0]);
                    $chanLink = trim($lines[1]);
                    $chanTitle = $chanId;
                    $folder = $botData['current_folder'] ?? '';
                    $botData['channels'][] = [
                        'id' => $chanId,
                        'link' => $chanLink,
                        'type' => 'public',
                        'title' => $chanTitle,
                        'folder' => $folder
                    ];
                    saveBotData($dataFile, $botData);
                    $reply = "✔️ <b>Public Channel Added Successfully!</b>";
                } else {
                    $reply = "📌 <b>Format Error!</b>\n\nUse: @channelusername\nhttps://t.me/channel";
                }
                break;
            case 'add_chan_private':
                $botData['pending_channel_forward'][$userId] = true;
                saveBotData($dataFile, $botData);
                $reply = "☯️ <b>Private Channel Setup</b>\n\nForward a message from the private channel:";
                break;
            case 'edit_img':
                $botData['imageUrl'] = $text;
                saveBotData($dataFile, $botData);
                $reply = "✔️ Bot Start Image URL changed successfully.";
                break;
            case 'edit_solved':
                $botData['solved_post_link'] = $text;
                saveBotData($dataFile, $botData);
                $reply = "✔️ Target Solved Post Link updated successfully.";
                break;
            case 'edit_verification_msg':
                $botData['verification_success_msg'] = $text;
                saveBotData($dataFile, $botData);
                $reply = "✔️ Verification Success Message updated successfully.";
                break;
            case 'edit_user_welcome':
                $botData['user_welcome_msg'] = $text;
                saveBotData($dataFile, $botData);
                $reply = "✔️ User Welcome Message updated successfully.";
                break;
            case 'edit_user_join':
                $botData['user_join_msg'] = $text;
                saveBotData($dataFile, $botData);
                $reply = "✔️ User Join Notification updated successfully.";
                break;
            case 'add_admin':
                if (!in_array($text, $botData['admins'])) {
                    $botData['admins'][] = $text;
                    saveBotData($dataFile, $botData);
                    $reply = "✔️ User ID <code>" . htmlspecialchars($text) . "</code> added as Admin.";
                } else {
                    $reply = "🔖 User already Admin.";
                }
                break;
            case 'rem_admin':
                if (($key = array_search($text, $botData['admins'])) !== false) {
                    unset($botData['admins'][$key]);
                    $botData['admins'] = array_values($botData['admins']);
                    saveBotData($dataFile, $botData);
                    $reply = "✔️ User ID <code>" . htmlspecialchars($text) . "</code> removed from Admins.";
                } else {
                    $reply = "📌 User ID not found in Admin list.";
                }
                break;
            case 'edit_welcome':
                $botData['welcome_message'] = $text;
                saveBotData($dataFile, $botData);
                $reply = "✔️ Welcome message updated successfully.";
                break;
            case 'edit_ai_response':
                $parts = explode('|', $text, 2);
                if (count($parts) == 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    $botData['ai_responses'][$key] = $value;
                    saveBotData($dataFile, $botData);
                    $reply = "✔️ AI Response updated for key: <b>" . htmlspecialchars($key) . "</b>";
                } else {
                    $reply = "📌 <b>Invalid Format!</b>\n\nUse: key|response";
                }
                break;
            case 'toggle_referral':
                $botData['referral_enabled'] = !$botData['referral_enabled'];
                saveBotData($dataFile, $botData);
                $status = $botData['referral_enabled'] ? 'ENABLED' : 'DISABLED';
                $reply = "✔️ Referral System <b>" . $status . "</b> successfully!";
                break;
            case 'set_referral_target':
                $target = intval($text);
                if ($target > 0) {
                    $botData['referral_target'] = $target;
                    saveBotData($dataFile, $botData);
                    $reply = "✔️ Referral Target set to: <b>" . $target . "</b>";
                } else {
                    $reply = "📌 <b>Invalid Number!</b>";
                }
                break;
            case 'broadcast':
                $broadcastSent = 0;
                $broadcastFailed = 0;
                foreach ($botData['registered'] as $userId => $registered) {
                    if ($registered) {
                        $result = sendTelegramRequest('sendMessage', [
                            'chat_id' => $userId,
                            'text' => $text,
                            'parse_mode' => 'HTML'
                        ], $botToken);
                        if ($result && isset($result['ok']) && $result['ok'] === true) {
                            $broadcastSent++;
                        } else {
                            $broadcastFailed++;
                        }
                    }
                }
                $reply = "📌 <b>Broadcast Complete!</b>\n\n✔️ Sent: <b>" . $broadcastSent . "</b> users\n📌 Failed: <b>" . $broadcastFailed . "</b> users";
                break;
            case 'add_custom_btn':
                $result = processCustomButtonInput($text, $botData, $dataFile);
                if ($result) {
                    $reply = "✔️ " . $result;
                } else {
                    $reply = "📌 <b>Invalid Format!</b>\n\nUse: url|Button Text|https://example.com|primary";
                }
                break;
            default:
                $reply = "📌 State not recognized.";
        }

        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        showCurrentFolder($chatId, $botToken, $botData);
        return;
    }

    // ==========================================
    // COMMAND HANDLERS
    // ==========================================
    
    if (strpos($text, '/start') === 0) {
        $firstName = htmlspecialchars($message['from']['first_name'] ?? 'User');
        $lastName = isset($message['from']['last_name']) ? ' ' . htmlspecialchars($message['from']['last_name']) : '';
        $username = isset($message['from']['username']) ? '@' . htmlspecialchars($message['from']['username']) : 'None';

        $isExistingUser = isset($botData['registered'][$userId]) && $botData['registered'][$userId] === true;
        $isVerified = in_array((string)$userId, $botData['verified_users'] ?? []);
        
        if (!$isExistingUser) {
            $botData['registered'][$userId] = true;
            if (preg_match('/^\/start ref_(\d+)$/', $text, $matches)) {
                $referrerId = $matches[1];
                if ((string)$referrerId !== (string)$userId && $referralEnabled) {
                    if (!isset($botData['referrals'][$userId])) {
                        $botData['referrals'][$userId] = ['referred_by' => $referrerId, 'count' => 0, 'invited' => []];
                    }
                    if (!isset($botData['referrals'][$referrerId])) {
                        $botData['referrals'][$referrerId] = ['referred_by' => null, 'count' => 0, 'invited' => []];
                    }
                    if (!in_array($userId, $botData['referrals'][$referrerId]['invited'])) {
                        $botData['referrals'][$referrerId]['invited'][] = $userId;
                        $botData['referrals'][$referrerId]['count'] = count($botData['referrals'][$referrerId]['invited']);
                        saveBotData($dataFile, $botData);
                        $refCount = $botData['referrals'][$referrerId]['count'];
                        $notifyMsg = "🏅 <b>New User Joined!</b>\n\n🎯 <b>User:</b> " . $firstName . "\n📌 Your Total Users: <b>" . $refCount . "</b>";
                        sendTelegramRequest('sendMessage', [
                            'chat_id' => $referrerId,
                            'text' => applyPremiumEmojis($notifyMsg, $premiumEmojis),
                            'parse_mode' => 'HTML'
                        ], $botToken);
                    }
                }
            } else {
                if (!isset($botData['referrals'][$userId])) {
                    $botData['referrals'][$userId] = ['referred_by' => null, 'count' => 0, 'invited' => []];
                }
                saveBotData($dataFile, $botData);
            }
        }

        // Send Auto DM messages first
        if (!empty($autoDmMessages)) {
            sendAutoDmMessages($chatId, $autoDmMessages, $botToken);
        }

        // Then show channel buttons
        $keyboard = buildKeyboardWithCustomButtons($channels, $welcomeButtons, $folderButtons, true);
        
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => " ",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ], $botToken);

        if (!$isExistingUser) {
            $date = date('Y-m-d H:i:s');
            $userMsg = $userJoinMsg;
            $userMsg = str_replace('{first_name}', $firstName, $userMsg);
            $userMsg = str_replace('{last_name}', $lastName, $userMsg);
            $userMsg = str_replace('{username}', $username, $userMsg);
            $userMsg = str_replace('{user_id}', $userId, $userMsg);
            $userMsg = str_replace('{date}', $date, $userMsg);
            foreach ($admins as $adm) {
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $adm,
                    'text' => applyPremiumEmojis($userMsg, $premiumEmojis),
                    'parse_mode' => 'HTML'
                ], $botToken);
            }
        }
        return;
    }

    if ($text === '/refer' || $text === '/refer@' . getBotUsername($botToken)) {
        handleReferralCommand($chatId, $userId, $botToken, $premiumEmojis, $botData, $dataFile);
        return;
    }

    if ($text === '/admin') {
        if ($isAdmin) {
            sendAdminPanel($chatId, $botToken);
        } else {
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "❌ You are not an admin.",
                'parse_mode' => 'HTML'
            ], $botToken);
        }
        return;
    }
    
    if ($text === '/help') {
        $helpText = "🏅 <b>Help & Support</b>\n\n" .
                    "/start - Start the bot\n" .
                    "/refer - Get referral link\n" .
                    "/admin - Admin panel\n" .
                    "/help - Show this help";
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($helpText, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }
}

function getBotUsername($botToken) {
    $me = sendTelegramRequest('getMe', [], $botToken);
    return $me['result']['username'] ?? 'lose_recover_bot';
}

// ==========================================
// 7. REFERRAL FUNCTIONS
// ==========================================

function handleReferralCommand($chatId, $userId, $botToken, $premiumEmojis, $botData, $dataFile) {
    $me = sendTelegramRequest('getMe', [], $botToken);
    $botUser = $me['result']['username'] ?? 'lose_recover_bot';
    $refCount = $botData['referrals'][$userId]['count'] ?? 0;
    $refLink = "https://t.me/" . $botUser . "?start=ref_" . $userId;
    
    $isVerified = in_array((string)$userId, $botData['verified_users'] ?? []);
    
    if ($isVerified) {
        $text = "🎯 <b>Your Referral Link:</b>\n\n" . $refLink . "\n\n📌 Total Referrals: " . $refCount;
    } else {
        $text = "📌 You need to verify first to get a referral link.";
    }
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => applyPremiumEmojis($text, $premiumEmojis),
        'parse_mode' => 'HTML'
    ], $botToken);
}

// ==========================================
// 8. AUTO DM MANAGEMENT - FIXED WITH SAVE MODE INFO
// ==========================================

function showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis) {
    $messages = $botData['auto_dm_messages'] ?? [];
    $saveMode = $botData['save_mode'] ?? false;
    
    $text = "💌 <b>Auto DM Messages Management</b>\n\n";
    $text .= "📤 <b>Save Mode:</b> " . ($saveMode ? '✅ <b>ON</b>' : '❌ <b>OFF</b>') . "\n\n";
    
    if (empty($messages)) {
        $text .= "<i>No messages saved yet.</i>\n\n";
        $text .= "📌 <b>How to save any message:</b>\n";
        $text .= "1️⃣ Click '🔄 Turn ON Save Mode' below\n";
        $text .= "2️⃣ <b>Send ANY message</b> to the bot:\n";
        $text .= "   • 📝 Text messages\n";
        $text .= "   • 📷 Photos\n";
        $text .= "   • 🎬 Videos\n";
        $text .= "   • 📦 APK/Documents\n";
        $text .= "   • 🎵 Audio files\n";
        $text .= "   • 🎨 Stickers\n";
        $text .= "3️⃣ The message will be saved automatically\n";
        $text .= "4️⃣ Click '🔄 Turn OFF Save Mode' when done\n\n";
        $text .= "💡 <b>Note:</b> No forwarding needed! Just send any message directly.";
    } else {
        $text .= "<b>📨 Saved Messages:</b> " . count($messages) . "\n\n";
        foreach ($messages as $index => $msg) {
            if ($msg['type'] === 'copy') {
                $text .= ($index + 1) . ". 📋 Forwarded Message\n";
            } else {
                $type = isset($msg['text']) ? 'Text' : 
                       (isset($msg['photo']) ? 'Photo' :
                       (isset($msg['video']) ? 'Video' :
                       (isset($msg['document']) ? 'Document/APK' :
                       (isset($msg['audio']) ? 'Audio' :
                       (isset($msg['sticker']) ? 'Sticker' : 'Unknown')))));
                $text .= ($index + 1) . ". 📎 " . $type . "\n";
            }
        }
        $text .= "\n📌 <b>To remove:</b> Type <code>remove|number</code>\n";
        $text .= "Example: <code>remove|1</code>\n\n";
    }
    
    $text .= "💡 <b>Tip:</b> Use the <a href='save.php'>Web Interface</a> for easier management";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => $saveMode ? '🔄 Turn OFF Save Mode' : '🔄 Turn ON Save Mode', 
                 'callback_data' => 'adm_toggle_save_mode', 'style' => $saveMode ? 'danger' : 'success']
            ],
            [
                ['text' => '🗑️ Clear All Messages', 'callback_data' => 'adm_clear_auto_dm', 'style' => 'danger'],
                ['text' => '🔙 Back to Panel', 'callback_data' => 'adm_back', 'style' => 'primary']
            ]
        ]
    ];
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => applyPremiumEmojis($text, $premiumEmojis),
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard,
        'disable_web_page_preview' => true
    ], $botToken);
}

function removeAutoDmMessage($chatId, $userId, $text, &$botData, $dataFile, $botToken, $premiumEmojis) {
    $parts = explode('|', $text);
    if (isset($parts[1])) {
        $index = intval($parts[1]) - 1;
        if (isset($botData['auto_dm_messages'][$index])) {
            unset($botData['auto_dm_messages'][$index]);
            $botData['auto_dm_messages'] = array_values($botData['auto_dm_messages']);
            saveBotData($dataFile, $botData);
            $reply = "✔️ <b>Auto DM Message " . ($index + 1) . " removed successfully!</b>";
        } else {
            $reply = "📌 <b>Invalid message number!</b>";
        }
    } else {
        $reply = "📌 <b>Invalid format!</b>\nUse: remove|1";
    }
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => applyPremiumEmojis($reply, $premiumEmojis),
        'parse_mode' => 'HTML'
    ], $botToken);
    showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
}

// ==========================================
// 9. REMOVE SYSTEM
// ==========================================

function showUnifiedRemoveList($chatId, $botToken, $botData, $premiumEmojis) {
    $text = "🗑️ <b>Remove Channel / Folder</b>\n\n" .
            "📌 Send the number to remove:\n\n";
    
    $items = [];
    $index = 1;
    
    $text .= "📢 <b>Channels:</b>\n";
    if (empty($botData['channels'])) {
        $text .= "<i>No channels added yet</i>\n";
    } else {
        foreach ($botData['channels'] as $idx => $chan) {
            $type = $chan['type'] ?? 'public';
            $typeLabel = $type === 'private' ? '🔒' : '🌐';
            $title = $chan['title'] ?? $chan['id'];
            $folder = $chan['folder'] ?? 'No Folder';
            $text .= ($index) . ". $typeLabel <b>" . htmlspecialchars($title) . "</b> [📁" . htmlspecialchars($folder) . "]\n";
            $items[$index] = ['type' => 'channel', 'index' => $idx];
            $index++;
        }
    }
    
    $text .= "\n📁 <b>Folders:</b>\n";
    if (empty($botData['folders'])) {
        $text .= "<i>No folders created yet</i>\n";
    } else {
        foreach ($botData['folders'] as $idx => $folder) {
            $count = 0;
            foreach ($botData['channels'] as $chan) {
                if (($chan['folder'] ?? '') == $folder) {
                    $count++;
                }
            }
            $isActive = ($folder == ($botData['current_folder'] ?? '')) ? ' ✅ (Active)' : '';
            $text .= ($index) . ". 📁 <b>" . htmlspecialchars($folder) . "</b> (<b>" . $count . "</b> channels)" . $isActive . "\n";
            $items[$index] = ['type' => 'folder', 'index' => $idx, 'name' => $folder];
            $index++;
        }
    }
    
    if (empty($botData['channels']) && empty($botData['folders'])) {
        $text .= "\n<i>❌ Nothing to remove.</i>";
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($text, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }
    
    $text .= "\n📌 <i>Warning: Removing a folder removes its assignment from channels.</i>";
    
    $botData['pending_removal_items'] = $items;
    // BUG FIX: was using $chatId instead of $userId as the state key
    // The message handler looks up admin_states[$userId], so must use $userId here.
    // showUnifiedRemoveList is always called from admin context so $chatId == $userId in private chats,
    // but we extract $userId from the function's context via $chatId (they're the same in DM).
    // We store under $chatId which equals userId in private chat — kept for DM bots, but
    // renamed variable to make intent clear.
    $adminUserId = $chatId; // In private DM bots, chatId === userId
    $botData['admin_states'][$adminUserId] = 'remove_item';
    saveBotData($GLOBALS['dataFile'], $botData);
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => applyPremiumEmojis($text, $premiumEmojis),
        'parse_mode' => 'HTML'
    ], $botToken);
}

function processUnifiedRemove($chatId, $userId, $text, &$botData, $dataFile, $botToken, $premiumEmojis) {
    $number = intval($text);
    $items = $botData['pending_removal_items'] ?? [];
    
    if (!isset($items[$number])) {
        $reply = "📌 <b>Invalid Number!</b>";
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        showUnifiedRemoveList($chatId, $botToken, $botData, $premiumEmojis);
        return;
    }
    
    $item = $items[$number];
    
    if ($item['type'] === 'channel') {
        $channelIndex = $item['index'];
        if (isset($botData['channels'][$channelIndex])) {
            $removed = $botData['channels'][$channelIndex];
            $title = $removed['title'] ?? $removed['id'];
            unset($botData['channels'][$channelIndex]);
            $botData['channels'] = array_values($botData['channels']);
            saveBotData($dataFile, $botData);
            $reply = "✔️ <b>Channel Removed Successfully!</b>\n\n📌 <b>Channel:</b> " . htmlspecialchars($title);
        } else {
            $reply = "📌 <b>Channel not found!</b>";
        }
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
    } elseif ($item['type'] === 'folder') {
        $folderName = $item['name'];
        $botData['folders'] = array_values(array_diff($botData['folders'], [$folderName]));
        foreach ($botData['channels'] as &$chan) {
            if (($chan['folder'] ?? '') === $folderName) {
                $chan['folder'] = '';
            }
        }
        if (($botData['current_folder'] ?? '') === $folderName) {
            $botData['current_folder'] = '';
        }
        saveBotData($dataFile, $botData);
        $reply = "✔️ <b>Folder Removed Successfully!</b>\n\n📁 <b>Removed Folder:</b> " . htmlspecialchars($folderName);
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($reply, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
    }
    
    unset($botData['pending_removal_items']);
    unset($botData['admin_states'][$userId]);
    saveBotData($dataFile, $botData);
    showCurrentFolder($chatId, $botToken, $botData);
}

// ==========================================
// 10. AI RESPONSE
// ==========================================

function getAIResponse($message, $aiResponses, $userId) {
    $message = strtolower(trim($message));
    
    foreach ($aiResponses as $key => $response) {
        if ($key !== 'default' && strpos($message, $key) !== false) {
            return $response;
        }
    }
    
    return $aiResponses['default'] ?? 'Please use /start to begin verification.';
}

// ==========================================
// 11. SHOW CURRENT FOLDER
// ==========================================

function showCurrentFolder($chatId, $botToken, $botData) {
    $folder = $botData['current_folder'] ?? '';
    $channels = $botData['channels'] ?? [];
    
    if (empty($folder)) {
        $text = "📁 <b>📂 No Active Folder</b>\n\n<i>Create a folder first or select an existing one.</i>\n\n";
    } else {
        $folderChannels = array_filter($channels, function($chan) use ($folder) {
            return ($chan['folder'] ?? '') == $folder;
        });
        $text = "📁 <b>📂 Current Folder: " . htmlspecialchars($folder) . "</b>\n\n";
        if (empty($folderChannels)) {
            $text .= "<i>No channels in this folder.</i>\n\n";
        } else {
            $text .= "📋 <b>Channels in this folder:</b>\n";
            $index = 0;
            foreach ($channels as $idx => $chan) {
                if (($chan['folder'] ?? '') == $folder) {
                    $index++;
                    $type = $chan['type'] ?? 'public';
                    $typeLabel = $type === 'private' ? '🔒' : '🌐';
                    $text .= ($index) . ". $typeLabel <b>" . htmlspecialchars($chan['title'] ?? $chan['id']) . "</b>\n";
                    $text .= "   🔗 " . htmlspecialchars($chan['link']) . "\n";
                }
            }
        }
    }
    
    $text .= "\n📌 <i>Select an option:</i>";
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '➕ Add Channel', 'callback_data' => 'adm_add_chan', 'style' => 'success'],
                ['text' => '❌ Remove Channel', 'callback_data' => 'adm_rem_chan', 'style' => 'danger']
            ],
            [
                ['text' => '🔢 Reorder', 'callback_data' => 'adm_reorder', 'style' => 'primary'],
                ['text' => '🔙 Back to Panel', 'callback_data' => 'adm_back', 'style' => 'primary']
            ],
            [
                ['text' => '🚪 Close', 'callback_data' => 'adm_close', 'style' => 'danger']
            ]
        ]
    ];
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    ], $botToken);
}

// ==========================================
// 12. CALLBACK HANDLER
// ==========================================

function handleCallbackQuery($callbackQuery, $botToken, $channels, $solvedPostLink, $premiumEmojis, $admins, &$botData, $dataFile, $folders, $folderButtons, $verificationSuccessMsg, $referralEnabled, $referralTarget, $userWelcomeMsg, &$verifiedUsers) {
    $callbackQueryId = $callbackQuery['id'];
    $userId = $callbackQuery['from']['id'];
    $chatId = $callbackQuery['message']['chat']['id'] ?? $userId;
    $messageId = $callbackQuery['message']['message_id'] ?? null;
    $data = $callbackQuery['data'] ?? '';

    $isAdmin = in_array((string)$userId, $admins);

    // ==========================================
    // TOGGLE SAVE MODE - FIXED
    // ==========================================
    if ($data === 'adm_toggle_save_mode') {
        if (!$isAdmin) {
            sendTelegramRequest('answerCallbackQuery', [
                'callback_query_id' => $callbackQueryId,
                'text' => '📌 Access Denied'
            ], $botToken);
            return;
        }
        $botData['save_mode'] = !($botData['save_mode'] ?? false);
        saveBotData($dataFile, $botData);
        $status = $botData['save_mode'] ? 'ON' : 'OFF';
        
        sendTelegramRequest('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => 'Save Mode turned ' . $status
        ], $botToken);
        
        showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
        return;
    }

    // ==========================================
    // CLEAR ALL AUTO DM
    // ==========================================
    if ($data === 'adm_clear_auto_dm') {
        if (!$isAdmin) {
            sendTelegramRequest('answerCallbackQuery', [
                'callback_query_id' => $callbackQueryId,
                'text' => '📌 Access Denied'
            ], $botToken);
            return;
        }
        $botData['auto_dm_messages'] = [];
        saveBotData($dataFile, $botData);
        sendTelegramRequest('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => 'All Auto DM messages cleared!'
        ], $botToken);
        showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
        return;
    }

    // ==========================================
    // ADMIN PANEL NAVIGATION
    // ==========================================
    if (strpos($data, 'adm_') === 0) {
        if (!$isAdmin) {
            sendTelegramRequest('answerCallbackQuery', [
                'callback_query_id' => $callbackQueryId,
                'text' => '📌 Access Denied'
            ], $botToken);
            return;
        }

        sendTelegramRequest('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => 'Processing...'
        ], $botToken);

        if ($data === 'adm_close') {
            sendTelegramRequest('deleteMessage', [
                'chat_id' => $chatId,
                'message_id' => $messageId
            ], $botToken);
            return;
        }
        
        if ($data === 'adm_full_info') {
            showFullInfo($chatId, $botToken, $botData);
            return;
        }
        
        if ($data === 'adm_back') {
            sendAdminPanel($chatId, $botToken);
            return;
        }
        
        if ($data === 'adm_reorder') {
            $totalChannels = count($botData['channels']);
            if ($totalChannels < 2) {
                sendTelegramRequest('answerCallbackQuery', [
                    'callback_query_id' => $callbackQueryId,
                    'text' => 'Need at least 2 channels!'
                ], $botToken);
                return;
            }
            $text = "🔢 <b>Reorder Channels</b>\n\n📋 <b>Current Order:</b>\n";
            foreach ($botData['channels'] as $index => $chan) {
                $type = $chan['type'] ?? 'public';
                $typeLabel = $type === 'private' ? '🔒' : '🌐';
                $title = $chan['title'] ?? $chan['id'];
                $text .= ($index + 1) . ". $typeLabel " . htmlspecialchars($title) . "\n";
            }
            $text .= "\n📩 Send numbers from 1 to " . $totalChannels . " separated by commas.\nExample: <code>3,1,2</code>";
            $botData['admin_states'][$userId] = 'reorder_channels';
            saveBotData($dataFile, $botData);
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($text, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
            return;
        }
        
        if ($data === 'folder_new') {
            $botData['admin_states'][$userId] = 'new_folder';
            saveBotData($dataFile, $botData);
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "📁 <b>Create New Folder</b>\n\nSend the name of the new folder:",
                'parse_mode' => 'HTML'
            ], $botToken);
            return;
        }
        
        // ==========================================
        // AUTO DM MANAGEMENT BUTTON
        // ==========================================
        if ($data === 'adm_manage_auto_dm') {
            $botData['admin_states'][$userId] = 'manage_auto_dm';
            saveBotData($dataFile, $botData);
            showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
            return;
        }
        
        if (strpos($data, 'delete_folder_btn_') === 0) {
            $index = intval(substr($data, 18));
            if (isset($botData['folder_buttons'][$index])) {
                unset($botData['folder_buttons'][$index]);
                $botData['folder_buttons'] = array_values($botData['folder_buttons']);
                saveBotData($dataFile, $botData);
                sendTelegramRequest('answerCallbackQuery', [
                    'callback_query_id' => $callbackQueryId,
                    'text' => 'Folder button deleted!'
                ], $botToken);
                sendAdminPanel($chatId, $botToken);
            }
            return;
        }
        
        if (strpos($data, 'edit_chan_') === 0) {
            $index = intval(substr($data, 10));
            if (isset($botData['channels'][$index])) {
                $botData['pending_edit_channel'][$userId] = ['index' => $index];
                saveBotData($dataFile, $botData);
                $chan = $botData['channels'][$index];
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "✏️ <b>Edit Channel Link</b>\n\n📌 <b>Channel:</b> " . htmlspecialchars($chan['title'] ?? $chan['id']) . "\n🔗 <b>Current Link:</b>\n" . htmlspecialchars($chan['link']) . "\n\n📩 <b>Send the new link:</b>",
                    'parse_mode' => 'HTML'
                ], $botToken);
            }
            return;
        }

        if ($data === 'adm_toggle_referral') {
            $botData['referral_enabled'] = !$botData['referral_enabled'];
            saveBotData($dataFile, $botData);
            $status = $botData['referral_enabled'] ? 'ENABLED' : 'DISABLED';
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "✔️ Referral System <b>" . $status . "</b> successfully!"
            ], $botToken);
            sendAdminPanel($chatId, $botToken);
            return;
        }
        
        if ($data === 'adm_set_referral_target') {
            $botData['admin_states'][$userId] = 'set_referral_target';
            saveBotData($dataFile, $botData);
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "🎯 <b>Set Referral Target</b>\n\nSend the number of referrals required:\n\nCurrent Target: <b>" . $botData['referral_target'] . "</b>",
                'parse_mode' => 'HTML'
            ], $botToken);
            return;
        }

        switch ($data) {
            case 'adm_add_chan':
                $keyboard = [
                    'inline_keyboard' => [
                        [
                            ['text' => '🌐 Public', 'callback_data' => 'adm_add_public', 'style' => 'primary'],
                            ['text' => '🔒 Private', 'callback_data' => 'adm_add_private', 'style' => 'primary']
                        ],
                        [
                            ['text' => '📁 Folder Button', 'callback_data' => 'adm_add_folder_btn', 'style' => 'success']
                        ],
                        [
                            ['text' => '📁 Current Folder: ' . ($botData['current_folder'] ?: 'None'), 'callback_data' => 'show_current_folder', 'style' => 'primary'],
                            ['text' => '❌ Cancel', 'callback_data' => 'adm_close', 'style' => 'danger']
                        ]
                    ]
                ];
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "🔖 <b>Select Option:</b>",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $keyboard
                ], $botToken);
                return;
                
            case 'show_current_folder':
                showCurrentFolder($chatId, $botToken, $botData);
                return;
                
            case 'adm_add_folder_btn':
                $botData['pending_folder_button'][$userId] = true;
                saveBotData($dataFile, $botData);
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "📁 <b>Create Folder Button</b>\n\nSend the Telegram addlist link:\nExample: <code>https://t.me/addlist/2YlzShQegy9kZDU1</code>",
                    'parse_mode' => 'HTML'
                ], $botToken);
                return;
                
            case 'adm_add_public':
                $botData['admin_states'][$userId] = 'add_chan_public';
                saveBotData($dataFile, $botData);
                $promptText = "🔖 <b>Add Public Channel</b>\n\nSend in this format (2 lines):\n\n<code>@channelusername</code>\n<code>https://t.me/channel</code>";
                break;
                
            case 'adm_add_private':
                $botData['admin_states'][$userId] = 'add_chan_private';
                saveBotData($dataFile, $botData);
                $promptText = "☯️ <b>Private Channel Setup</b>\n\nForward a message from the private channel:";
                break;
                
            case 'adm_rem_chan':
                showUnifiedRemoveList($chatId, $botToken, $botData, $premiumEmojis);
                return;
                
            case 'adm_edit_img':
                $botData['admin_states'][$userId] = 'edit_img';
                saveBotData($dataFile, $botData);
                $promptText = "☯️ <b>Edit Image Link</b>\n\nSend the new direct Image Link:";
                break;
                
            case 'adm_edit_solved':
                $botData['admin_states'][$userId] = 'edit_solved';
                saveBotData($dataFile, $botData);
                $promptText = "☯️ <b>Edit Solved Link</b>\n\nSend the new Solved Post Link:";
                break;
                
            case 'adm_edit_verification_msg':
                $botData['admin_states'][$userId] = 'edit_verification_msg';
                saveBotData($dataFile, $botData);
                $promptText = "✅ <b>Edit Verification Success Message</b>\n\nSend the new message:";
                break;
                
            case 'adm_edit_user_welcome':
                $botData['admin_states'][$userId] = 'edit_user_welcome';
                saveBotData($dataFile, $botData);
                $promptText = "🎉 <b>Edit User Welcome Message</b>\n\nSend the new welcome message:";
                break;
                
            case 'adm_edit_user_join':
                $botData['admin_states'][$userId] = 'edit_user_join';
                saveBotData($dataFile, $botData);
                $promptText = "🎯 <b>Edit User Join Notification</b>\n\nSend the new notification:";
                break;
                
            case 'adm_add_admin':
                $botData['admin_states'][$userId] = 'add_admin';
                saveBotData($dataFile, $botData);
                $promptText = "☯️ <b>Add Admin</b>\n\nSend the numeric User ID:";
                break;
                
            case 'adm_rem_admin':
                $botData['admin_states'][$userId] = 'rem_admin';
                saveBotData($dataFile, $botData);
                $promptText = "☯️ <b>Remove Admin</b>\n\nSend the numeric User ID to remove:";
                break;
                
            case 'adm_edit_welcome':
                $botData['admin_states'][$userId] = 'edit_welcome';
                saveBotData($dataFile, $botData);
                $promptText = "🔖 <b>Edit Welcome Message</b>\n\nSend the new welcome message:";
                break;
                
            case 'adm_edit_ai_response':
                $botData['admin_states'][$userId] = 'edit_ai_response';
                saveBotData($dataFile, $botData);
                $promptText = "🤖 <b>Edit AI Response</b>\n\nFormat: <code>key|response</code>\nExample: <code>hi|Hello!</code>";
                break;
                
            case 'adm_broadcast':
                $botData['admin_states'][$userId] = 'broadcast';
                saveBotData($dataFile, $botData);
                $totalUsers = count($botData['registered'] ?? []);
                $promptText = "📌 <b>Broadcast Message</b>\n\nSend message to <b>" . $totalUsers . "</b> users:";
                break;
                
            case 'adm_add_custom_btn':
                $botData['admin_states'][$userId] = 'add_custom_btn';
                saveBotData($dataFile, $botData);
                $promptText = "🔖 <b>Add Custom Button</b>\n\nFormat: url|Button Text|https://example.com|primary";
                break;
                
            case 'adm_clear_buttons':
                $botData['welcome_buttons'] = [];
                saveBotData($dataFile, $botData);
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "✔️ All custom welcome buttons removed."
                ], $botToken);
                showCurrentFolder($chatId, $botToken, $botData);
                return;
                
            case 'adm_reset_bot':
                $confirmText = "⚠️ <b>⚠️ DANGER: Reset Bot ⚠️</b>\n\nThis will <b>PERMANENTLY DELETE</b> all data:\n❌ All channels\n❌ All folders\n❌ All users\n❌ All referrals\n❌ All verification data\n\n📌 <b>Type 'yes' to confirm reset</b>";
                $botData['admin_states'][$userId] = 'confirm_reset';
                saveBotData($dataFile, $botData);
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => applyPremiumEmojis($confirmText, $premiumEmojis),
                    'parse_mode' => 'HTML'
                ], $botToken);
                return;
        }

        saveBotData($dataFile, $botData);
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($promptText, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }

    // ==========================================
    // CHECK JOINED - AUTO VERIFICATION
    // ==========================================
    if ($data === 'check_joined') {
        sendTelegramRequest('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => 'Checking status...'
        ], $botToken);

        $missingChannels = [];
        $activeChans = $botData['channels'] ?? [];
        $allJoined = true;

        if (empty($activeChans)) {
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "📌 <b>No channels configured!</b>\n\nPlease contact admin.",
                'parse_mode' => 'HTML'
            ], $botToken);
            return;
        }

        foreach ($activeChans as $chan) {
            $channelId = $chan['id'];
            $joined = false;
            try {
                $response = sendTelegramRequest('getChatMember', [
                    'chat_id' => $channelId,
                    'user_id' => $userId
                ], $botToken);
                if ($response && isset($response['ok']) && $response['ok'] === true) {
                    $status = $response['result']['status'] ?? '';
                    if (in_array($status, ['creator', 'administrator', 'member'])) {
                        $joined = true;
                    }
                }
            } catch (Exception $e) {
                $joined = false;
            }
            if (!$joined) {
                $missingChannels[] = $chan;
                $allJoined = false;
            }
        }

        if ($allJoined) {
            $isVerified = in_array((string)$userId, $verifiedUsers);
            if (!$isVerified) {
                $verifiedUsers[] = (string)$userId;
                $botData['verified_users'] = $verifiedUsers;
                saveBotData($GLOBALS['dataFile'], $botData);
            }
            
            $firstName = $callbackQuery['from']['first_name'] ?? 'User';
            
            // BUG FIX: only send userWelcomeMsg if it is not empty
            $userMsg = $userWelcomeMsg;
            $userMsg = str_replace('{first_name}', $firstName, $userMsg);
            if (!empty(trim($userMsg))) {
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => applyPremiumEmojis($userMsg, $premiumEmojis),
                    'parse_mode' => 'HTML'
                ], $botToken);
            }

            // BUG FIX: $GLOBALS['verificationSuccessMsg'] was never set as a global.
            // Use the local function parameter $verificationSuccessMsg instead.
            $successText = $verificationSuccessMsg;
            $successText = str_replace('{first_name}', $firstName, $successText);

            if ($referralEnabled) {
                $refCount = $botData['referrals'][$userId]['count'] ?? 0;
                $successText .= "\n\n💡 <b>Referral System:</b> You have referred <b>" . $refCount . "</b> users.";
            }

            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($successText, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);

            $postSent = attemptCopyMessage($chatId, $solvedPostLink, $botToken);
            if (!$postSent && !empty($solvedPostLink)) {
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "🔖 <a href='$solvedPostLink'>Click here</a>",
                    'parse_mode' => 'HTML'
                ], $botToken);
            }
        } else {
            $listText = "";
            foreach ($missingChannels as $idx => $chan) {
                $type = $chan['type'] ?? 'public';
                $typeLabel = $type === 'private' ? '🔒 Private' : '🌐 Public';
                $listText .= "• " . $typeLabel . " Channel " . ($idx + 1) . "\n";
            }
            $someJoinText = "📌 You still need to join these channels:\n\n" . $listText . "\n📌 Join them and click Check Joined again.";
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($someJoinText, $premiumEmojis),
                'parse_mode' => 'HTML',
                'reply_markup' => buildKeyboard($missingChannels, true)
            ], $botToken);
        }
    }
}

// ==========================================
// 13. UTILITY FUNCTIONS
// ==========================================

function sendTelegramRequest($method, $data = [], $token = '') {
    $url = "https://api.telegram.org/bot" . $token . "/" . $method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

function handleChatJoinRequest($chatJoinRequest, $botToken, $botUsername, $premiumEmojis, $admins, &$botData, $dataFile, $autoDmMessages) {
    // BUG FIX: Always read auto_dm_messages fresh from $botData so newly saved
    // messages are included, not just the stale snapshot passed at webhook dispatch time.
    $autoDmMessages = $botData['auto_dm_messages'] ?? $autoDmMessages;
    $user = $chatJoinRequest['from'];
    $userId = $user['id'];
    $chatId = $chatJoinRequest['chat']['id'] ?? null;
    $chatTitle = $chatJoinRequest['chat']['title'] ?? 'Channel';
    
    $isRegistered = isset($botData['registered'][$userId]) && $botData['registered'][$userId] === true;
    $requestKey = $chatId . '_' . $userId;
    if (isset($botData['processed_join_requests'][$requestKey])) {
        return;
    }
    
    $botData['processed_join_requests'][$requestKey] = true;
    saveBotData($dataFile, $botData);
    
    if (!$isRegistered) {
        // Send Auto DM messages
        if (!empty($autoDmMessages)) {
            sendAutoDmMessages($userId, $autoDmMessages, $botToken);
        }

        // Show channel buttons
        $keyboard = buildKeyboardWithCustomButtons($botData['channels'] ?? [], $botData['welcome_buttons'] ?? [], $botData['folder_buttons'] ?? [], true);
        sendTelegramRequest('sendMessage', [
            'chat_id' => $userId,
            'text' => " ",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ], $botToken);

        foreach ($admins as $adm) {
            $adminText = "📌 <b>New User Join Request!</b>\n\n" .
                         "📌 <b>Channel:</b> " . $chatTitle . "\n" .
                         "👤 <b>User ID:</b> <code>" . $userId . "</code>\n\n" .
                         "☯️ <i>" . count($autoDmMessages) . " Auto DM messages sent.</i>";
            sendTelegramRequest('sendMessage', [
                'chat_id' => $adm,
                'text' => applyPremiumEmojis($adminText, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
        }
    }
}

function attemptCopyMessage($targetChatId, $postLink, $botToken) {
    if (empty($postLink)) return false;
    $sourceChatId = null;
    $messageId = null;
    if (preg_match('~t\.me/c/(\d+)/(\d+)~', $postLink, $matches)) {
        $sourceChatId = '-100' . $matches[1];
        $messageId = $matches[2];
    } elseif (preg_match('~t\.me/([a-zA-Z0-9_]+)/(\d+)~', $postLink, $matches)) {
        $sourceChatId = '@' . $matches[1];
        $messageId = $matches[2];
    }
    if ($sourceChatId !== null && $messageId !== null) {
        $response = sendTelegramRequest('copyMessage', [
            'chat_id' => $targetChatId,
            'from_chat_id' => $sourceChatId,
            'message_id' => $messageId
        ], $botToken);
        return ($response && isset($response['ok']) && $response['ok'] === true);
    }
    return false;
}

function buildKeyboard($channels, $showCheckJoined = true) {
    $keyboard = [];
    $currentRow = [];
    foreach ($channels as $index => $chan) {
        $type = $chan['type'] ?? 'public';
        $label = $type === 'private' ? '🔒' : '📢';
        $folder = $chan['folder'] ?? 'No Folder';
        $currentRow[] = [
            'text' => $label . ' ' . $folder . ' ' . ($index + 1),
            'url' => $chan['link'],
            'style' => 'primary'
        ];
        if (count($currentRow) === 2) {
            $keyboard[] = $currentRow;
            $currentRow = [];
        }
    }
    if (!empty($currentRow)) {
        $keyboard[] = $currentRow;
    }
    if ($showCheckJoined) {
        $keyboard[] = [[
            'text' => '✅ Check Joined',
            'callback_data' => 'check_joined',
            'style' => 'success'
        ]];
    }
    return ['inline_keyboard' => $keyboard];
}

function buildKeyboardWithCustomButtons($channels, $customButtons, $folderButtons, $showCheckJoined = true) {
    $keyboard = [];
    $currentRow = [];
    foreach ($channels as $index => $chan) {
        $type = $chan['type'] ?? 'public';
        $label = $type === 'private' ? '🔒' : '📢';
        $folder = $chan['folder'] ?? 'No Folder';
        $currentRow[] = [
            'text' => $label . ' ' . $folder . ' ' . ($index + 1),
            'url' => $chan['link'],
            'style' => 'primary'
        ];
        if (count($currentRow) === 2) {
            $keyboard[] = $currentRow;
            $currentRow = [];
        }
    }
    if (!empty($currentRow)) {
        $keyboard[] = $currentRow;
    }
    if (!empty($folderButtons)) {
        $folderRow = [];
        foreach ($folderButtons as $index => $btn) {
            $folderRow[] = [
                'text' => '📁 Join All ' . ($index + 1),
                'url' => $btn['link'],
                'style' => 'success'
            ];
            if (count($folderRow) === 2) {
                $keyboard[] = $folderRow;
                $folderRow = [];
            }
        }
        if (!empty($folderRow)) {
            $keyboard[] = $folderRow;
        }
    }
    if (!empty($customButtons)) {
        $customRow = [];
        foreach ($customButtons as $button) {
            if (isset($button['type']) && $button['type'] === 'url') {
                $customRow[] = [
                    'text' => $button['text'] ?? 'Button',
                    'url' => $button['url'] ?? '#',
                    'style' => $button['style'] ?? 'primary'
                ];
            } elseif (isset($button['type']) && $button['type'] === 'callback') {
                $customRow[] = [
                    'text' => $button['text'] ?? 'Button',
                    'callback_data' => $button['callback_data'] ?? 'custom_action',
                    'style' => $button['style'] ?? 'primary'
                ];
            }
            if (count($customRow) === 2) {
                $keyboard[] = $customRow;
                $customRow = [];
            }
        }
        if (!empty($customRow)) {
            $keyboard[] = $customRow;
        }
    }
    if ($showCheckJoined) {
        $keyboard[] = [[
            'text' => '✅ Check Joined',
            'callback_data' => 'check_joined',
            'style' => 'success'
        ]];
    }
    return ['inline_keyboard' => $keyboard];
}

function processCustomButtonInput($text, &$botData, $dataFile) {
    $parts = explode('|', $text);
    if (count($parts) >= 3) {
        $type = trim($parts[0]);
        $label = trim($parts[1]);
        $value = trim($parts[2]);
        $style = isset($parts[3]) ? trim($parts[3]) : 'primary';
        $newButton = ['text' => $label, 'style' => $style];
        if ($type === 'url') {
            $newButton['type'] = 'url';
            $newButton['url'] = $value;
        } elseif ($type === 'callback') {
            $newButton['type'] = 'callback';
            $newButton['callback_data'] = $value;
        } else {
            return false;
        }
        $botData['welcome_buttons'][] = $newButton;
        saveBotData($dataFile, $botData);
        return "Custom button added successfully!";
    }
    return false;
}

// ==========================================
// 14. ADMIN PANEL
// ==========================================

function sendAdminPanel($chatId, $botToken) {
    $text = "🔰 <b>⚡ Admin Panel ⚡</b> 🔰\n\n<i>📌 Select an option below:</i>";
    $adminKeyboard = [
        'inline_keyboard' => [
            [
                ['text' => '📊 Full Info', 'callback_data' => 'adm_full_info', 'style' => 'primary'],
                ['text' => '📁 Create Folder', 'callback_data' => 'folder_new', 'style' => 'success']
            ],
            [
                ['text' => '➕ Add Channel', 'callback_data' => 'adm_add_chan', 'style' => 'success'],
                ['text' => '❌ Remove', 'callback_data' => 'adm_rem_chan', 'style' => 'danger']
            ],
            [
                ['text' => '🔢 Reorder', 'callback_data' => 'adm_reorder', 'style' => 'primary'],
                ['text' => '🖼️ Edit Image', 'callback_data' => 'adm_edit_img', 'style' => 'primary']
            ],
            [
                ['text' => '🔑 Edit Solved', 'callback_data' => 'adm_edit_solved', 'style' => 'primary'],
                ['text' => '✅ Edit Verification', 'callback_data' => 'adm_edit_verification_msg', 'style' => 'primary']
            ],
            [
                ['text' => '🎉 Edit User Welcome', 'callback_data' => 'adm_edit_user_welcome', 'style' => 'primary'],
                ['text' => '🎯 Edit User Join', 'callback_data' => 'adm_edit_user_join', 'style' => 'primary']
            ],
            [
                ['text' => '💌 Manage Auto DM', 'callback_data' => 'adm_manage_auto_dm', 'style' => 'primary']
            ],
            [
                ['text' => '👤 Add Admin', 'callback_data' => 'adm_add_admin', 'style' => 'primary'],
                ['text' => '❌ Remove Admin', 'callback_data' => 'adm_rem_admin', 'style' => 'danger']
            ],
            [
                ['text' => '✏️ Edit Welcome', 'callback_data' => 'adm_edit_welcome', 'style' => 'primary'],
                ['text' => '🤖 Edit AI', 'callback_data' => 'adm_edit_ai_response', 'style' => 'primary']
            ],
            [
                ['text' => '📢 Broadcast', 'callback_data' => 'adm_broadcast', 'style' => 'primary'],
                ['text' => '🎯 Toggle Referral', 'callback_data' => 'adm_toggle_referral', 'style' => 'primary']
            ],
            [
                ['text' => '🎯 Set Target', 'callback_data' => 'adm_set_referral_target', 'style' => 'primary'],
                ['text' => '➕ Add Button', 'callback_data' => 'adm_add_custom_btn', 'style' => 'primary']
            ],
            [
                ['text' => '🗑️ Clear Buttons', 'callback_data' => 'adm_clear_buttons', 'style' => 'danger'],
                ['text' => '🔄 RESET BOT', 'callback_data' => 'adm_reset_bot', 'style' => 'danger']
            ],
            [
                ['text' => '🚪 Close', 'callback_data' => 'adm_close', 'style' => 'danger']
            ]
        ]
    ];
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => $adminKeyboard
    ], $botToken);
}

// ==========================================
// 15. FULL INFO
// ==========================================

function showFullInfo($chatId, $botToken, $botData) {
    $registeredCount = count($botData['registered'] ?? []);
    $verifiedCount = count($botData['verified_users'] ?? []);
    $folders = $botData['folders'] ?? [];
    $autoDmMessages = $botData['auto_dm_messages'] ?? [];
    $saveMode = $botData['save_mode'] ?? false;
    
    $text = "📊 <b>⚡ Network Statistics ⚡</b> 📊\n\n" .
            "👥 Registered: <b>" . $registeredCount . "</b>\n" .
            "✅ Verified: <b>" . $verifiedCount . "</b>\n" .
            "📢 Channels: <b>" . count($botData['channels'] ?? []) . "</b>\n" .
            "📁 Folders: <b>" . count($folders) . "</b>\n" .
            "👤 Admins: <b>" . count($botData['admins']) . "</b>\n" .
            "💌 Auto DM: <b>" . count($autoDmMessages) . "</b>\n" .
            "📤 Save Mode: <b>" . ($saveMode ? '✅ ON' : '❌ OFF') . "</b>\n\n" .
            "🖼️ <b>Image:</b> " . htmlspecialchars($botData['imageUrl']) . "\n" .
            "🔑 <b>Solved:</b> " . htmlspecialchars($botData['solved_post_link']) . "\n";
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '🔙 Back', 'callback_data' => 'adm_back', 'style' => 'primary']
            ]
        ]
    ];

    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => $text,
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    ], $botToken);
}

function applyPremiumEmojis($text, $premiumEmojis) {
    if (empty($text)) return $text;
    foreach ($premiumEmojis as $emoji => $premium) {
        $text = str_replace($emoji, $premium, $text);
    }
    return $text;
}

// ==========================================
// 16. INITIALIZATION
// ==========================================

error_log("Bot @lose_recover_bot initialized");
error_log("Auto DM Messages: " . count($autoDmMessages));
error_log("Save Mode: " . ($saveMode ? 'ON' : 'OFF'));

?>
