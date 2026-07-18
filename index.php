<?php
/**
 * User Verification Bot 
 * With Auto DM System - FORWARD ANY MESSAGE TYPE
 * Bot Token: 8814977950:AAEr7T-rHx3jE8Dj7zEKmsszORCUBy3_vF4
 */

// ==========================================
// 1. CONFIGURATION
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
function loadBotData($filePath, $defAdmin, $defImage, $defChannels, $defSolved, $defVerifMsg) {
    if (!file_exists($filePath)) {
        return createFreshData($defAdmin, $defImage, $defChannels, $defSolved, $defVerifMsg);
    }
    return json_decode(file_get_contents($filePath), true);
}

function createFreshData($defAdmin, $defImage, $defChannels, $defSolved, $defVerifMsg) {
    $initialData = [
        'admins' => [$defAdmin],
        'imageUrl' => $defImage,
        'channels' => $defChannels,
        'folders' => [],
        'folder_buttons' => [],
        'solved_post_link' => $defSolved,
        'verification_success_msg' => $defVerifMsg,
        'referrals' => [],
        'registered' => [],
        'verified_users' => [],
        'admin_states' => [],
        'welcome_message' => '📌 Join the channels below and click Check Joined:',
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
            'hello' => 'Hi there! Welcome to the portal.',
            'help' => 'I can help you with:\n- Channel verification\n- Access management\n- Support\n- Referral system\n\nType /start to begin verification.',
            'status' => 'Your verification status will be checked when you click "Check Joined". Make sure you have joined all required channels.',
            'refer' => 'Use /refer to get your unique referral link and earn rewards for each new user you bring!',
            'default' => 'I understand you need assistance. Please use /start to begin verification or contact support.'
        ],
        'agent_requests' => [],
        'pending_agent_request' => [],
        'referral_enabled' => true,
        'referral_required' => false,
        'referral_target' => 3,
        'user_welcome_msg' => '🎉 <b>Welcome to the Network!</b>\n\nYou have successfully verified as a user.\n\n📌 <b>What you can do:</b>\n• Access premium content\n• Get exclusive updates\n• Connect with other members\n• Earn rewards through referrals\n\n💡 Use /refer to get your referral link!',
        'user_join_msg' => '🎯 <b>New User Alert!</b>\n\n👤 <b>User Name:</b> {first_name} {last_name}\n🆔 <b>User ID:</b> <code>{user_id}</code>\n🔗 <b>Username:</b> {username}\n\n📌 <b>Status:</b> ✅ Verified User\n📅 <b>Joined:</b> {date}',
        'pending_removal_items' => [],
        'forwarding_mode' => false
    ];
    file_put_contents($GLOBALS['dataFile'], json_encode($initialData, JSON_PRETTY_PRINT));
    return $initialData;
}

function saveBotData($filePath, $data) {
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

// Load data
$botData = loadBotData($dataFile, $defaultAdmin, $defaultImage, $defaultChannels, $defaultSolvedPost, $defaultVerificationMsg);

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
$forwardingMode = $botData['forwarding_mode'] ?? false;

$premiumEmojis = [
    '🔖' => '<tg-emoji emoji-id="6154668949549092628">🔖</tg-emoji>',
    '📌' => '<tg-emoji emoji-id="6154635564768300782">📌</tg-emoji>',
    '💌' => '<tg-emoji emoji-id="6267117597154612941">💌</tg-emoji>',
    '🎉' => '<tg-emoji emoji-id="6154462890630495777">🎉</tg-emoji>',
    '🎯' => '<tg-emoji emoji-id="6154462890630495778">🎯</tg-emoji>',
];

// ==========================================
// 3. WEBHOOK CONTROLLER
// ==========================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: text/html; charset=utf-8');
    echo "<h1>🤖 Bot Status: Active</h1>";
    echo "<p>👤 Admins: " . count($admins) . "</p>";
    echo "<p>📢 Channels: " . count($channels) . "</p>";
    echo "<p>💌 Auto DM Messages: " . count($autoDmMessages) . "</p>";
    echo "<p>📤 Forwarding Mode: " . ($forwardingMode ? 'ON' : 'OFF') . "</p>";
    
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
        echo "<p>📌 <a href='save.php'>Open save.php to save messages</a></p>";
    }
    
    echo "<hr>";
    echo "<p><strong>To set up webhook:</strong></p>";
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
    handleMessage($update['message'], $botToken, $imageUrl, $channels, $premiumEmojis, $admins, $botData, $dataFile, $welcomeMessage, $welcomeButtons, $autoDmMessages, $folders, $folderButtons, $verificationSuccessMsg, $aiResponses, $referralEnabled, $referralTarget, $userWelcomeMsg, $userJoinMsg, $verifiedUsers, $forwardingMode);
} elseif (isset($update['callback_query'])) {
    handleCallbackQuery($update['callback_query'], $botToken, $channels, $solvedPostLink, $premiumEmojis, $admins, $botData, $dataFile, $folders, $folderButtons, $verificationSuccessMsg, $referralEnabled, $referralTarget, $userWelcomeMsg, $verifiedUsers);
} elseif (isset($update['chat_join_request'])) {
    $me = sendTelegramRequest('getMe', [], $botToken);
    $botUser = $me['result']['username'] ?? 'MyVerificationBot';
    handleChatJoinRequest($update['chat_join_request'], $botToken, $botUser, $premiumEmojis, $admins, $botData, $dataFile, $autoDmMessages);
}

