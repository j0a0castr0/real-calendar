# real-calendar

A paused proof of concept I built over the turn of the year (Dec 2025 – Jan 2026). I'm keeping it public as a reference for how I like to model a domain: DDD, event sourcing and CQRS, written test-first.

## The idea

Booking a house visit in Portugal usually means sending a message on a portal and waiting for an agent to reply. The idea was to let buyers see an agent's real availability and book a visit asynchronously, and to capture buyer intent even when the agent isn't answering.

It stalled because the main portal (Idealista) doesn't expose the message or contact data the product needed, so I parked it.

## How it's built

**Bounded contexts** (`src/`):
- `RealCalendarContext`: listings, availability slots and visits. Agents add slots to a listing, buyers book a visit in a free slot.
- `WywaContext`: agent responsiveness. It models incoming demand, away periods and a confidence level for how likely an agent is to respond.
- `AuthContext`: AWS Cognito login and JWT validation.
- `Shared`: the event-sourcing building blocks.

**Event sourcing, written by hand rather than with a library:**
- `AggregateRoot` records events and rebuilds state by replaying them.
- An event store (Doctrine/Postgres, plus an in-memory one for tests) with optimistic concurrency through an expected version.
- **Event upcasting**: every event has a schema version, and older stored events are upcast when they're loaded.
- Projections into read models for the queries (CQRS).
- I tried two aggregate styles on purpose: `Listing` raises events, while the `Wywa` aggregates return events from `eventsFor…()` methods (a "decider"-like style).

**Tests:** domain tests written test-first (`tests/*/Domain`) and end-to-end API tests (`tests/*/E2E`).

**Infrastructure:** CloudFormation for ECS, ALB, RDS and Cognito (`aws/`), and a GitHub Actions deploy workflow. It runs on AWS under the working name **Gatolizo** (gatolizo.click). The live app is behind a login, so run it locally to try it.

**Stack:** PHP 8.4, Symfony 8, Doctrine, PHPUnit 12, PostgreSQL, Docker. Frontend in [rl-frontend](https://github.com/j0a0castr0/rl-frontend) (React + TypeScript).

I used AI chatbots as a sounding board, mainly for the UI and to talk through some design choices. I made the final calls on the domain model and the event-sourcing approach.

## Known limitations (it's a PoC)

- The concurrency check reads the current max version and then inserts. Without a unique constraint on `(aggregate_id, version)` two concurrent writes could both pass. A unique index would close that gap.
- Login goes through the backend (admin password auth) to keep the prototype simple. In production I'd let the frontend talk to Cognito directly.
- No README-level docs for the API yet. The E2E tests are the best spec.

## Running it locally

```bash
docker compose up -d          # PostgreSQL
composer install
php bin/console doctrine:migrations:migrate
php bin/phpunit
```
