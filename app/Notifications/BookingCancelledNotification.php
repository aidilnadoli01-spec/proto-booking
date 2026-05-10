<?php

namespace App\Notifications;

use App\Models\Antrean;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Antrean $antrean)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $dokter = $this->antrean->jadwalDokter?->dokter?->nama ?? '-';
        $tanggal = (string) $this->antrean->tanggal_periksa;
        $nomor = (string) $this->antrean->nomor_antrean;

        return (new MailMessage)
            ->subject('Booking Antrean Dibatalkan')
            ->greeting('Halo '.$notifiable->name)
            ->line("Booking antrean kamu sudah dibatalkan.")
            ->line("Dokter: {$dokter}")
            ->line("Tanggal: {$tanggal}")
            ->line("Nomor antrean: #{$nomor}")
            ->action('Lihat Booking Saya', route('booking.index'));
    }
}

