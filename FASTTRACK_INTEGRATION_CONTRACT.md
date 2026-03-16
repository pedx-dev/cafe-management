# Cafe <-> FastTrack Integration Contract (Phase 1)

This document defines the canonical request and response shapes for integration work.
All API responses should follow the same envelope:

```json
{
  "success": true,
  "message": "Human readable summary",
  "data": {},
  "errors": []
}
```

## Shared Headers

- API key header name: `X-Integration-Key` (configurable via env)
- Content type: `application/json`
- Accept: `application/json`

## 1) Cafe -> FastTrack (Create Delivery)

- Method: `POST`
- Path: `/api/orders`

### Request Body (Canonical)

```json
{
  "source_system": "cafe-management",
  "source_order_id": 123,
  "order_number": "CAF-20260316-0001",
  "delivery_type": "fasttrack",
  "customer": {
    "name": "Jane Doe",
    "phone": "+639171234567"
  },
  "delivery": {
    "address": "123 Sample St, Cebu City",
    "lat": 10.3157,
    "lng": 123.8854,
    "eta_minutes": 35
  },
  "payment": {
    "method": "cash",
    "status": "unpaid",
    "amount": 599.5,
    "currency": "PHP"
  },
  "items": [
    {
      "menu_item_id": 10,
      "name": "Cappuccino",
      "quantity": 2,
      "unit_price": 120,
      "line_total": 240
    }
  ],
  "notes": "Leave at reception",
  "placed_at": "2026-03-16T10:35:00Z"
}
```

### Success Response (Example)

```json
{
  "success": true,
  "message": "Delivery order received",
  "data": {
    "delivery_order_id": 7781,
    "status": "awaiting_courier",
    "reference": "FT-7781"
  },
  "errors": []
}
```

### Error Response (Example)

```json
{
  "success": false,
  "message": "Validation failed",
  "data": {},
  "errors": [
    {
      "field": "delivery.address",
      "message": "The delivery address field is required."
    }
  ]
}
```

## 2) FastTrack -> Cafe (Status Callback)

- Method: `POST`
- Path: `/api/fasttrack/status-update`

### Request Body (Canonical)

```json
{
  "source_system": "fasttrack",
  "source_order_id": 123,
  "delivery_order_id": 7781,
  "reference": "FT-7781",
  "status": "accepted",
  "courier": {
    "id": 51,
    "name": "Rider One",
    "phone": "+639189999999"
  },
  "event_at": "2026-03-16T10:40:00Z"
}
```

### Success Response (Example)

```json
{
  "success": true,
  "message": "Order status updated",
  "data": {
    "order_id": 123,
    "status": "confirmed"
  },
  "errors": []
}
```

## 3) FastTrack Acceptance Response (Courier API)

When courier accepts via FastTrack `POST /api/accept-delivery`, FastTrack should return:

```json
{
  "success": true,
  "message": "Delivery accepted",
  "data": {
    "delivery_order_id": 7781,
    "status": "accepted",
    "courier_id": 51,
    "accepted_at": "2026-03-16T10:39:00Z"
  },
  "errors": []
}
```

## HTTP Status Conventions

- `200` successful retrieval or state update
- `201` resource created
- `401` invalid API key
- `409` race/duplicate acceptance
- `422` validation failure
- `500` unexpected server error

## Network and Environment Notes

- Keep all URLs in env/config only.
- Avoid hardcoding localhost or fixed ports in controllers/services.
- For two-machine demos, replace localhost with LAN IP.
- If frontend calls APIs directly across ports, configure CORS on both apps.
- Rotate API keys for each demo run if possible.
