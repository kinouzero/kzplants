<?php

namespace App\Services;

use App\Mail\NotificationMail;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NotificationService
{
    public function send(Notification $notification, string $title, string $message, array $context = []): void
    {
        $config = $notification->config();
        $channels = $config['channels'] ?? [];
        if (! is_array($channels) || empty($channels)) {
            return;
        }

        $users = $notification->users()->wherePivot('active', true)->get();
        if ($users->isEmpty()) {
            return;
        }

        if (in_array('mail', $channels, true)) {
            $subject = $config['mail_subject'] ?: $title;
            foreach ($users as $user) {
                if (! $user->email) {
                    continue;
                }
                Mail::to($user->email)->send(new NotificationMail($subject, $message));
            }
        }

        if (in_array('ntfy', $channels, true)) {
            $this->sendNtfy($title, $message, $config, $context);
        }
    }

    private function sendNtfy(string $title, string $message, array $config, array $context): void
    {
        $baseUrl = rtrim($config['ntfy_url'] ?? config('ntfy.url'), '/');
        $topic = $config['ntfy_topic'] ?? config('ntfy.default_topic');
        if (! $baseUrl || ! $topic) {
            return;
        }

        $headers = [
            'Title' => $title,
        ];

        $priority = $config['ntfy_priority'] ?? null;
        if ($priority) {
            $headers['Priority'] = (string) $priority;
        }

        $tags = $config['ntfy_tags'] ?? null;
        if ($tags) {
            $headers['Tags'] = is_array($tags) ? implode(',', $tags) : (string) $tags;
        }

        $token = config('ntfy.token');
        if ($token) {
            $headers['Authorization'] = 'Bearer '.$token;
        }

        $body = $message;
        if (! empty($context)) {
            $body .= "\n\n".Str::limit(json_encode($context), 1000);
        }

        Http::withHeaders($headers)->post($baseUrl.'/'.$topic, $body);
    }
}
