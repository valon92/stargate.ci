#!/bin/bash

# Fix all Vue files with problematic SVG backgrounds
for file in src/views/*.vue; do
  if [ -f "$file" ]; then
    # Replace the problematic SVG background with a simple gradient
    sed -i '' 's/bg-\[url.*opacity-20\]/bg-gradient-to-br from-primary-900\/10 to-secondary-900\/10/g' "$file"
    echo "Fixed: $file"
  fi
done

echo "All files fixed!"
