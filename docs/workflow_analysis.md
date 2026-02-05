# Current Workflow & Problem Analysis

## 1. Current Procedure (As-Is)

The current operation for managing Walmart seller accounts is a manual, multi-step process involving distributed teams and disconnected tools (Google Sheets, Walmart Dashboard, Shippo).

### Type 1: Order Ingestion & Validation
1.  **Manual Monitoring**: A staff member manually logs into multiple Walmart Seller Dashboards to check for new orders.
2.  **Data Transcription**: New order details are manually copied from Walmart and pasted into a "Master Google Sheet".
3.  **Inventory Check**: The staff member opens a separate "Warehouse Inventory Sheet" (one for each warehouse) to verify stock availability.

### Type 2: Fulfillment & Logistics
4.  **Label Creation**: If inventory is sufficient, the staff member manually navigates to the Shippo dashboard to create a shipping label.
5.  **Tracking Update**: The generated tracking number is manually copied from Shippo back into the "Master Google Sheet".

### Type 3: Warehouse Operations
6.  **Physical Packing**: Warehouse staff periodically check the "Master Google Sheet" (or a specific view of it) to see orders ready for packing.
7.  **Shipment Handover**: Meaning items are packed and handed to the carrier.
8.  **Shipment Confirmation**: Warehouse staff manually notify the central team (or update the sheet) that the item has shipped.

### Type 4: Reconciliation
9.  **Final Status Update**: A staff member updates the final status of the order in the sheets.
10. **Inventory Adjustment**: Inventory counts are manually decremented in the "Warehouse Inventory Sheet".

---

## 2. Problem Statement

The reliance on manual data entry and disparate spreadsheets creates several critical bottlenecks and risks:

*   **Data Integrity & Human Error**: Manually copying data between Walmart, Sheets, and Shippo is prone to typos (wrong addresses, wrong SKUs), leading to shipping errors and returns.
*   **Lack of Real-Time Visibility**: Inventory interaction is not atomic. Two orders coming in simultaneously could claim the same stock unit before specific sheets are updated, leading to overselling.
*   **Operational Latency**: The time lag between an order arriving on Walmart and the warehouse seeing it depends on human speed.
*   **Scalability Limits**: As order volume grows, the linear increase in manual labor makes this workflow unsustainable. Adding more clients/accounts exponentially increases complexity.
*   **Disjointed Auditing**: Tracking the lifecycle of a single order requires cross-referencing multiple sheets and dashboard logs.

---

## 3. Critical Requirements for Automation

To resolve these issues, the new system must provide:

1.  **Centralization**: A single "Source of Truth" database replacing all Google Sheets.
2.  **Automation**:
    *   Auto-fetch orders from Walmart via API.
    *   Auto-sync inventory (prevent overselling).
    *   Auto-generate shipping labels via Shippo API.
3.  **Role-Based Access**: Specialized views for "Managers" (Global View) vs. "Warehouse Staff" (Packing View only).

---

## 4. Current Automation Status (Phase 7 Update)

The platform has successfully transitioned key manual procedures into automated routines:

*   **Order Ingestion**: Manual monitoring of Walmart Seller Dashboards has been replaced by the `FetchOrdersJob` and specialized adapters (`WalmartAdapter`).
*   **Source of Truth**: The "Master Google Sheet" has been replaced by the normalized `orders` and `order_items` tables, scoped by Agency.
*   **Visibility**: Real-time business intelligence is provided through the revised Order Console and individual Detailed Views, eliminating the need for manual transcription.

