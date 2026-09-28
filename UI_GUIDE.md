# MarketLink UI Guide

## Visual direction

MarketLink uses the **eGreen Basket** theme. It should feel natural, agricultural, trustworthy, modern, premium, simple, spacious, professional, and accessible. It must not resemble a blue technology dashboard, crypto platform, game, generic SaaS template, or over-animated student project.

## Core palette

| Token | Value | Intended use |
| --- | --- | --- |
| Forest | `#163D2A` | Brand depth, strong text, navigation |
| Green | `#168B4B` | Primary actions and active states |
| Leaf | `#55B83E` | Selective highlights and positive accents |
| Cream | `#F7F6F0` | Warm page background |
| Surface | `#FCFBF7` | Cards and elevated content surfaces |
| Text | `#17221B` | Primary copy |
| Muted | `#68756D` | Secondary copy and metadata |

These values are defined as `--ml-*` custom properties in `resources/css/app.css`. Reuse the tokens rather than copying hex values into feature styles.

## Visual rules

Do:

- Use natural agricultural and realistic produce photography.
- Use restrained green accents and warm cream/off-white backgrounds.
- Use generous whitespace, clear hierarchy, and easy-to-understand navigation.
- Keep cards restrained and animations subtle and purposeful.
- Build responsive layouts from small screens upward.
- Use clean typography, visible keyboard focus, semantic HTML, descriptive labels, and sufficient contrast.

Do not:

- Use excessive gradients, neon, or blue as the primary brand color.
- Apply glassmorphism everywhere or blur every item.
- Add excessive motion or make every container heavily rounded.
- Fill the experience with cartoon farm graphics or childish visuals.
- Redesign, redraw, recolor, stretch, crop, or otherwise alter the official MarketLink logo.

## Components and layout

Shared layout shells belong in `resources/views/layouts`; reusable small Blade components belong in `resources/views/components`. Feature views belong in their role/module folder. Before creating a new component, check whether an existing shared component can be extended without coupling unrelated features.

Use the smallest radius that supports the hierarchy. Prefer real spacing and border contrast over heavy shadows. Interactive controls need default, hover, focus, disabled, loading, error, and success behavior where applicable. Never communicate state by color alone.

## Responsive and accessibility baseline

- Support keyboard navigation and a visible `:focus-visible` treatment.
- Use landmarks, headings in order, form labels, useful alternative text, and accessible status messages.
- Do not rely on hover-only controls.
- Prevent horizontal page overflow at 320px width.
- Keep tap targets comfortably usable on touch devices.
- Respect `prefers-reduced-motion` when motion is introduced.
- Test complete screens at mobile, tablet, and desktop widths before review.
