<?php

return [
    'token-scopes' => [
        'user-read' => 'Read the details of the authenticated user',
        'user-create' => 'Create resources owned by the authenticated user',
        'user-update' => 'Update resources owned by the authenticated user',
        'verify-password' => "Verify a user's credentials - client credentials grant only",
        'admin-read' => 'Read any user detail - client credentials grant only',
        'admin-create' => 'Create users - client credentials grant only',
        'admin-update' => 'Update any user - client credentials grant only',
    ],
];