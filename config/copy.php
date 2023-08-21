<?php

return [
    'messages' => [
        'create_info' => 'Start your thread. Then when you click save the LLM will start to reply to you.',
        'update_info' => 'Update the thread, this will trigger a new build and remove all previous replies',
        'show_info' => 'You can trigger the query or reply to get more info. Below this area you will see scheduled tasks and more',
    ],
    'meta_data' => [
        'create_info' => 'Here you can add data about yourself, social links, sites etc that you want to add to messages you send the LLM assistant',
    ],
    'llm_functions' => [
        'create_info' => 'Here you can create LLM Functions that can then be used on your threads. When added to a thread the LLM will use them. To enable them in the system just add them to your app/helpers.php file',
    ],
];
