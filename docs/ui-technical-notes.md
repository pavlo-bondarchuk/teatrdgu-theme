# UI Technical Notes

## Breadcrumbs

- Breadcrumb appearance and wrapper alignment belong in `assets/css/common.css`; page styles must not override them.
- Render separators through `dgut_breadcrumb_separator_icon()` and keep the Yoast separator filter enabled for every theme breadcrumb.
- Keep the final `.breadcrumb_last` item on one line with ellipsis so long titles do not break the breadcrumb row.

## Afisha and homepage hero

- The Afisha archive uses only `.dgut-afisha-grid`; keep all event cards equal and use the 3/2/1 desktop/tablet/mobile column pattern.
- Do not promote the first or featured event into a separate wide card on the archive.
- Homepage hero slides are page-level manual content in `home_hero_slides`; do not couple their displayed copy or artwork back to `performance` posts.
- Each hero row owns its image, crop focus, eyebrow, title, credits, date and CTA link. Preserve this manual boundary when extending the banner.
- Homepage hero rows may provide a dedicated optional 4:3 mobile image. Render it only in the mobile `<source>`; when absent, continue generating the mobile crop from the required desktop image and its focus setting.
- Afisha is a virtual archive sourced from published `performance` posts with a valid `dgut_performance_date`; do not restore a separate event post type or event detail template.
- Because Afisha is a virtual archive, Polylang cannot infer its translated counterpart. Build switcher URLs from the default `/afisha/` route plus each non-default language slug, and preserve only a valid `YYYY-MM` month query.
- Afisha archive artwork belongs to `dgut_performance_afisha_poster`; preserve its natural aspect ratio and use the performance featured image only as a fallback.
- Afisha cards show a full localized datetime in a labelled calendar block; use the first configured ticket-service URL for the direct ticket CTA and keep the repertoire detail CTA separate with a right chevron.
- Keep the Afisha date block's flexible top margin on `.dgut-afisha-card__time`, not on the footer, so variable title heights do not shift the date row vertically.
- Order month tabs newest first, but keep performance cards chronological inside the selected month.
- Event-promotion slides may coexist with performance slides and keep manual Afisha artwork, but their CTA must link to the matching repertoire detail page.
