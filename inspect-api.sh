#!/bin/bash
set -eu

if [ ! -f "api.yaml" ]; then
    echo "Error: api.yaml not found in the current directory." >&2
    exit 1
fi

API_NAME=$(basename "$PWD")
OUTPUT_FILE="${API_NAME}.txt"
MAX_BYTES=51200

> "$OUTPUT_FILE"

echo "api.yaml" >> "$OUTPUT_FILE"
cat "api.yaml" >> "$OUTPUT_FILE"
echo "" >> "$OUTPUT_FILE"

if [ -d "Directives" ]; then
    find Directives -maxdepth 1 -type f -name "*.php" 2>/dev/null | sort | while read -r file; do
        echo "$file" >> "$OUTPUT_FILE"
        FILE_SIZE=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
        if [ "$FILE_SIZE" -gt "$MAX_BYTES" ]; then
            echo "[Content hidden: File size is too large]" >> "$OUTPUT_FILE"
        else
            cat "$file" >> "$OUTPUT_FILE"
        fi
        echo "" >> "$OUTPUT_FILE"
    done
fi

if [ -d "Maps" ]; then
    find Maps -maxdepth 1 -type f -name "*.json" 2>/dev/null | sort | while read -r file; do
        echo "$file" >> "$OUTPUT_FILE"
        FILE_SIZE=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
        if [ "$FILE_SIZE" -gt "$MAX_BYTES" ]; then
            echo "[Content hidden: File size is too large]" >> "$OUTPUT_FILE"
        else
            cat "$file" >> "$OUTPUT_FILE"
        fi
        echo "" >> "$OUTPUT_FILE"
    done

    find Maps -mindepth 2 -type f 2>/dev/null | sort | while read -r file; do
        echo "$file" >> "$OUTPUT_FILE"
        echo "[Content hidden: Protected information]" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    done

    find Maps -mindepth 2 -type d 2>/dev/null | sort | while read -r dir; do
        echo "$dir" >> "$OUTPUT_FILE"
        echo "" >> "$OUTPUT_FILE"
    done
fi

if [ -d "Assets" ]; then
    find Assets -type f 2>/dev/null | sort | while read -r file; do
        echo "$file" >> "$OUTPUT_FILE"
        
        FILE_SIZE=$(stat -c%s "$file" 2>/dev/null || stat -f%z "$file")
        FILENAME_LOWER=$(echo "$file" | tr '[:upper:]' '[:lower:]')

        if [[ "$FILENAME_LOWER" =~ \.(png|jpg|jpeg|gif|ico|svg|webp)$ ]]; then
            echo "[Content hidden: Binary media file type]" >> "$OUTPUT_FILE"
        elif [ "$FILE_SIZE" -gt "$MAX_BYTES" ]; then
            echo "[Content hidden: File size is too large]" >> "$OUTPUT_FILE"
        else
            cat "$file" >> "$OUTPUT_FILE"
        fi
        echo "" >> "$OUTPUT_FILE"
    done
fi

echo "Inspection file generated successfully: $OUTPUT_FILE"