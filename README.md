# Fall Guardian Backend

Symfony/API Platform backend for device identity, fall-alert persistence, caregiver linking, push notification delivery, and alert acknowledgement.

## Responsibilities

- Register protected-person and caregiver devices.
- Authenticate device API calls with bearer device tokens.
- Persist fall alerts submitted by the protected-person phone app.
- Link caregiver devices through short-lived invite codes.
- Store caregiver push tokens.
- Dispatch backend-owned push notifications for linked caregivers.
- Persist push delivery attempts for auditability.
- Track caregiver acknowledgement of alerts.

Caregiver escalation is push-notification based.

## Runtime Flow

```text
watch detects fall
-> phone owns countdown through AlertCoordinator
-> timeout submits /api/v1/fall-alerts
-> backend persists FallAlert
-> backend dispatches SendFallAlertPushMessage
-> Messenger handler sends push to linked caregiver devices
-> caregiver can acknowledge the alert
```

## Architecture

Backend business code is organized by domain under `src/Domain`.

```text
src/
├── Domain/
│   ├── Alert/
│   │   ├── Handler/
│   │   ├── Message/
│   │   ├── Port/
│   │   ├── Processor/
│   │   ├── Provider/
│   │   ├── Request/
│   │   ├── Response/
│   │   └── Service/
│   ├── Caregiver/
│   ├── Debug/
│   ├── Device/
│   ├── Healthcheck/
│   └── Push/
├── Entity/
├── Enum/
└── Infrastructure/
```

Common flow:

```text
Request/*InputDTO
-> Processor or Provider
-> Service or Handler
-> Port
-> Infrastructure adapter
-> Entity / database / gateway
-> Response/*OutputDTO
```

## Public API

- `POST /api/v1/devices/register`
- `POST /api/v1/fall-alerts`
- `GET /api/v1/fall-alerts/{id}`
- `POST /api/v1/fall-alerts/{clientAlertId}/cancel`
- `POST /api/v1/invites`
- `POST /api/v1/invites/{code}/accept`
- `POST /api/v1/caregiver/push-token`
- `GET /api/v1/caregiver/alerts`
- `POST /api/v1/fall-alerts/{id}/acknowledge`
- `GET /health`

Development/debug:

- `GET /debug/fake-push` is available outside production when the fake push provider is configured.

## Local Commands

```bash
make up
make test-unit
make test-integration
make test-behat
make analyse
make lint-dry
```

Useful direct commands:

```bash
vendor/bin/phpunit --testsuite=unit
vendor/bin/phpunit --testsuite=integration
vendor/bin/behat --config behat.yaml.dist --colors
vendor/bin/phpstan analyse --no-progress --memory-limit=-1
vendor/bin/php-cs-fixer fix --dry-run --diff --verbose --sequential
```

## Configuration

Core environment variables:

- `APP_SECRET`
- `DATABASE_URL`
- `MESSENGER_TRANSPORT_DSN`
- `APP_SHARE_DIR`
- `PUSH_PROVIDER` (`fake` or `fcm`)
- `FCM_PROJECT_ID`
- `FCM_SERVICE_ACCOUNT_JSON`

The fake push provider writes messages under `var/share/fake_push_inbox.jsonl`.
