<?php

namespace App\Console\Commands;

use App\Mail\ReleaseAlertMail;
use App\Models\Game;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('release-alerts:send')]
#[Description('Send email notifications for released games')]
class SendReleaseAlerts extends Command
{
    public function handle(): int
    {
        $games = Game::query()
            ->whereNotNull('release_date')
            ->whereDate('release_date', '<=', today())
            ->whereHas('releaseAlertForUsers', function ($query) {
                $query->whereNull('release_alerts.notified_at');
            })
            ->with([
                'releaseAlertForUsers' => function ($query) {
                    $query->whereNull('release_alerts.notified_at');
                },
            ])
            ->get();

        foreach ($games as $game) {
            foreach ($game->releaseAlertForUsers as $user) {
                try {
                    Mail::to($user->email)
                        ->send(new ReleaseAlertMail($game));

                    $game->releaseAlertForUsers()
                        ->updateExistingPivot($user->id, [
                            'notified_at' => now(),
                        ]);

                    $this->info(
                        "Release alert sent to {$user->email} for {$game->name}"
                    );
                } catch (\Throwable $e) {
                    report($e);

                    $this->error(
                        "Failed to send alert to {$user->email} {$e->getMessage()}"
                    );
                }
            }
        }

        return self::SUCCESS;
    }
}
