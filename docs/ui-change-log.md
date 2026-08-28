# UI Change Log

## 2026-08-28

- Rebuilt the Afisha archive from published `performance` posts that have a valid show date; removed the separate Afisha post type, fields and detail template.
- Kept the 3/2/1 card grid and month filtering, with month tabs now ordered newest first while performances inside each month remain chronological.
- Converted the performance show-date field to a date-and-time picker and added a one-time, loss-safe migration for existing values.
- Redirected the former Afisha detail URLs and updated the three homepage promo slides to their matching repertoire pages.

## 2026-08-27

- Unified every `.dgut-breadcrumbs` instance and wrapper through the shared `common.css` styles.
- Standardized the 12px typography, colours, horizontal alignment, 12px SVG chevron and long-current-label truncation.
- Standardized desktop and mobile container alignment and top spacing, then removed conflicting page-specific breadcrumb overrides.
- Simplified the Afisha archive to one equal-card grid: three columns on desktop, two on tablet and one on mobile; removed the special featured event layout.
- Replaced the homepage hero performance selector with a manual SCF repeater for image, crop focus, eyebrow, title, credits, date and CTA link.
- Migrated the four existing homepage slides into the repeater without changing their public content or order.
- Appended the three September Afisha event posters to the homepage hero repeater with event dates and matching detail links.
