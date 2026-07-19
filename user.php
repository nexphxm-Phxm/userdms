function loadBotData($filePath) {
    $readAndDecode = function($path) {
        if (!file_exists($path)) return false;
        for ($i = 0; $i < 5; $i++) {
            $content = @file_get_contents($path);
            if ($content !== false && trim($content) !== '') {
                $data = json_decode($content, true);
                if (is_array($data) && !empty($data)) {
                    return $data;
                }
            }
            usleep(200000); // wait 200ms
        }
        return false;
    };

    $data = $readAndDecode($filePath);
    
    if ($data === false) {
        $backupPath = dirname($filePath) . '/data5_backup.json';
        $data = $readAndDecode($backupPath);
        if ($data !== false) {
            error_log("Data restored from backup: " . $filePath);
        }
    }
    
    if ($data !== false) {
        $default = createFreshData(true);
        foreach ($default as $key => $value) {
            if (!array_key_exists($key, $data)) {
                $data[$key] = $value;
            }
        }
        return $data;
    }
    
    error_log("WARNING: Data file corrupt and no backup – creating fresh data for " . $filePath);
    return createFreshData();
}

function createFreshData($returnOnly = false) {
    $initialData = [
        'admins' => ['5157557268', '7177448473'],
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
    if (!$returnOnly) {
        saveBotData($GLOBALS['dataFile'], $initialData);
        error_log("Fresh data created for " . $GLOBALS['dataFile']);
    }
    return $initialData;
}

function saveBotData($filePath, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    if ($json === false) return false;
    
    // Save main file with lock to prevent corruption
    $result = file_put_contents($filePath, $json, LOCK_EX);
    if ($result === false) {
        error_log("ERROR: Failed to write data file: " . $filePath);
        return false;
    }
    // Also write backup with lock
    $backupPath = dirname($filePath) . '/data5_backup.json';
    file_put_contents($backupPath, $json, LOCK_EX);
    return true;
}
