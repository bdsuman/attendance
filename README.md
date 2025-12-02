# Attendance

A lightweight Laravel-based attendance system. This repository contains models, controllers, resources, and API documentation scaffolding for managing employees, shifts and daily attendance entries.

## Quick summary
- Framework: Laravel (app skeleton present)
- PHP: Project targets PHP ^8.2 (see `composer.json`)
- Key features:
  - Time helpers to convert HH:MM / minutes ↔ seconds
  - Shift model stores times in seconds; `ShiftResource` presents HH:MM
  - Attendance flow consolidated into a status-driven endpoint (check_in/check_out/break_in/break_out)
  - Computed attendance fields persisted (worked_seconds, worked_hours, calculated_status, late_minutes, early_leave_minutes)
  - Observer to compute/persist attendance derived fields



