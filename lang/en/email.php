<?php
return [
    'verification' => [
        'subject' => 'Verify Email Address',
        'message' => '<h2>Hi :name</h2>
<p>Please click the button below to verify your email address.</p>
<p class="text-center"><a href=":url" class="action-btn button button-primary" target="_blank">Verify Email Address</a></p>
<p>If you did not create an account, no further action is required.</p>
Regards,<br>
:site_name.'
    ],
    'reset_password' => [
        'subject' => 'Reset Password Notification',
        'message' => '<h2>Hello :name!</h2>
<p>You are receiving this email because we received a password reset request for your account.</p>
<p class="text-center"><a href=":url" class="action-btn button button-primary" target="_blank">Reset Password</a></p>
<p>This password reset link will expire in 60 minutes.</p>
<p>If you did not request a password reset, no further action is required.</p>
Regards,<br>
:site_name.'
    ],
    'pre_order_query' => [
        'subject' => 'You have received a query for :service_name',
        'message' => '<h2>Dear Admin,</h2>
<p>:name has sent you a query regarding your service: <a href=":url" target="_blank">:service_name</a>.</p>
<p>:content</p>
<p class="text-center"></p>
Regards,<br>
:site_name.'
    ],
    'order_created' => [
        'subject' => 'Your :site_name order receipt from :order_date',
        'message' => '<h2>Thank you for your order</h2>
<p>We will get started on your order right away. You should be receiving an order confirmation email shortly. You can view order details by clicking the link below:</p>
<p class="text-center"><a href=":url" class="action-btn button button-primary" target="_blank">View Order</a></p>
Regards,<br>
:site_name.'
    ],
    'order_processing' => [
        'subject' => 'Your :site_name order receipt from :order_date is processing now',
        'message' => '<h2>Your order is processing.</h2>
<p>Hi there. Your recent order on :site_name is now being processed. You can view order details by clicking the link below:</p>
<p class="text-center"><a href=":url" class="action-btn button button-primary" target="_blank">View Order</a></p>
Regards,<br>
:site_name.'
    ],
    'order_completed' => [
        'subject' => 'Your :site_name order receipt from :order_date is completed',
        'message' => '<h2>Your order is completed.</h2>
<p>Hi there. Your recent order on :site_name has been marked as completed. You can view order details by clicking the link below:</p>
<p class="text-center"><a href=":url" class="action-btn button button-primary" target="_blank">View Order</a></p>
Regards,<br>
:site_name.'
    ],
    'message_received' => [
        'subject' => ':sender has sent you a message. REF: Order#:order_id',
        'message' => '<h2>Dear :receiver.</h2>
<p>:sender has just posted a reply in Order#:order_id. To view or add a reply click the link below:</p>
<p class="text-center"><a href=":url" class="action-btn button button-primary" target="_blank">View Message</a></p>
Regards,<br>
:site_name.'
    ],
    'welcome' => [
        'subject' => 'Welcome to :site_name!',
        'message' => '<h2>Dear :name</h2>
<p>Welcome to :site_name! We\'re thrilled to have you on board. 🎉</p>
<p>Your journey with us is about to begin, and we\'re excited to have you on board.</p>
<p>If you have any questions or need assistance, feel free to reach out. We\'re here to support you!</p>
<p>Thank you for joining :site_name. Here\'s to an amazing journey ahead!</p>
Regards,<br>
:site_name.',
    ]
];
