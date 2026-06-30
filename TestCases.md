# Vanilla PHP Multi-Tenant CMS REST API - Critical Test Cases

This document lists only the high-priority, critical test cases representing core infrastructure reliability, security boundaries, and data persistence logic.

---

## 1. Core Framework Infrastructure (Unit Tests)

### Container (DI Autowiring)
*   **TC-CON-001**: Verify recursive autowiring successfully instantiates a controller and resolves its nested constructor dependencies from the container.
*   **TC-CON-002**: Verify autowiring throws a clear Exception if a constructor dependency is a built-in type (e.g. `string`) with no default value.

### Router & Middleware Pipeline
*   **TC-RTR-001**: Verify routes registered with custom attributes (`#[Route]`) are mapped correctly, and request dispatching runs the declared middleware pipeline sequentially before executing the controller.
*   **TC-RTR-002**: Verify requests to any `/api/*` endpoints are automatically intercepted and authorized via `ApiAuth`.

### Query Builder (RepositoryBase)
*   **TC-REP-001**: Verify `buildWhereClauses()` correctly parameters-binds diverse condition types (e.g. `LIKE`, `IN`, `BETWEEN`, `IS NULL`) and sanitizes column names to prevent SQL Injection.
*   **TC-REP-002**: Verify that `findAll()` handles pagination correctly by querying `limit + 1` rows to determine and return the correct `hasNextPage` boolean.

---

## 2. Security & Guarding (Integration / E2E Tests)

### API Authentication & Host Restriction
*   **TC-SEC-001**: Verify `/api/*` endpoints reject requests missing `Authorization` basic credentials or the `Origin` header.
*   **TC-SEC-002**: Verify API access is denied when the request origin does not match the API Key's domain restriction (`siteHost`) saved in the database.

### CSRF Protection & Rate Limiting
*   **TC-SEC-003**: Verify POST requests to web UI actions (e.g., `/register`, `/login`) fail with `401 Unauthorized` if the CSRF token in the request header or body is invalid or missing.
*   **TC-SEC-004**: Verify register attempts are rate limited (5 request limits per IP, 3 requests per email address within a 5-minute window).

---

## 3. Dynamic Database Schema & CRUD (Integration Tests)

### Dynamic Table DDL
*   **TC-DYN-001**: Verify that inserting a new custom field executes DDL creating a dedicated `field_data_[field_slug]` table with the correct schema and index.

### Content & Custom Field Persistence
*   **TC-DYN-002**: Verify user authentication, custom content type definition, and subsequent content record creation flow completes successfully.
*   **TC-DYN-003**: **[CRITICAL BUG]** Verify that invoking the `/api/fields/save-data` endpoint saves custom field data successfully.
    *(Note: This test will currently fail because `FieldDataRepository.php` omits `content_id` in its `INSERT` SQL statement, violating the schema's `NOT NULL` constraint. This test is essential to prove the bug exists and to verify the eventual fix.)*
