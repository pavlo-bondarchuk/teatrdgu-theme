# UI Technical Notes

## Breadcrumbs

- Breadcrumb appearance and wrapper alignment belong in `assets/css/common.css`; page styles must not override them.
- Render separators through `dgut_breadcrumb_separator_icon()` and keep the Yoast separator filter enabled for every theme breadcrumb.
- Keep the final `.breadcrumb_last` item on one line with ellipsis so long titles do not break the breadcrumb row.
