# System Design & Development Plan (v3: SaaS Multi-Agency)

## 1. High-Level Architecture (SaaS Model)

The platform is now a **Multi-Tenant SaaS**. 
*   **Tenants = Agencies**: Use the software to manage their business.
*   **Sub-Entities = Clients**: The agencies' customers (who own the seller accounts).

### Hierarchy
1.  **Super Admin**: Operates the SaaS Platform itself.
2.  **Agency (Tenant)**: Pays for the software. Has its own isolated data.
3.  **Client**: A customer of an Agency.
4.  **Integration**: A store belonging to a Client.

---

## 2. Database Schema (Schema-Based Tenancy)

We will use **Discriminator-based Multi-tenancy** (Shared Database, `agency_id` column) for simplicity and performance, rather than separate databases per tenant.

### A. Tenancy Layer
1.  **Agencies** (New Top Level)
    *   `id`, `name`, `domain` (optional for white-labeling), `plan_subscription_id`
    *   *Purpose*: Data isolation boundary.

2.  **Users** (Updated)
    *   `id`, `agency_id` (Nullable - NULL for Super Admins), `name`, `email`, `role`
    *   *Constraint*: Users belong to one Agency (or are Super Admins).

### B. Agency Data (Scoped)
All following tables MUST have an `agency_id` foreign key for scoping.

3.  **Clients**
    *   `id`, `agency_id` (FK), `name`, `code`
    *   *Scope*: Unique `code` per Agency.

4.  **Warehouses**
    *   `id`, `agency_id` (FK), `name`, `location_code`
    *   *Note*: Agencies manage their own warehouses.

5.  **Products** (Master Catalog)
    *   `id`, `agency_id` (FK), `client_id`, `master_sku`
    *   *Constraint*: `master_sku` unique per Agency/Client tuple.

6.  **Orders**
    *   `id`, `agency_id` (FK), `client_id`, `external_order_id`

7.  **Integration_Connections**
    *   `id`, `agency_id` (FK), `client_id`, `credentials`

---

## 3. Development Roadmap (Pivot)

### Phase 1: Re-Factor for Tenancy (Immediate Priority)
*   **Goal**: Inject `agency_id` into all existing tables.
*   **Action**: 
    1.  Create `agencies` migration.
    2.  Update `users`, `clients`, `warehouses` migrations to include `agency_id`.
    3.  Update Models to include `BelongsTo` Agency.
    4.  (Future) Implement Global Scope `AgencyScope`.

### Phase 2: Adapter Pattern (Refined)
*   The `WalmartAdapter` logic has been hardened to handle nested data structures and aggregate financial charges manually when top-level summaries are absent in API payloads.
*   Implemented a robust mapping strategy for `Order` and `OrderItem` models, ensuring data fidelity across different marketplace formats.

### Phase 3: Modern UI & Agentic UX
*   **Infrastructure**: Integrated Alpine.js for lightweight state management (Sidebar, Modals).
*   **Navigation Architecture**: Implemented a collapsible, persistent sidebar to accommodate the expanding module list.
*   **Data Visualization**: Upgraded index consoles to multi-dimensional layouts, grouping related metrics (e.g., Status + Timestamp, Amount + Currency).
*   **Entity Ingress**: Developed highly specialized detail views (Show routes) to provide deep intelligence on ingested commerce data.
### Phase 4: Enhanced Data Exploration (Filtering & Sorting)
*   **Infrastructure**: Created a reusable `FilterableController` trait and `FilterSortBar` component.
*   **Filtering**: Implemented context-aware filters (Search, Date Ranges, Status, Relations) across all major resource indices.
*   **Sorting**: Added column-based sorting with visual indicators and relationship support.
*   **UX**: Designed a mobile-first, collapsible filter panel with URL persistence for shareable views.
