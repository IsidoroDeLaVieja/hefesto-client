#!/bin/bash
set -eu

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
CURRENT_API_PATH="$PWD"
API_NAME=$(basename "$CURRENT_API_PATH")
BUILD_DIR="$SCRIPT_DIR/build"

GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

if [ $# -eq 0 ]; then
    echo -e "${RED}Error: The correct format is: deploy-api.sh ENV${NC}"
    echo "Example: deploy-api.sh LOCAL"
    exit 1
fi

ENV_TARGET=$1

if [ -f "$SCRIPT_DIR/.env" ]; then
    export $(grep -v '^#' "$SCRIPT_DIR/.env" | xargs)
else
    echo -e "${YELLOW}Warning: .env file not found in $SCRIPT_DIR${NC}"
fi

DEPLOY_ENDPOINT_VAR="DEPLOY_ENDPOINT_${ENV_TARGET}"
API_KEY_VAR="API_KEY_${ENV_TARGET}"
PUBLIC_HOST_VAR="PUBLIC_HOST_${ENV_TARGET}"

DEPLOY_ENDPOINT="${!DEPLOY_ENDPOINT_VAR:-}"
API_KEY="${!API_KEY_VAR:-}"
PUBLIC_HOST="${!PUBLIC_HOST_VAR:-}"

if [ -z "$DEPLOY_ENDPOINT" ] || [ -z "$API_KEY" ] || [ -z "$PUBLIC_HOST" ]; then
    echo -e "${RED}Error: Missing environment variables for [${ENV_TARGET}] in your .env file${NC}"
    echo "Please ensure you have defined: $DEPLOY_ENDPOINT_VAR, $API_KEY_VAR and $PUBLIC_HOST_VAR"
    exit 1
fi

echo -e "${YELLOW}Preparing package for API '${API_NAME}'...${NC}"
rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR"

if [ -f "$CURRENT_API_PATH/api.yaml" ]; then
    cp "$CURRENT_API_PATH/api.yaml" "$BUILD_DIR/"
else
    echo -e "${RED}Critical Error: 'api.yaml' not found in the current directory.${NC}"
    exit 1
fi

for folder in Directives Maps Assets; do
    if [ -d "$CURRENT_API_PATH/$folder" ]; then
        cp -R "$CURRENT_API_PATH/$folder" "$BUILD_DIR/"
    fi
done

if [ -d "$SCRIPT_DIR/Directives" ] && ls "$SCRIPT_DIR/Directives"/*.php &> /dev/null; then
    mkdir -p "$BUILD_DIR/Directives"
    cp "$SCRIPT_DIR/Directives"/*.php "$BUILD_DIR/Directives/"
fi

cd "$BUILD_DIR"
tar -czvf "$API_NAME.tar.gz" * > /dev/null

echo -e "${YELLOW}Deploying to ${ENV_TARGET}...${NC}"

RESPONSE=$(curl -s -w "\n%{http_code}" --location --request POST "$DEPLOY_ENDPOINT" \
  --header "public-host-key: $API_KEY" \
  --header "public-host: $PUBLIC_HOST" \
  --form "file=@\"$BUILD_DIR/$API_NAME.tar.gz\"")

HTTP_STATUS=$(echo "$RESPONSE" | tail -n1)
BODY=$(echo "$RESPONSE" | sed '$d')

rm -f "$BUILD_DIR/$API_NAME.tar.gz"

if [ "$HTTP_STATUS" -ge 200 ] && [ "$HTTP_STATUS" -lt 300 ]; then
    echo -e "${GREEN}✔ API '${API_NAME}' deployed successfully to ${ENV_TARGET}! (HTTP $HTTP_STATUS)${NC}"
else
    echo -e "${RED}✘ Deployment failed. Server responded with HTTP $HTTP_STATUS${NC}"
    if [ ! -z "$BODY" ]; then
        echo -e "${RED}Server detail: $BODY${NC}"
    fi
    exit 1
fi