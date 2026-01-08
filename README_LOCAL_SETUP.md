# Local PHP Setup Guide for Testing Contact Form

## Option 1: Install PHP via Homebrew (Recommended for macOS)

### Step 1: Install Homebrew (if not already installed)
```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

### Step 2: Install PHP
```bash
brew install php
```

### Step 3: Verify Installation
```bash
php --version
```

### Step 4: Start PHP Built-in Server
Navigate to your project directory and run:
```bash
cd /Users/michaelyeboah/Desktop/Projects/sunshine-daycare-website
php -S localhost:8000
```

### Step 5: Access Your Site
Open your browser and go to:
```
http://localhost:8000/contact-us.html
```

---

## Option 2: Use MAMP (Easier GUI Option)

### Step 1: Download MAMP
1. Go to https://www.mamp.info/en/downloads/
2. Download MAMP for macOS
3. Install the application

### Step 2: Configure MAMP
1. Open MAMP
2. Click "Preferences"
3. Set Apache Port to `8888` (or keep default)
4. Set Document Root to: `/Users/michaelyeboah/Desktop/Projects/sunshine-daycare-website`
5. Click "Start Servers"

### Step 3: Access Your Site
Open your browser and go to:
```
http://localhost:8888/contact-us.html
```

---

## Option 3: Use XAMPP

### Step 1: Download XAMPP
1. Go to https://www.apachefriends.org/download.html
2. Download XAMPP for macOS
3. Install the application

### Step 2: Configure XAMPP
1. Open XAMPP Control Panel
2. Start Apache
3. Copy your project to: `/Applications/XAMPP/htdocs/sunshine-daycare-website`

### Step 3: Access Your Site
Open your browser and go to:
```
http://localhost/sunshine-daycare-website/contact-us.html
```

---

## Testing the Contact Form

### Important Notes for Local Testing:

1. **Email Functionality**: The `mail()` function in PHP requires a mail server. For local testing, you have a few options:

   **Option A: Use a Testing Service (Recommended)**
   - Use Mailtrap, MailHog, or similar service
   - Update the PHP handler to use SMTP instead of `mail()`

   **Option B: Mock the Response (Quick Testing)**
   - Temporarily modify `contact-form-handler.php` to always return success
   - This lets you test the form UI without sending actual emails

   **Option C: Use SMTP (Production-like)**
   - Install PHPMailer or similar library
   - Configure with Gmail SMTP or another email service

2. **CORS Issues**: If testing from `file://` protocol, you may encounter CORS issues. Always use `http://localhost` instead.

3. **Form Action URL**: Make sure the form action points to the correct path:
   ```html
   <form action="contact-form-handler.php" method="POST">
   ```

---

## Quick Test Script

Create a test file `test-contact.php` to verify PHP is working:

```php
<?php
echo "PHP is working!";
echo "<br>PHP Version: " . phpversion();
?>
```

Access it at: `http://localhost:8000/test-contact.php`

---

## Troubleshooting

### PHP Not Found
- Make sure PHP is in your PATH
- Try: `which php` to find PHP location
- Add to PATH if needed: `export PATH="/opt/homebrew/bin:$PATH"`

### Port Already in Use
- Change the port: `php -S localhost:8080`
- Or kill the process using the port

### Email Not Sending
- Check PHP error logs
- Use a mail testing service for local development
- Consider using PHPMailer with SMTP for better reliability

---

## Recommended: Use Mailtrap for Email Testing

1. Sign up at https://mailtrap.io (free tier available)
2. Get your SMTP credentials
3. Install PHPMailer:
   ```bash
   composer require phpmailer/phpmailer
   ```
4. Update `contact-form-handler.php` to use PHPMailer with Mailtrap SMTP

This way you can see all emails in Mailtrap's inbox without sending real emails.

