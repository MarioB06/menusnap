# MenuSnap API Contract

Base URL: `{BASE_URL}/api/v1`

Authentication: Bearer Token via Laravel Sanctum

---

## Authentication

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/auth/register` | No | Register new user |
| POST | `/auth/login` | No | Login user |
| GET | `/auth/profile` | Yes | Get current user profile |
| POST | `/auth/logout` | Yes | Logout (revoke token) |

### POST /auth/login
**Request:**
```json
{ "email": "string", "password": "string" }
```
**Response (200):**
```json
{
  "data": { "id": 1, "name": "...", "email": "...", "created_at": "..." },
  "token": "plaintext-token"
}
```

### POST /auth/register
**Request:**
```json
{
  "name": "string",
  "email": "string",
  "password": "string",
  "password_confirmation": "string"
}
```
**Response (201):** Same as login.

---

## Restaurants

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/restaurants` | Yes | List user's restaurants |
| POST | `/restaurants` | Yes | Create restaurant |
| GET | `/restaurants/{id}` | Yes | Get restaurant with menus/categories/dishes |
| PUT | `/restaurants/{id}` | Yes | Update restaurant |
| DELETE | `/restaurants/{id}` | Yes | Delete restaurant |

### GET /restaurants/{id}
Returns full restaurant with nested menus → categories → dishes.

---

## Menus

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/restaurants/{rid}/menus` | Yes | List menus |
| POST | `/restaurants/{rid}/menus` | Yes | Create menu |
| GET | `/restaurants/{rid}/menus/{mid}` | Yes | Get menu detail |
| PUT | `/restaurants/{rid}/menus/{mid}` | Yes | Update menu |
| DELETE | `/restaurants/{rid}/menus/{mid}` | Yes | Delete menu |
| POST | `/restaurants/{rid}/menus/reorder` | Yes | Reorder menus |

---

## Categories

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/menus/{mid}/categories` | Yes | List categories |
| POST | `/menus/{mid}/categories` | Yes | Create category |
| GET | `/menus/{mid}/categories/{cid}` | Yes | Get category |
| PUT | `/menus/{mid}/categories/{cid}` | Yes | Update category |
| DELETE | `/menus/{mid}/categories/{cid}` | Yes | Delete category |
| POST | `/menus/{mid}/categories/reorder` | Yes | Reorder categories |

---

## Dishes

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/categories/{cid}/dishes` | Yes | List dishes |
| POST | `/categories/{cid}/dishes` | Yes | Create dish |
| GET | `/categories/{cid}/dishes/{did}` | Yes | Get dish |
| PUT | `/categories/{cid}/dishes/{did}` | Yes | Update dish |
| DELETE | `/categories/{cid}/dishes/{did}` | Yes | Delete dish |
| POST | `/categories/{cid}/dishes/reorder` | Yes | Reorder dishes |

### Dish Object
```json
{
  "id": 1,
  "category_id": 1,
  "name": "Margherita Pizza",
  "description": "Fresh mozzarella and basil",
  "price": 12.99,
  "image_url": "https://...",
  "allergens": ["gluten", "dairy"],
  "dietary_tags": ["vegetarian"],
  "is_available": true,
  "sort_order": 0
}
```

---

## Tables

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/restaurants/{rid}/tables` | Yes | List tables |
| POST | `/restaurants/{rid}/tables` | Yes | Create table (auto QR) |
| GET | `/restaurants/{rid}/tables/{tid}` | Yes | Get table |
| PUT | `/restaurants/{rid}/tables/{tid}` | Yes | Update table |
| DELETE | `/restaurants/{rid}/tables/{tid}` | Yes | Delete table |
| GET | `/restaurants/{rid}/tables/{tid}/qr` | Yes | Download QR PNG |

### QR Code URL Format
`{BASE_URL}/menu/{restaurant.slug}/{table.uuid}`

---

## Images

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/dishes/{did}/image` | Yes | Upload dish image (multipart) |
| DELETE | `/dishes/{did}/image` | Yes | Delete dish image |
| POST | `/restaurants/{rid}/logo` | Yes | Upload logo (multipart) |
| DELETE | `/restaurants/{rid}/logo` | Yes | Delete logo |

---

## Plans & Subscriptions

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `/plans` | No | List available plans |
| GET | `/restaurants/{rid}/subscription` | Yes | Get subscription |

---

## Assumptions for Guest Menu Viewing

The current backend serves guest menus via Blade templates (web route `/menu/{slug}/{uuid}`), not via API.

**For the Flutter app's QR scanning feature**, the app:
1. Parses the QR URL to extract the restaurant slug
2. Uses the authenticated API (`GET /restaurants/{id}`) to fetch menu data
3. If a public guest API endpoint is added later, update `MenuRepository` accordingly

**Recommended future backend endpoint:**
```
GET /api/v1/public/menu/{slug}?table={uuid}  (no auth required)
```

---

## Error Responses

| Status | Description |
|--------|-------------|
| 401 | Unauthenticated |
| 403 | Forbidden |
| 404 | Not found |
| 422 | Validation error (`{ "message": "...", "errors": { "field": ["..."] } }`) |
| 429 | Rate limited |
| 500 | Server error |
