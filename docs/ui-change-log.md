# UI Change Log

## 2026-08-31

- Added an optional mobile image to each manual homepage hero slide; mobile uses that image when supplied and retains the existing focused desktop crop as a fallback.
- Corrected the Afisha language switcher so its Ukrainian route remains `/afisha/` and each translated route is generated dynamically from the Polylang language slug, while retaining a selected month.

## 2026-08-28

- Anchored every Afisha card date block to the bottom of the flexible card content so dates and footers stay level when titles wrap to different line counts.
- Expanded Afisha card dates into a labelled calendar block with the full localized date and time; added a direct ticket-service CTA and a chevron to the repertoire detail link.
- Added a dedicated Afisha poster image to repertoire entries; Afisha cards use it in its natural aspect ratio and fall back to the performance featured image when it is empty.
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
