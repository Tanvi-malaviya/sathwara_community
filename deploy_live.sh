#!/bin/bash
# ============================================================
# deploy_live.sh — Sathwara Community Portal
# Live Server Deployment Script
#
# USAGE:
#   chmod +x deploy_live.sh
#   ./deploy_live.sh
#
# FIRST TIME SETUP: Fill in the CONFIGURATION section below.
# ============================================================

set -e  # Exit immediately on any error

# ─── CONFIGURATION ──────────────────────────────────────────────
# Change these values to match your live server settings

DEPLOY_USER="root"                              # SSH username (e.g. root, ubuntu, sathwara)
DEPLOY_HOST="YOUR_SERVER_IP_OR_DOMAIN"         # e.g. 123.45.67.89 or yourdomain.com
DEPLOY_PATH="/var/www/html/sathwara_community"  # Absolute path on server
DEPLOY_BRANCH="main"                           # Git branch to deploy
PHP_BIN="php8.2"                               # PHP binary on server
COMPOSER_BIN="composer"                        # Composer binary
NPM_BIN="npm"                                  # npm binary

# Optional: SSH private key path (leave empty to use default ~/.ssh/id_rsa)
SSH_KEY=""

# ─────────────────────────────────────────────────────────────────

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

log_info()    { echo -e "${CYAN}  [INFO]${NC}  $1"; }
log_success() { echo -e "${GREEN}  [OK]${NC}    $1"; }
log_error()   { echo -e "${RED}  [ERROR]${NC} $1"; exit 1; }

echo ""
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BOLD}  🚀 Sathwara Community Portal — Live Deployment Script${NC}"
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "  Server : ${CYAN}${DEPLOY_USER}@${DEPLOY_HOST}${NC}"
echo -e "  Path   : ${CYAN}${DEPLOY_PATH}${NC}"
echo -e "  Branch : ${CYAN}${DEPLOY_BRANCH}${NC}"
echo ""
echo -e "${YELLOW}  Press ENTER to start deployment or Ctrl+C to cancel...${NC}"
read -r

# Build SSH options
SSH_OPTS="-o StrictHostKeyChecking=no"
if [ -n "$SSH_KEY" ]; then
    SSH_OPTS="$SSH_OPTS -i $SSH_KEY"
fi

# ─── DEPLOY COMMAND BLOCK ────────────────────────────────────────
log_info "Connecting to server and deploying..."

ssh $SSH_OPTS "${DEPLOY_USER}@${DEPLOY_HOST}" bash << REMOTE_SCRIPT
set -e

echo ""
echo "──────────────────────────────────────────────"
echo "  1/8  Navigating to project directory..."
echo "──────────────────────────────────────────────"
cd "${DEPLOY_PATH}" || { echo "ERROR: Path not found: ${DEPLOY_PATH}"; exit 1; }

echo ""
echo "──────────────────────────────────────────────"
echo "  2/8  Enabling maintenance mode..."
echo "──────────────────────────────────────────────"
${PHP_BIN} artisan down --message="Sathwara Community Portal is being updated. We will be back shortly!" --retry=30 2>/dev/null || true

echo ""
echo "──────────────────────────────────────────────"
echo "  3/8  Pulling latest code from Git..."
echo "──────────────────────────────────────────────"
git fetch --all
git reset --hard origin/${DEPLOY_BRANCH}
git pull origin ${DEPLOY_BRANCH}
echo "  Git pull done on branch: ${DEPLOY_BRANCH}"

echo ""
echo "──────────────────────────────────────────────"
echo "  4/8  Installing PHP dependencies..."
echo "──────────────────────────────────────────────"
${COMPOSER_BIN} install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "──────────────────────────────────────────────"
echo "  5/8  Building frontend assets..."
echo "──────────────────────────────────────────────"
if [ -f "package.json" ]; then
    ${NPM_BIN} ci --prefer-offline 2>/dev/null || ${NPM_BIN} install
    ${NPM_BIN} run build
    echo "  Frontend assets built."
else
    echo "  No package.json, skipping npm build."
fi

echo ""
echo "──────────────────────────────────────────────"
echo "  6/8  Running database migrations..."
echo "──────────────────────────────────────────────"
${PHP_BIN} artisan migrate --force

echo ""
echo "──────────────────────────────────────────────"
echo "  7/8  Clearing and rebuilding caches..."
echo "──────────────────────────────────────────────"
${PHP_BIN} artisan config:clear
${PHP_BIN} artisan cache:clear
${PHP_BIN} artisan route:clear
${PHP_BIN} artisan view:clear
${PHP_BIN} artisan config:cache
${PHP_BIN} artisan route:cache
${PHP_BIN} artisan view:cache
echo "  All caches cleared and rebuilt."

echo ""
echo "──────────────────────────────────────────────"
echo "  8/8  Fixing permissions..."
echo "──────────────────────────────────────────────"
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public 2>/dev/null || \
chown -R nginx:nginx storage bootstrap/cache public 2>/dev/null || \
echo "  (chown skipped — not root or user mismatch)"

if command -v systemctl &> /dev/null; then
    systemctl restart php8.2-fpm 2>/dev/null || \
    systemctl restart php8.1-fpm 2>/dev/null || \
    systemctl restart php-fpm 2>/dev/null || \
    echo "  (PHP-FPM restart skipped)"
fi

${PHP_BIN} artisan up

echo ""
echo "══════════════════════════════════════════════"
echo "  DEPLOYMENT COMPLETE!"
echo "══════════════════════════════════════════════"

REMOTE_SCRIPT

echo ""
echo -e "${GREEN}${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}${BOLD}  Deployment Successful!${NC}"
echo -e "${GREEN}${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "  Server:  ${DEPLOY_USER}@${DEPLOY_HOST}"
echo -e "  Branch:  ${DEPLOY_BRANCH}"
echo -e "  Time:    $(date '+%Y-%m-%d %H:%M:%S')"
echo ""
echo -e "  Check your site: ${CYAN}https://${DEPLOY_HOST}${NC}"
echo ""