http_response_code(200);
exit;

// ==========================================
// 4. SAVE MESSAGE FUNCTIONS
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
        $data['supports_streaming'] = true;
    }
    
    // DOCUMENT (APK files)
    if (isset($message['document'])) {
        $data['document'] = $message['document']['file_id'];
        $data['caption'] = $message['caption'] ?? '';
        $data['file_name'] = $message['document']['file_name'] ?? 'file';
        $data['mime_type'] = $message['document']['mime_type'] ?? '';
    }
    
    // AUDIO
    if (isset($message['audio'])) {
        $data['audio'] = $message['audio']['file_id'];
        $data['caption'] = $message['caption'] ?? '';
        $data['performer'] = $message['audio']['performer'] ?? '';
        $data['title'] = $message['audio']['title'] ?? '';
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

function saveForwardedMessage($message, &$botData, $dataFile) {
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
// 5. SEND MESSAGE FUNCTIONS
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
            'caption' => $data['caption'] ?? '',
            'supports_streaming' => true
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
            'caption' => $data['caption'] ?? '',
            'performer' => $data['performer'] ?? '',
            'title' => $data['title'] ?? ''
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
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => "👋 Welcome! Please wait for admin to set up Auto DM messages.",
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }
    
    foreach ($autoDmMessages as $index => $msgData) {
        if ($msgData['type'] === 'copy') {
            $response = sendTelegramRequest('copyMessage', [
                'chat_id' => $chatId,
                'from_chat_id' => $msgData['from_chat_id'],
                'message_id' => $msgData['message_id']
            ], $botToken);
        } else {
            $response = sendSavedMessage($chatId, $msgData, $botToken);
        }
        
        if (!$response || !isset($response['ok']) || $response['ok'] !== true) {
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "⚠️ Message " . ($index + 1) . " could not be sent.",
                'parse_mode' => 'HTML'
            ], $botToken);
        }
        usleep(500000);
    }
}

// ==========================================
// 6. MESSAGE HANDLER
// ==========================================

