#!/bin/bash

set -euo pipefail

usage() {
    echo "Error: Missing API name."
    echo "Usage: create-api.sh API_NAME"
    exit 1
}

if [ $# -eq 0 ]; then
    usage
fi

API_NAME="$1"
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
TEMPLATE_DIR="$SCRIPT_DIR/api-template"
API_TARGET="$PWD/$API_NAME"

echo "==> Creating API: $API_NAME..."

if [ ! -d "$TEMPLATE_DIR" ]; then
    echo "Error: Template directory not found at $TEMPLATE_DIR"
    exit 1
fi

if [ -d "$API_TARGET" ]; then
    echo "Warning: Directory '$API_TARGET' already exists."
    read -p "Do you want to overwrite it? (y/N): " response
    if [[ ! "$response" =~ ^[yY]$ ]]; then
        echo "Operation cancelled by user."
        exit 0
    fi
fi

echo "--> Copying template..."
mkdir -p "$API_TARGET"
cp -R "$TEMPLATE_DIR"/* "$API_TARGET"

YAML_FILE="$API_TARGET/api.yaml"
if [ -f "$YAML_FILE" ]; then
    sed -i "s|#api-name#|$API_NAME|g" "$YAML_FILE"
else
    echo "Notice: api.yaml not found. Placeholder replacement skipped."
fi

echo "Success: API created at $API_TARGET"