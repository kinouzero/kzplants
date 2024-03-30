#!/bin/sh

# Npm install
npm i

# Build assets
npm run build

# Link node_modules directory in public directory
rm -rf public/npm && ln -s ../node_modules public/npm