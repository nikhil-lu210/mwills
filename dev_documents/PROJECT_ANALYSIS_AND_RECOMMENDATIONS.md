# McWills Consulting — Project Analysis & Recommendations

This document summarizes the current project, your design and logo, and concrete recommendations for building the public website and admin dashboard.

---

## 1. Current Project State

### Tech stack (already in place)
- **Laravel 12** — backend, routing, auth, DB
- **Livewire 4** — reactive UI without writing JS
- **Livewire Flux** — UI component library (buttons, forms, modals, sidebar, etc.)
- **Laravel Fortify** — login, register, 2FA, password reset, email verification
- **Livewire Blaze** — tooling around Livewire

### What exists today
- **Public:** Single route `/` → generic Laravel welcome page (not your consulting design).
- **Auth:** Login, register, 2FA, profile/settings (profile, appearance, security).
- **Dashboard:** `/dashboard` (auth required) with Flux sidebar; placeholder content only.
- **No:** Blog, consultation/enquiry messages, or any content from `website_design_ui.html`.

### Design & branding assets
- **`dev_documents/website_design_ui.html`** — Full single-page mock of the **public** McWills Consulting site:
  - **Pages/sections:** Home, What We Do (Strategy, BD, Talent, Content), Intelligence (blog-like), About, Contact.
  - **Navigation:** Sticky nav, “Book a Consultation” CTA, mobile menu.
  - **Contact:** Enquiry form (name, company, email, area, message) + calendar embed placeholder.
  - **Intelligence:** “From the Intelligence Desk” — article cards with category, title, excerpt, read time (ideal for **blog posts**).
- **`dev_documents/website_logo.png`** — Logo: dark blue “M”/wave + gold upward arrow (growth/progress). Colors align with the design (navy, gold, off-white, slate).

---

## 2. What You Want (Summary)

| Area | Goal |
|------|------|
| **Public site** | Replace current `/` with the McWills design: Home, Services, Intelligence, About, Contact. |
| **Admin** | Login-only area to **maintain** the site. |
| **Blog** | Write and manage posts; show them on the public site as “Intelligence” / “From the Intelligence Desk”. |
| **Consultation messages** | Capture “Book a Consultation” / contact form submissions and manage them in the admin (view, status, maybe reply). |
| **“Many more”** | Room for future admin features (e.g. pages, downloads, settings). |

---

## 3. Recommendations — What’s Better for You

### 3.1 Keep Laravel + Livewire + Flux

**Recommendation: Keep this stack.**

- You already have auth (Fortify), dashboard layout (Flux sidebar), and settings.
- Livewire + Flux fits “admin dashboard” very well (tables, forms, modals, filters) and keeps everything in one app.
- One codebase, one deployment, shared auth and models.

No need to introduce a separate CMS or different front-end framework for the admin.

### 3.2 Public site: Blade + Tailwind (from your design)

**Recommendation: Implement the public site in Laravel with Blade and Tailwind.**

- Your design file already uses Tailwind (CDN) and defines a clear palette: `navy`, `gold`, `offwhite`, `slate`, `max-w-content`, etc.
- Prefer **server-rendered Blade views** for the public site:
  - Better SEO for “Intelligence” articles and service pages.
  - Simpler than converting the whole thing to a SPA.
  - You can still use small Livewire bits where needed (e.g. contact form, newsletter).
- Use **proper routes** (e.g. `/`, `/services/strategy`, `/intelligence`, `/about`, `/contact`) instead of the current single-page JS “router” in the HTML mock. This gives shareable URLs and clearer structure for the admin (e.g. “edit homepage”, “list blog posts”).

### 3.3 Logo and branding

**Recommendation: Use the provided logo and align colors everywhere.**

- **Logo:** Copy `dev_documents/website_logo.png` into `public/images/` (e.g. `logo.png`) and use it in:
  - Public site: nav, footer, favicon (resize for favicon).
  - Admin: sidebar/header so the dashboard clearly belongs to McWills.
- **Colors:** Reuse the design tokens everywhere (public and admin):
  - Primary: navy `#1A2B4A`
  - Accent: gold `#C9A042`
  - Background: offwhite `#F4F6F9`
  - Muted text: slate `#8A9BB0`
- This keeps the public site and the “feel” of the admin consistent with the logo (growth, professionalism).

### 3.4 Admin: Extend current dashboard

**Recommendation: Treat the existing Flux dashboard as the admin shell and add sections.**

- **Routes:** Keep `/login`, `/dashboard`, `/settings/...` as-is. Add admin routes under a prefix or under `/dashboard` (e.g. `/dashboard`, `/dashboard/posts`, `/dashboard/messages`).
- **Sidebar:** Add items in `resources/views/layouts/app/sidebar.blade.php`:
  - Dashboard (overview)
  - **Posts** (blog / Intelligence)
  - **Consultation messages** (enquiries from the contact form)
  - Later: e.g. Pages, Downloads (Africa Playbook), Settings
