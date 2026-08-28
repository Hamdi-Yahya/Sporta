<?php

use Illuminate\Support\Facades\Schedule;

/*
|──────────────────────────────────────────────────────────────
| SPORTA — Scheduled Tasks
|──────────────────────────────────────────────────────────────
*/

// Safety net: expire booking yang melewati batas waktu bayar (tiap 5 menit)
Schedule::command('booking:expire')->everyFiveMinutes();

// Auto-complete booking terkonfirmasi yang jadwalnya sudah berlalu (tiap 15 menit)
Schedule::command('booking:complete')->everyFifteenMinutes();
