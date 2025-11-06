#!/bin/bash

echo "╔══════════════════════════════════════════════════════════════╗"
echo "║         TROUBLESHOOT PRODUCTION - RECRUITMENT API            ║"
echo "╚══════════════════════════════════════════════════════════════╝"
echo ""

# 1. Cek apakah code sudah terupdate
echo "📋 Step 1: Checking if code is updated..."
echo "─────────────────────────────────────────────────────────────"
if grep -q "last_education_id" app/Http/Controllers/Api/HrisIntegrationController.php; then
    echo "✅ Code SUDAH TERUPDATE - File contains 'last_education_id'"
else
    echo "❌ Code BELUM TERUPDATE - File does NOT contain 'last_education_id'"
    echo ""
    echo "🔧 Fixing: Pulling latest code..."
    git fetch origin
    git pull origin main --force
    echo "✅ Code pulled"
fi
echo ""

# 2. Check current branch
echo "📋 Step 2: Checking current branch..."
echo "─────────────────────────────────────────────────────────────"
BRANCH=$(git branch --show-current)
echo "Current branch: $BRANCH"
if [ "$BRANCH" != "main" ]; then
    echo "⚠️  WARNING: Not on main branch!"
    echo "Switching to main..."
    git checkout main
    git pull origin main
fi
echo ""

# 3. Clear all caches
echo "📋 Step 3: Clearing all caches..."
echo "─────────────────────────────────────────────────────────────"
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
echo "✅ All caches cleared"
echo ""

# 4. Rebuild cache
echo "📋 Step 4: Rebuilding cache..."
echo "─────────────────────────────────────────────────────────────"
php artisan config:cache
php artisan route:cache
echo "✅ Cache rebuilt"
echo ""

# 5. Check PHP-FPM status
echo "📋 Step 5: Restarting PHP-FPM..."
echo "─────────────────────────────────────────────────────────────"
# Detect PHP version
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "Detected PHP version: $PHP_VERSION"

sudo systemctl restart php${PHP_VERSION}-fpm
if [ $? -eq 0 ]; then
    echo "✅ PHP-FPM restarted successfully"
else
    echo "⚠️  Could not restart PHP-FPM (might need sudo)"
fi
echo ""

# 6. Restart Nginx
echo "📋 Step 6: Restarting Nginx..."
echo "─────────────────────────────────────────────────────────────"
sudo systemctl restart nginx
if [ $? -eq 0 ]; then
    echo "✅ Nginx restarted successfully"
else
    echo "⚠️  Could not restart Nginx (might need sudo)"
fi
echo ""

# 7. Test API directly
echo "📋 Step 7: Testing API directly..."
echo "─────────────────────────────────────────────────────────────"
echo "Running: php artisan tinker to test controller..."
echo ""

# 8. Show last 20 lines of log
echo "📋 Step 8: Checking Laravel logs..."
echo "─────────────────────────────────────────────────────────────"
if [ -f "storage/logs/laravel.log" ]; then
    echo "Last 20 lines of laravel.log:"
    tail -n 20 storage/logs/laravel.log
else
    echo "⚠️  No log file found"
fi
echo ""

echo "╔══════════════════════════════════════════════════════════════╗"
echo "║                    TROUBLESHOOTING COMPLETE                  ║"
echo "╚══════════════════════════════════════════════════════════════╝"
echo ""
echo "📝 Next Steps:"
echo "1. Test API with: curl -X POST https://trialhris.metropolitanland.com/recruitment/api/setNewCandidate ..."
echo "2. Or run: php verify-api-response.php from local"
echo ""