- **Implementation:** Use **Livewire + Flux** for list views (tables, filters, search), create/edit forms (modals or full pages), and delete confirmations. This matches your existing settings pages and keeps the UI consistent.

### 3.5 Data you need

**Recommendation: Add two main content types first.**

1. **Blog posts (Intelligence)**
   - Table: `posts` (or `intelligence_articles`)
   - Fields: title, slug, category (e.g. “The Vacancy”, “BD & Growth”), excerpt, body (HTML or Markdown), read_time_minutes, published_at, created_at, updated_at.
   - Admin: list (with status/draft/published), create, edit, delete, optional image.
   - Public: list on Intelligence page; detail at e.g. `/intelligence/{slug}`.

2. **Consultation / contact messages**
   - Table: `consultation_messages` or `enquiries`
   - Fields: name, company, email, area (strategy/bd/talent/content/not_sure), message, status (new/read/replied/archived), created_at, optional notes (admin-only).
   - Admin: list with filters (status, area), view, mark read/replied, optional reply-by-email later.
   - Public: contact form submits to this table (and optionally sends you an email notification).

Optional later:
- **Pages** — e.g. editable “About” or service intro blocks.
- **Downloads** — e.g. “Africa Playbook” PDF, with optional email-gate.
- **Settings** — site name, contact email, Calendly/embed URL, etc.

### 3.6 Contact form and “Book a Consultation”

- **Form:** Implement the contact/enquiry form from the design (name, company, email, area, message) as a Livewire component or classic form that:
  - Validates and stores a row in `consultation_messages`.
  - Optionally sends a notification email to you.
  - Redirects to a “Thank you” page.
- **Calendar embed:** Use the placeholder in the design to embed Calendly/TidyCal (iframe or link). Store the embed URL in config or a simple settings table so you can change it from the admin later if you add “Settings”.

### 3.7 Auth and access control

- **Recommendation:** Keep registration **only if** you want multiple admins (e.g. team members). Otherwise you can disable registration and create your admin user via tinker or a one-time seeder.
- Restrict all dashboard routes with `auth` and `verified` middleware (you already do this for `/dashboard`). No need for roles/permissions until you have a second role (e.g. “editor” vs “admin”).

---

## 4. Suggested Phasing

| Phase | What to do |
|------|------------|
| **1. Foundation** | Copy logo to `public`, add Tailwind theme (navy, gold, offwhite, slate). Create layout Blade for the public site (header, footer, nav) and one “Home” view using content from the design. Route `/` to this home view. |
| **2. Public pages** | Add routes and Blade views for Services (strategy, bd, talent, content), About, Contact. Implement contact form (Livewire or form POST) and save to `consultation_messages`. Thank-you page after submit. |
| **3. Blog (Intelligence)** | Migrations for `posts`. Admin: Livewire list + create/edit (Flux forms). Public: Intelligence listing page and `/intelligence/{slug}`. Optional: categories, featured image. |
| **4. Admin messages** | Livewire list of consultation messages (table + filters), detail view, status updates. Optional: email notification on new message. |
| **5. Polish** | Favicon from logo. Calendly/embed on contact page. Optional: “Download Africa Playbook” (link or gate). Later: editable About/service copy, site settings. |

---

## 5. What to Avoid (for your case)

- **Don’t** keep the public site as a single giant HTML file with a JS router — move to Blade + routes for maintainability and SEO.
- **Don’t** introduce a second stack (e.g. separate React/Vue SPA for the public site) unless you have a strong reason; Blade + optional Livewire is enough.
- **Don’t** over-engineer roles/permissions until you have more than one admin.
- **Don’t** forget to use the real logo and color tokens on both public and admin so the brand feels one.

---

## 6. Summary Table

| Question | Recommendation |
|----------|----------------|
| Framework for public site? | Laravel Blade + Tailwind, using your design tokens and structure. |
| Framework for admin? | Existing Laravel + Livewire + Flux dashboard; add Posts and Messages sections. |
| Blog vs “Intelligence”? | Same thing — blog posts in DB, displayed as “From the Intelligence Desk” on the public site. |
| Contact form? | Store in `consultation_messages`; manage in admin; optional email alert. |
| Logo? | Use `website_logo.png` in nav/footer and as favicon; align colors (navy, gold) everywhere. |
| Next step? | Phase 1: public layout + Home page + logo + Tailwind theme; then contact form + messages table; then blog. |

---

If you tell me which phase you want to start with (e.g. “Phase 1: public layout and home” or “Phase 3: blog”), I can outline exact file changes and code steps next.
