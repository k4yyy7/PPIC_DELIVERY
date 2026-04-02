# Font Files for Offline Use

This folder contains the font files for **Plus Jakarta Sans** and **Poppins** fonts.

## Required Font Files

To make the fonts work offline, you need to download the actual font files from Google Fonts.

### Plus Jakarta Sans (Weights: 400, 500, 600, 700, 800)
1. Go to https://fonts.google.com/specimen/Plus+Jakarta+Sans
2. Download all weights in WOFF2 format
3. Rename files to:
   - `plus-jakarta-sans-v12-latin-400.woff2`
   - `plus-jakarta-sans-v12-latin-500.woff2`
   - `plus-jakarta-sans-v12-latin-600.woff2`
   - `plus-jakarta-sans-v12-latin-700.woff2`
   - `plus-jakarta-sans-v12-latin-800.woff2`

### Poppins (Weights: 400, 700)
1. Go to https://fonts.google.com/specimen/Poppins
2. Download weights 400 and 700 in WOFF2 format
3. Files should be named:
   - `Poppins-Regular.woff2`
   - `Poppins-Bold.woff2`

## Quick Download from GitHub
You can also download the font files from Google's GitHub repository:
- https://github.com/google/fonts/tree/main/ofl/plusjakartasans
- https://github.com/google/fonts/tree/main/ofl/poppins

## Alternative: Use Google Fonts CDN (Online)
If you have internet connection, you can revert to using Google Fonts CDN by updating the CSS files to use:
```css
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
```