function handleMessage($message, $botToken, $imageUrl, $channels, $premiumEmojis, $admins, &$botData, $dataFile, $welcomeMessage, $welcomeButtons, $autoDmMessages, $folders, $folderButtons, $verificationSuccessMsg, $aiResponses, $referralEnabled, $referralTarget, $userWelcomeMsg, $userJoinMsg, &$verifiedUsers, &$forwardingMode) {
    $chatId = $message['chat']['id'] ?? null;
    $text = trim($message['text'] ?? '');
    $userId = $message['from']['id'] ?? null;
    
    if (!$chatId || !$userId) return;

    $isAdmin = in_array((string)$userId, $admins);
    $forwardingMode = $botData['forwarding_mode'] ?? false;
    $isInState = isset($botData['admin_states'][$userId]);

    // ==========================================
    // FORWARDING MODE - Save ANY message sent to bot
    // ==========================================
    if ($isAdmin && $forwardingMode && !$isInState && strpos($text, '/') !== 0) {
        // Check for any content - text, photo, video, document, etc.
        $hasContent = isset($message['text']) || 
                      isset($message['photo']) || 
                      isset($message['video']) || 
                      isset($message['document']) || 
                      isset($message['audio']) || 
                      isset($message['voice']) || 
                      isset($message['sticker']) ||
                      isset($message['animation']);
        
        if ($hasContent) {
            $savedData = saveForwardedMessage($message, $botData, $dataFile);
            
            $reply = "✅ <b>Auto DM Message Saved!</b>\n\n";
            $reply .= "📌 Total messages: " . count($botData['auto_dm_messages']);
            
            // Send confirmation
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($reply, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);
            return;
        }
    }

    // ==========================================
    // COMMAND HANDLERS
    // ==========================================
    
    if ($text === '/start' || strpos($text, '/start') === 0) {
        $firstName = htmlspecialchars($message['from']['first_name'] ?? 'User');
        $lastName = isset($message['from']['last_name']) ? ' ' . htmlspecialchars($message['from']['last_name']) : '';
        $username = isset($message['from']['username']) ? '@' . htmlspecialchars($message['from']['username']) : 'None';

        $isExistingUser = isset($botData['registered'][$userId]) && $botData['registered'][$userId] === true;
        $isVerified = in_array((string)$userId, $botData['verified_users'] ?? []);
        
        if (!$isExistingUser) {
            $botData['registered'][$userId] = true;
            saveBotData($dataFile, $botData);
        }

        $keyboard = buildKeyboardWithCustomButtons($channels, $welcomeButtons, $folderButtons, true);
        
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => "📌 Join the channels below and click Check Joined:",
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
                    "📌 <b>Available Commands:</b>\n" .
                    "/start - Start verification\n" .
                    "/refer - Get referral link\n" .
                    "/admin - Admin panel (admins only)\n" .
                    "/help - Show this help";
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($helpText, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }

    // AI Response for non-command messages
    if (!$isAdmin && strpos($text, '/') !== 0 && !empty($text)) {
        $response = $aiResponses['default'] ?? 'Please use /start to begin verification.';
        sendTelegramRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => applyPremiumEmojis($response, $premiumEmojis),
            'parse_mode' => 'HTML'
        ], $botToken);
        return;
    }
}

function getBotUsername($botToken) {
    $me = sendTelegramRequest('getMe', [], $botToken);
    return $me['result']['username'] ?? 'MyVerificationBot';
}

// ==========================================
// 7. REFERRAL SYSTEM
// ==========================================

function handleReferralCommand($chatId, $userId, $botToken, $premiumEmojis, $botData, $dataFile) {
    $me = sendTelegramRequest('getMe', [], $botToken);
    $botUser = $me['result']['username'] ?? 'MyVerificationBot';
    $refCount = $botData['referrals'][$userId]['count'] ?? 0;
    $refLink = "https://t.me/" . $botUser . "?start=ref_" . $userId;
    
    $isVerified = in_array((string)$userId, $botData['verified_users'] ?? []);
    
    if ($isVerified) {
        $text = "🎯 <b>Your Referral Link:</b>\n\n" . $refLink . "\n\n📌 Total Referrals: " . $refCount;
    } else {
        $text = "📌 You need to verify first to get a referral link.\n\nClick 'Check Joined' to verify.";
    }
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => applyPremiumEmojis($text, $premiumEmojis),
        'parse_mode' => 'HTML'
    ], $botToken);
}

// ==========================================
// 8. CALLBACK HANDLER
// ==========================================

