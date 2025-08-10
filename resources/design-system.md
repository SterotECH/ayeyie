# Ayeyie Poultry Feed Design System

A clean and focused design system for the Ayeyie Poultry Feed application, using Tailwind CSS v4 with modern OKLCH colors and automatic light/dark mode support.

## Color Palette

### Light Mode Colors

#### Core Brand Colors
```css
--color-primary     /* Warm Orange - grain/feed theme */
--color-secondary   /* Forest Green - natural/organic theme */
--color-accent      /* Golden Corn - highlights and CTAs */
```

#### Layout & Surfaces
```css
--color-surface              /* Neutral gray for containers */
--color-background-primary   /* Main app background */
--color-background-secondary /* Secondary background sections */
--color-background-overlay   /* Modal/overlay background */
```

#### Cards
```css
--color-card-primary    /* Main cards - pure white */
--color-card-secondary  /* Secondary cards - warm white */
--color-card-border     /* Card borders */
```

#### Text
```css
--color-text-primary    /* Main text - almost black */
--color-text-secondary  /* Secondary text - medium gray */
--color-text-muted      /* Muted text - light gray */
```

#### Status Colors
```css
--color-success  /* Fresh Green - positive states */
--color-warning  /* Golden Yellow - caution states */
--color-error    /* Barn Red - error states */
--color-info     /* Sky Blue - informational states */
```

### Dark Mode
All colors automatically adapt when `.dark` class is applied to the document. Dark mode uses lighter versions of brand colors and appropriate dark surfaces for optimal contrast and readability.

## Usage Examples

### Tailwind Classes
You can use the design system colors directly in Tailwind:

```html
<!-- Backgrounds -->
<div class="bg-background-primary">Main app background</div>
<div class="bg-card-primary border border-card-border">Card</div>

<!-- Brand Colors -->
<button class="bg-primary text-white">Primary Button</button>
<button class="bg-secondary text-white">Secondary Button</button>
<span class="text-accent">Accent Text</span>

<!-- Text Colors -->
<h1 class="text-text-primary">Main Heading</h1>
<p class="text-text-secondary">Secondary text</p>
<small class="text-text-muted">Muted text</small>

<!-- Status Colors -->
<div class="bg-success text-white">Success</div>
<div class="bg-warning text-white">Warning</div>
<div class="bg-error text-white">Error</div>
<div class="bg-info text-white">Info</div>
```

### Component Examples

#### Card Component
```html
<div class="bg-card-primary border border-card-border rounded-lg shadow-md p-6">
  <h3 class="text-text-primary text-xl font-semibold mb-2">Product Name</h3>
  <p class="text-text-secondary mb-4">Product description goes here.</p>
  <button class="bg-primary text-white px-4 py-2 rounded-md hover:opacity-90">
    Add to Cart
  </button>
</div>
```

#### Status Alert
```html
<div class="bg-success text-white px-4 py-3 rounded-md mb-4">
  <p class="font-medium">Success!</p>
  <p class="text-sm opacity-90">Product added successfully.</p>
</div>
```

#### Form Input
```html
<div class="mb-4">
  <label class="block text-text-primary text-sm font-medium mb-2">
    Product Name
  </label>
  <input 
    type="text" 
    class="w-full px-3 py-2 border border-card-border rounded-md 
           bg-card-primary text-text-primary
           focus:ring-2 focus:ring-primary focus:border-primary"
  >
</div>
```

## Design Tokens Available

- **`primary`** - Main brand color (warm orange)
- **`secondary`** - Secondary brand color (forest green) 
- **`accent`** - Accent color (golden corn)
- **`surface`** - Neutral surface color
- **`background-primary`** - Main background
- **`background-secondary`** - Secondary background
- **`background-overlay`** - Modal overlay
- **`card-primary`** - Main card background
- **`card-secondary`** - Secondary card background
- **`card-border`** - Card border color
- **`text-primary`** - Primary text
- **`text-secondary`** - Secondary text
- **`text-muted`** - Muted text
- **`success`** - Success state
- **`warning`** - Warning state
- **`error`** - Error state
- **`info`** - Info state

## Dark Mode Toggle

To enable dark mode, simply add the `dark` class to your document:

```javascript
// Toggle dark mode
document.documentElement.classList.toggle('dark');
```

## Compatibility

- ✅ Tailwind CSS v4
- ✅ Flux UI Components
- ✅ Modern OKLCH colors
- ✅ Automatic light/dark mode
- ✅ Accessible color contrasts