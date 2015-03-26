# Jegan_EmailCoupon
Extension for send coupon codes in email for Magento 

  Procedure to install
  
  1. Simple copy the app folder and paste in magento root directory.
  2. Goto var->cache and delete all cache files
  3. Now goto your magento admin panel, you will see JEGAN Email Coupon Plugin.
  
# Features

  1. Send Coupon codes automatically while the email transcations occur.
  2. Enable and disable the features individually and having settings for that.
  3. This extension support 4 features. Send coupon codes on
    1. Newsletter subscribtion success.
    2. First order confirmation success.
    3. Become a new customer of our site.
    4. We can create an events and then select customers manually to offer the discount.

# Newsletter subscribtion

  1. To create new email template for send coupon code goto System->Transactional Emails
  2. Click 'Add New Template' buttton.
  3. From there load default template for newsletter success email.
  4. And Edit that template as your wish.
  5. Choose the 'Email Template' as created template name in System->Configuration->(JEGAN EMAIL COUPON) tab->Newsletter section
  
Place this belowe line of code to show the coupon code.
        
        {{var subscriber.getCouponCode()}}

# First order

  1. To create new email template for send coupon code goto System->Transactional Emails
  2. Click 'Add New Template' buttton.
  3. From there load default template for order confirmation email.
  4. And Edit that template as your wish.
  5. Choose the 'Email Template' as created template name in System->Configuration->(JEGAN EMAIL COUPON) tab->First order section
  
Place this belowe line of code to show the coupon code.
        
         {{var couponCode }}

# Become a new customer

  1. To create new email template for send coupon code goto System->Transactional Emails
  2. Click 'Add New Template' buttton.
  3. From there load default template for account confirmation email.
  4. And Edit that template as your wish.
  5. Choose the 'Email Template' as created template name in System->Configuration->(JEGAN EMAIL COUPON) tab->Become a new customer section
  
Place this belowe line of code to show the coupon code.
        
         {{var  customer.couponCode}}

# New Event

  1. To create new email template for send coupon code goto System->Transactional Emails
  2. Click 'Add New Template' buttton.
  3. From there load any default templates.
  4. And Edit that template as your wish.
  5. Choose the 'Email Template' as created template name in System->Configuration->(JEGAN EMAIL COUPON) tab->New Event section
  
Place this belowe line of code to show the coupon code.
        
        {{var couponCode }}