function handleCallbackQuery($callbackQuery, $botToken, $channels, $solvedPostLink, $premiumEmojis, $admins, &$botData, $dataFile, $folders, $folderButtons, $verificationSuccessMsg, $referralEnabled, $referralTarget, $userWelcomeMsg, &$verifiedUsers) {
    $callbackQueryId = $callbackQuery['id'];
    $userId = $callbackQuery['from']['id'];
    $chatId = $callbackQuery['message']['chat']['id'] ?? $userId;
    $data = $callbackQuery['data'] ?? '';

    $isAdmin = in_array((string)$userId, $admins);

    // ==========================================
    // CHECK JOINED - AUTO VERIFICATION
    // ==========================================
    if ($data === 'check_joined') {
        sendTelegramRequest('answerCallbackQuery', [
            'callback_query_id' => $callbackQueryId,
            'text' => 'Checking...'
        ], $botToken);

        $activeChans = $botData['channels'] ?? [];
        $allJoined = true;

        if (empty($activeChans)) {
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "📌 No channels configured! Contact admin.",
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
                $allJoined = false;
                break;
            }
        }

        if ($allJoined) {
            // Auto verify user
            if (!in_array((string)$userId, $verifiedUsers)) {
                $verifiedUsers[] = (string)$userId;
                $botData['verified_users'] = $verifiedUsers;
                saveBotData($GLOBALS['dataFile'], $botData);
            }
            
            $firstName = $callbackQuery['from']['first_name'] ?? 'User';
            $lastName = isset($callbackQuery['from']['last_name']) ? ' ' . $callbackQuery['from']['last_name'] : '';
            $username = isset($callbackQuery['from']['username']) ? '@' . $callbackQuery['from']['username'] : 'None';
            
            // Send welcome message
            $userMsg = $userWelcomeMsg;
            $userMsg = str_replace('{first_name}', $firstName, $userMsg);
            $userMsg = str_replace('{last_name}', $lastName, $userMsg);
            $userMsg = str_replace('{username}', $username, $userMsg);
            $userMsg = str_replace('{user_id}', $userId, $userMsg);
            
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($userMsg, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);

            // Send verification success
            $successText = $GLOBALS['verificationSuccessMsg'];
            $successText = str_replace('{first_name}', $firstName, $successText);
            $successText = str_replace('{last_name}', $lastName, $successText);
            $successText = str_replace('{username}', $username, $successText);
            $successText = str_replace('{user_id}', $userId, $successText);

            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => applyPremiumEmojis($successText, $premiumEmojis),
                'parse_mode' => 'HTML'
            ], $botToken);

            // Send solved post
            $postSent = attemptCopyMessage($chatId, $solvedPostLink, $botToken);
            if (!$postSent && !empty($solvedPostLink)) {
                sendTelegramRequest('sendMessage', [
                    'chat_id' => $chatId,
                    'text' => "🔖 Click below to view:",
                    'parse_mode' => 'HTML',
                    'reply_markup' => [
                        'inline_keyboard' => [[
                            ['text' => 'Open Post', 'url' => $solvedPostLink, 'style' => 'success']
                        ]]
                    ]
                ], $botToken);
            }
        } else {
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "📌 You still need to join all channels.\n\nJoin them and click Check Joined again.",
                'parse_mode' => 'HTML',
                'reply_markup' => buildKeyboard($activeChans, true)
            ], $botToken);
        }
        return;
    }

    // Admin panel callbacks
    if (strpos($data, 'adm_') === 0) {
        if (!$isAdmin) {
            sendTelegramRequest('answerCallbackQuery', [
                'callback_query_id' => $callbackQueryId,
                'text' => 'Access Denied'
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
                'message_id' => $callbackQuery['message']['message_id']
            ], $botToken);
            return;
        }

        if ($data === 'adm_back') {
            sendAdminPanel($chatId, $botToken);
            return;
        }

        if ($data === 'adm_manage_auto_dm') {
            showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
            return;
        }

        if ($data === 'adm_toggle_forwarding') {
            $botData['forwarding_mode'] = !($botData['forwarding_mode'] ?? false);
            saveBotData($dataFile, $botData);
            $status = $botData['forwarding_mode'] ? 'ON' : 'OFF';
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "📤 Forwarding mode turned <b>" . $status . "</b>\n\n" .
                          "Now " . ($botData['forwarding_mode'] ? 'forward any message to me to save it!' : 'forwarding is disabled.'),
                'parse_mode' => 'HTML'
            ], $botToken);
            showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
            return;
        }

        if ($data === 'adm_clear_auto_dm') {
            $botData['auto_dm_messages'] = [];
            saveBotData($dataFile, $botData);
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "✅ All Auto DM messages cleared!",
                'parse_mode' => 'HTML'
            ], $botToken);
            showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis);
            return;
        }

        if ($data === 'adm_add_chan') {
            sendTelegramRequest('sendMessage', [
                'chat_id' => $chatId,
                'text' => "🔖 <b>Add Channel</b>\n\nSend the channel link:\nExample: <code>https://t.me/channel</code>",
                'parse_mode' => 'HTML'
            ], $botToken);
            return;
        }

        // ... other admin functions remain the same
    }
}

