# Tier List Block Plugin — Scope

## Overview

A WordPress block plugin that enables authors to create TierMaker-style tier lists natively in the block editor. The frontend output is static HTML — the interactive experience is editor-only. Target distribution is WordPress.org.

---

## Blocks

### 1. `tier-list/tier-list`

The outer wrapper block. Contains all tier rows.

- **InnerBlocks** — allows only `tier-list/tier-item`
- **Default template** — pre-inserts five `tier-item` blocks (S, A, B, C, D) with matching label colors (see below)
- **Color support** — background color control via `supports.color.background`, defaults to `#1A1A17`
- **Layout** — renders rows stacked vertically; CSS handles structure

---

### 2. `tier-list/tier-item`

A single tier row. Template-locked to always contain exactly one `tier-label` and one `tier-content`, in that order.

- **InnerBlocks** — `templateLock="all"`, template: `[tier-label, tier-content]`
- **No attributes of its own** — label text and color live on `tier-label`
- **Layout** — renders as a horizontal row: label cell on the left, content zone on the right
- **Ordering** — handled entirely by native Gutenberg block mover controls; no custom DnD

---

### 3. `tier-list/tier-label`

The left cell of a tier row. Displays the tier rank label (S, A, B, etc.).

- **InnerBlocks** — default template inserts a `core/paragraph` pre-filled with the tier letter; author can edit freely or swap for any block
- **Color support** — background and text color via `supports.color` (`background: true`, `text: true`); uses native WordPress color UI (palette + custom picker)
- **Default background colors** (applied via `tier-item` template when rows are pre-inserted):

  | Row | Background |
  |-----|------------|
  | S   | `#FF7F7F`  |
  | A   | `#FEBF7F`  |
  | B   | `#FFDF80`  |
  | C   | `#FFFF7F`  |
  | D   | `#C0FF7F`  |

- **Text color default** — author-controlled; no preset enforced
- **Layout** — fixed-width left cell, centered content

---

### 4. `tier-list/tier-content`

The right cell of a tier row. Holds the ranked items for that tier.

- **InnerBlocks** — unrestricted; any block can be placed here
- **No color or typography supports** — intentionally bare; inherits theme styles
- **Layout** — fills remaining row width, wraps items horizontally (flexbox row, wrap)

---

## File Structure

```
tier-list/
├── tier-list.php
├── package.json
├── readme.txt
└── src/
    ├── tier-list/
    │   ├── block.json
    │   ├── edit.js
    │   ├── save.js
    │   └── style.scss
    ├── tier-item/
    │   ├── block.json
    │   ├── edit.js
    │   ├── save.js
    │   └── style.scss
    ├── tier-label/
    │   ├── block.json
    │   ├── edit.js
    │   ├── save.js
    │   └── style.scss
    └── tier-content/
        ├── block.json
        ├── edit.js
        ├── save.js
        └── style.scss
```

---

## Technical Notes

- **API version** — `apiVersion: 3` on all blocks
- **Build tooling** — `@wordpress/scripts`; no custom webpack config
- **Frontend** — static HTML output from `save.js`; no frontend JavaScript bundle
- **WordPress.org compliance** — GPL-2.0-or-later license, no minified source without originals, all dependencies via npm