// ==========================================
// 9. UTILITY FUNCTIONS
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
    $user = $chatJoinRequest['from'];
    $userId = $user['id'];
    $chatId = $chatJoinRequest['chat']['id'] ?? null;
    
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
            'text' => "📌 Join the channels below and click Check Joined:",
            'parse_mode' => 'HTML',
            'reply_markup' => $keyboard
        ], $botToken);
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
        $label = '📢 ' . ($index + 1);
        $currentRow[] = ['text' => $label, 'url' => $chan['link'], 'style' => 'primary'];
        if (count($currentRow) === 2) {
            $keyboard[] = $currentRow;
            $currentRow = [];
        }
    }
    if (!empty($currentRow)) $keyboard[] = $currentRow;
    if ($showCheckJoined) {
        $keyboard[] = [['text' => '✅ Check Joined', 'callback_data' => 'check_joined', 'style' => 'success']];
    }
    return ['inline_keyboard' => $keyboard];
}

function buildKeyboardWithCustomButtons($channels, $customButtons, $folderButtons, $showCheckJoined = true) {
    $keyboard = [];
    $currentRow = [];
    foreach ($channels as $index => $chan) {
        $label = '📢 ' . ($index + 1);
        $currentRow[] = ['text' => $label, 'url' => $chan['link'], 'style' => 'primary'];
        if (count($currentRow) === 2) {
            $keyboard[] = $currentRow;
            $currentRow = [];
        }
    }
    if (!empty($currentRow)) $keyboard[] = $currentRow;
    if (!empty($folderButtons)) {
        $folderRow = [];
        foreach ($folderButtons as $btn) {
            $folderRow[] = ['text' => '📁 Join All', 'url' => $btn['link'], 'style' => 'success'];
        }
        if (!empty($folderRow)) $keyboard[] = $folderRow;
    }
    if ($showCheckJoined) {
        $keyboard[] = [['text' => '✅ Check Joined', 'callback_data' => 'check_joined', 'style' => 'success']];
    }
    return ['inline_keyboard' => $keyboard];
}

// ==========================================
// 10. AUTO DM MANAGEMENT
// ==========================================

function showAutoDmMessages($chatId, $botToken, $botData, $premiumEmojis) {
    $messages = $botData['auto_dm_messages'] ?? [];
    $forwardingMode = $botData['forwarding_mode'] ?? false;
    
    $text = "💌 <b>Auto DM Messages</b>\n\n";
    $text .= "📤 Forwarding Mode: <b>" . ($forwardingMode ? '✅ ON' : '❌ OFF') . "</b>\n\n";
    
    if (empty($messages)) {
        $text .= "<i>No messages saved yet.</i>\n\n";
        $text .= "📌 <b>How to save:</b>\n";
        $text .= "1. Turn ON forwarding mode below\n";
        $text .= "2. Forward any message to the bot\n";
        $text .= "3. The message will be saved\n";
    } else {
        $text .= "<b>Saved Messages:</b> " . count($messages) . "\n\n";
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
        $text .= "\n📌 To remove: Type <code>remove|number</code>\n";
    }
    
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => $forwardingMode ? '🔄 Turn OFF' : '🔄 Turn ON', 
                 'callback_data' => 'adm_toggle_forwarding', 'style' => $forwardingMode ? 'danger' : 'success']
            ],
            [
                ['text' => '🗑️ Clear All', 'callback_data' => 'adm_clear_auto_dm', 'style' => 'danger'],
                ['text' => '🔙 Back', 'callback_data' => 'adm_back', 'style' => 'primary']
            ]
        ]
    ];
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => applyPremiumEmojis($text, $premiumEmojis),
        'parse_mode' => 'HTML',
        'reply_markup' => $keyboard
    ], $botToken);
}

// ==========================================
// 11. ADMIN PANEL
// ==========================================

function sendAdminPanel($chatId, $botToken) {
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => '➕ Add Channel', 'callback_data' => 'adm_add_chan', 'style' => 'success'],
                ['text' => '💌 Auto DM Messages', 'callback_data' => 'adm_manage_auto_dm', 'style' => 'primary']
            ],
            [
                ['text' => '🚪 Close', 'callback_data' => 'adm_close', 'style' => 'danger']
            ]
        ]
    ];
    
    sendTelegramRequest('sendMessage', [
        'chat_id' => $chatId,
        'text' => "🔰 <b>⚡ Admin Panel ⚡</b>\n\nSelect an option:",
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
// 12. ERROR LOGGING
// ==========================================

error_log("Bot initialized with " . count($admins) . " admins, " . count($channels) . " channels, " . count($autoDmMessages) . " Auto DM messages");
error_log("Forwarding Mode: " . ($forwardingMode ? 'ON' : 'OFF'));

?>
